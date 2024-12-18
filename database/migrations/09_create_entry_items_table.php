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
        Schema::create('entry_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('entry_invoices')->onDelete('cascade');
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->string('unit');
            $table->integer('unit_price');
            $table->integer('unit_quantity');
            $table->integer('total_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entry_items');
    }
};
