<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('amount')->nullable();
            $table->integer('status');
            $table->string('job_order')->nullable();
            $table->foreignId('product_id');
            $table->foreignId('supplier_id')->nullable();
            $table->foreignId('customer_id')->nullable();
            $table->string('actor');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
