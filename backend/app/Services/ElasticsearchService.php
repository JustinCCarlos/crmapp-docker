<?php
namespace App\Services;

use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Support\Facades\Log;

class ElasticsearchService
{
    protected $client;

    public function __construct()
    {
        $host = config('services.elasticsearch.host', 'searcher');
        $port = config('services.elasticsearch.port', '9200');

        if (!str_starts_with($host, 'http://') && !str_starts_with($host, 'https://')) {
            $host = 'http://' . $host;
        }

        $fullHost = "{$host}:{$port}";
        Log::info("Elasticsearch host: {$fullHost}");

        $this->client = ClientBuilder::create()
            ->setHosts([$fullHost])
            ->build();
    }

    public function getClient()
    {
        return $this->client;
    }

    public function search($index, $query)
    {
        $params = [
            'index' => $index,
            'body' => [
                'query' => $query
            ]
        ];

        return $this->client->search($params);
    }

    public function indexExists($index)
    {
        try {
            $response = $this->client->indices()->exists(['index' => $index]);
            return $response->getStatusCode() === 200;
        } catch (\Exception $e) {
            Log::error("Error checking if index exists: " . $e->getMessage());
            return false;
        }
    }

    public function createIndex($index, $mapping = [])
    {
        $params = ['index' => $index];
        
        if (!empty($mapping)) {
            $params['body'] = $mapping;
        }

        return $this->client->indices()->create($params);
    }

    public function getDocument($index, $id)
    {
      try {
        $params = [
          'index' => $index,
          'id' => $id
        ];
        return $this->client->get($params);
      } catch (\Exception $e) {
        Log::error("Error getting document: " . $e->getMessage());
        return null;
      }
    }

    public function indexDocument($index, $id, $document)
    {
      $params = [
        'index' => $index,
        'id' => $id,
        'body' => $document,
        'refresh' => 'wait_for'
      ];

        return $this->client->index($params);
    }

    public function deleteDocument($index, $id){
      $params = [
        'index' => $index,
        'id' => $id
      ];
      
      return $this->client->delete($params);
    }
  

    public function deleteIndex($index)
    {
        return $this->client->indices()->delete(['index' => $index]);
    }
}