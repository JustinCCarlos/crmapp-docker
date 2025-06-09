<?php

use App\Http\Controllers\CustomerController;
use App\Services\ElasticsearchService;

Route::get('/customers', [CustomerController::class, 'getAllCustomers']);

//Route::get('/customers/search', [CustomerController::class, 'searchCustomer']);

Route::get('/customers/{id}', [CustomerController::class, 'showCustomer']);

Route::post('/customers', [CustomerController::class, 'createCustomer']);

Route::put('/customers/{id}', [CustomerController::class, 'updateCustomer']);

Route::delete('/customers/{id}', [CustomerController::class, 'deleteCustomer']);