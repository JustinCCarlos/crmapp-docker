<?php

use App\Services\ElasticsearchService;

class Customer
{
  protected ElasticsearchService $elasticsearch;
  public function __construct(ElasticsearchService $elasticsearch)
  {
    $this->elasticsearch = $elasticsearch;
  }

  public function created(Customer $customer)
  {
    $this->elasticsearch->index([
      'index' => 'customers',
      'id'    => $customer->customer_id,
      'body'  => $customer->toArray(),
    ]);
  }

  public function updated(Customer $customer)
  {
    $this->elasticsearch->index([
      'index' => 'customers',
      'id'    => $customer->customer_id,
      'body'  => $customer->toArray(),
    ]);
  }

  public function deleted(Customer $customer)
  {
    $this->elasticsearch->delete([
      'index' => 'customers',
      'id'    => $customer->customer_id,
    ]);
  }

}