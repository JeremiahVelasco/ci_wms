<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'name' => 'FABRICATION DEPARTMENT',
                'address' => 'UNIGEAR-PLANT',
                'contact' => '00000000'
            ],

            [
                'name' => 'ENGINEERING DEPARTMENT',
                'address' => 'UNIGEAR-PLANT',
                'contact' => '000000000'
            ],

            [
                'name' => 'BUSBARRING DEPARTMENT',
                'address' => 'UNIGEAR-PLANT',
                'contact' => '000000'
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}
