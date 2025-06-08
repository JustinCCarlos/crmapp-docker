<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::create([
            'first_name' => 'Justin Charles',
            'last_name' => 'Carlos',
            'email' => 'justincharles.carlos@example.com',
            'contact_number' => '(+63) 908-869-9539',
        ]);

        Customer::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'contact_number' => '+1-555-123-4567',
        ]);

        Customer::create([
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane.smith@example.com',
            'contact_number' => '+1-555-987-6543',
        ]);

        Customer::create([
            'first_name' => 'Alice',
            'last_name' => 'Johnson',
            'email' => 'alice.johnson@example.com',
            'contact_number' => '+1-555-456-7890',
        ]);
    }
}
