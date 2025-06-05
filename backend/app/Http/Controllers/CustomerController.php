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

    public function getAllCustomers()
    {
      return response()->json($this->customerService->getAllCustomers());
    }

    public function searchCustomer(Request $request)
    {
      $term = $request->input('query');
      $results = $this->customerService->searchCustomers($term);
      return response()->json($results);
    }

    public function showCustomer($id){
      return response()->json($this->customerService->getCustomer($id));
    }

    public function createCustomer(Request $request)
    {
      $validated = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|unique:customers,email',
        'contact_number' => 'nullable|string|max:20',
      ]);

      return response()->json($this->customerService->createCustomer($validated));
    }

    public function updateCustomer(Request $request, $id)
    {
      $validated = $request->validate([
        'first_name' => 'sometimes|required|string|max:255',
        'last_name' => 'sometimes|required|string|max:255',
        'email' => 'sometimes|required|email|unique:customers,email,' . $id . ',customer_id',
        'contact_number' => 'nullable|string|max:20',
      ]);

      return response()->json($this->customerService->updateCustomer($id, $validated));
    }

    public function deleteCustomer($id)
    {
      return response()->json($this->customerService->deleteCustomer($id));
    }
}
