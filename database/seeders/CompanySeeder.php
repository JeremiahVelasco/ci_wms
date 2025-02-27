<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::create([
            'website' => 'Unigear Phils Corp',
            'description' => 'Unigear Philippines Corp  Inventory Management System',
            'keyword' => 'Unigear Philippines Corp  Inventory Management System',
            'address' => 'Lily st. Maligaya Park Subdivision Brgy Pasong Putik, Quezon City, Philippines',
            'contact' => '8994-4728 / 0942-377-1695',
            'facebook' => 'https://www.facebook.com/unigear',
            'email' => 'info@unigearphilscorp.com',
            'twitter' => 'https://twitter.com/Unigear',
            'youtube' => 'https://www.youtube.com/unigear',
            'owner' => 'CI PHP - Project',
        ]);
    }
}
