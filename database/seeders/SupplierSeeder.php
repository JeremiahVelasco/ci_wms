<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->command->info('Seeding suppliers from CSV...');

        // Path to your CSV file - update this to your actual path
        $csvPath = storage_path('app/seeders/csv/supplier.csv');

        if (!file_exists($csvPath)) {
            $this->command->error("CSV file not found at: $csvPath");
            return;
        }

        // Create a CSV Reader instance
        $csv = Reader::createFromPath($csvPath, 'r');
        $csv->setHeaderOffset(0); // The first row contains headers

        $records = $csv->getRecords();
        $count = 0;

        // Clear the existing table (if needed)
        DB::table('suppliers')->truncate();

        foreach ($records as $record) {
            DB::table('suppliers')->insert([
                'id' => $record['id'],
                'name' => $record['name'],
                'address' => $record['address'],
                'contact' => $record['contact'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $count++;
        }

        $this->command->info("Imported $count suppliers successfully!");
    }
}
