<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Services\ElasticsearchService;
use Illuminate\Console\Command;

class SyncCustomerToElasticsearch extends Command
{
    protected $signature = 'customers:sync-elasticsearch';
    protected $description = 'Sync customers from MySQL to Elasticsearch';

    private $elasticsearch;

    public function __construct(ElasticsearchService $elasticsearch)
    {
        parent::__construct();
        $this->elasticsearch = $elasticsearch;
    }

    public function handle()
    {
        $this->info('Starting customer sync to Elasticsearch...');

        try {
            // Test the connection first
            $this->info('Testing Elasticsearch connection...');
            $client = $this->elasticsearch->getClient();
            $info = $client->info();
            $this->info('Connected to Elasticsearch cluster: ' . $info['cluster_name']);

            // Check if index exists
            $indexName = 'customers';
            $indexExists = false;
            
            try {
                $response = $client->indices()->exists(['index' => $indexName]);
                $indexExists = $response->getStatusCode() === 200;
            } catch (\Exception $e) {
                $this->info('Index does not exist, will create it.');
                $indexExists = false;
            }

            if (!$indexExists) {
                $this->info('Creating customers index...');
                
                $mapping = [
                    'mappings' => [
                        'properties' => [
                            'id' => ['type' => 'integer'],
                            'first_name' => ['type' => 'text'],
                            'last_name' => ['type' => 'text'],
                            'email' => ['type' => 'keyword'],
                            'contact_number' => ['type' => 'keyword'],
                            'created_at' => ['type' => 'date'],
                            'updated_at' => ['type' => 'date']
                        ]
                    ]
                ];

                $client->indices()->create([
                    'index' => $indexName,
                    'body' => $mapping
                ]);
                $this->info(' Index created successfully!');
            } else {
                $this->info(' Index already exists.');
            }

            // Get all customers from MySQL
            $customers = Customer::all();
            $this->info("Found {$customers->count()} customers to sync");

            if ($customers->count() === 0) {
                $this->warn('No customers found in database!');
                return;
            }

            foreach ($customers as $customer) {
                // Index each customer
                $client->index([
                    'index' => $indexName,
                    'id' => $customer->customer_id,
                    'body' => $customer->toArray()
                ]);
                
                $this->info("Synced customer: {$customer->first_name} {$customer->last_name}");
            }

            $this->info("Customer sync completed successfully!");

        } catch (\Exception $e) {
            $this->error('Error during sync: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
        }
    }
}