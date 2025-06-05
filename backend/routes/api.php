<?php

use App\Http\Controllers\CustomerController;
use App\Services\ElasticsearchService;

Route::get('/customers', [CustomerController::class, 'getAllCustomers']);

Route::get('/customers/search', [CustomerController::class, 'searchCustomer']);
