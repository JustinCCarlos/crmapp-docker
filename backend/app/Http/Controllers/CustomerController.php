<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CustomerService;

class CustomerController extends Controller
{
  protected CustomerService $customerService;

    public function __construct(
      CustomerService $customerService
    ){
        $this->customerService = $customerService;
    }

    public function getAllCustomers(){
      return response()->json($this->customerService->getAllCustomers());
    }

    public function searchCustomer(Request $request){
      $term = $request->input('query');
      $results = $this->customerService->searchCustomers($term);
      return response()->json($results);
    }
}
