<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->command->info('Seeding transactions from CSV...');

        // Path to your CSV file - update this to your actual path
        $csvPath = storage_path('app/seeders/csv/transaction.csv');

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
        DB::table('transactions')->truncate();

        foreach ($records as $record) {
            DB::table('transactions')->insert([
                'id' => $record['id'],
                'amount' => $record['amount'],
                'status' => $record['status'],
                'job_order' => $record['job_order'],
                'product_id' => $record['product_id'],
                'supplier_id' => $record['supplier_id'],
                'customer_id' => $record['customer_id'],
                'actor' => $record['actor'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $count++;
        }

        $this->command->info("Imported $count transactions successfully!");
    }
}
