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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('location_id')->constrained()->cascadeOnDelete();
            $table->foreignId('terminal_id')->nullable()->constrained()->nullOnDelete();              // Keep sale record even if terminal is deleted
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();              // Cashier who processed the sale
            // $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();              // Null = walk-in / anonymous customer
            $table->string('sale_number')->unique();           // Human-readable e.g. "ORD-00001"
            $table->string('status')->default('pending');
            // Money columns — all in org's currency, stored as exact decimals
            $table->decimal('subtotal', 12, 2)->default(0);        // Sum of line totals before discounts/tax
            $table->decimal('discount_amount', 12, 2)->default(0); // Total order-level discount
            $table->decimal('tax_amount', 12, 2)->default(0);      // Tax applied to the order
            $table->decimal('total_amount', 12, 2)->default(0);    // Final amount due
            // Discount tracking
            // $table->foreignId('discount_id')->nullable()->constrained()->nullOnDelete();              // Order-level discount rule applied
            $table->string('discount_code')->nullable();       // Voucher code string used, if any
            $table->text('notes')->nullable();                 // Cashier notes
            $table->timestamp('completed_at')->nullable();     // When payment was finalised
            $table->timestamps();
            $table->softDeletes();
            // Indexes for reporting queries
            $table->index(['location_id', 'status']);
            $table->index(['organization_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
