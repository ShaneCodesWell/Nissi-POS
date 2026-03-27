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
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_variant_id')->constrained()->restrictOnDelete();          // Prevent deleting a variant that has sales history
            // Snapshot columns — copied from product_variant at time of sale.
            // Critical: prices and names can change later; we need to preserve what was charged.
            $table->string('product_name');                    // Snapshot of product name
            $table->string('variant_name')->nullable();        // Snapshot of variant name e.g. "Red / L"
            $table->string('sku');                             // Snapshot of SKU at time of sale
            $table->unsignedInteger('quantity');
            $table->decimal('unit_price', 12, 2);             // Price per unit at time of sale
            $table->decimal('cost_price', 12, 2)->default(0); // Cost per unit — for margin reports
            $table->decimal('discount_amount', 12, 2)->default(0); // Line-level discount
            $table->decimal('tax_amount', 12, 2)->default(0); // Line-level tax
            $table->decimal('line_total', 12, 2);             // (unit_price * quantity) - discount + tax
            // Optional: line-level discount rule reference
            // $table->foreignId('discount_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
            $table->index('sale_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_items');
    }
};
