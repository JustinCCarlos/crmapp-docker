<?php

namespace App\Services;

use App\Models\Customer;
use App\Services\ElasticsearchService;


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
}