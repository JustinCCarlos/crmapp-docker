<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
  protected $primaryKey = 'customer_id';

  protected $fillable = [
    'first_name',
    'last_name',
    'email',
    'contact_number',
  ];

  public $timestamps = true;

  public function toElasticsearchDocument()
  {
      return [
          'id' => $this->id,
          'first_name' => $this->first_name,
          'last_name' => $this->last_name,
          'email' => $this->email,
          'contact_number' => $this->contact_number,
          'full_name' => $this->first_name . ' ' . $this->last_name,
          'created_at' => $this->created_at->toISOString(),
          'updated_at' => $this->updated_at->toISOString(),
      ];
  }
}
