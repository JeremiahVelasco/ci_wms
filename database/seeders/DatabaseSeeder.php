<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(CustomerSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(SupplierSeeder::class);
        $this->call(TransactionSeeder::class);

        User::factory()->create([
            'name' => 'Cesar Jr. Villarta',
            'email' => 'admin@unigear.com',
            'password' => Hash::make('admin123')
        ]);

        User::factory()->create([
            'name' => 'Staff 101',
            'email' => 'staff1@unigear.com',
        ]);

        User::factory()->create([
            'name' => 'Staff 102',
            'email' => 'staff2@unigear.com',
        ]);
    }
}
