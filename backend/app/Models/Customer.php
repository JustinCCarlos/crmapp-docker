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


}
