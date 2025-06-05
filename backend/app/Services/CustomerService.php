<?php

namespace App\Services;

use App\Models\Customer;
use App\Services\ElasticsearchService;
use Illuminate\Support\Facades\Log;

class CustomerService
{
  protected ElasticsearchService $elasticsearch;

  public function __construct(
    ElasticsearchService $elasticsearch
  ){
    $this->elasticsearch = $elasticsearch;
  }

    public function getAllCustomers()
    {
        return Customer::all();
    }

    public function searchCustomers(string $term)
    {
      $query = [
        'multi_match' => [
            'query' => $term,
            'fields' => ['first_name', 'last_name', 'email', 'contact_number']
        ]
      ];

    $response = $this->elasticsearch->search('customers', $query);
      $hits = $response['hits']['hits'] ?? [];

      return array_map(fn($hit) => $hit['_source'], $hits);
    }

    public function getCustomer(int $id)
    {
      return Customer::findOrFail($id);
    }

    public function createCustomer(array $data)
    {
      $customer = Customer::create($data);

      // Try to index in Elasticsearch
      try {
          $this->elasticsearch4ndexDocument('customers', $customer->customer_id, $customer->toArray());
          Log::info("Customer {$customer->customer_id} created and indexed in Elasticsearch");
      } catch (\Exception $e) {
          Log::error("Customer {$customer->customer_id} created in MySQL but failed to index in Elasticsearch: " . $e->getMessage());
      }

      return $customer;
    }

    public function updateCustomer(int $id, array $data)
    {
      $customer = Customer::findOrFail($id);
      $customer->update($data);

      // Try to index in Elasticsearch
      try {
        $this->elasticsearch->indexDocument('customers', $customer->customer_id, $customer->toArray());
        Log::info("Customer {$customer->customer_id} updated and indexed in Elasticsearch");
      } catch (\Exception $e) {
        Log::error("Customer {$customer->customer_id} updated in MySQL but failed to index in Elasticsearch: " . $e->getMessage());
      }

      return $customer;
    }

    public function deleteCustomer(int $id)
    {
      $customer = Customer::findOrFail($id);
      
      // Try to index in Elasticsearch
      try {
        $this->elasticsearch->deleteDocument('customers', $customer->customer_id);
        Log::info("Customer {$customer->customer_id} deleted from Elasticsearch");
      } catch (\Exception $e) {
        Log::error("Failed to delete customer {$customer->customer_id} from Elasticsearch: " . $e->getMessage());
      }

      $customer->delete();

      return $customer;
    }
}