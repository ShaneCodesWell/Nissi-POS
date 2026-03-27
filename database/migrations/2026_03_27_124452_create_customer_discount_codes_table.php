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
        Schema::create('customer_discount_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('discount_code_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sale_id')->nullable()->constrained()->nullOnDelete();              // Populated when the code is redeemed
            $table->string('status')->default('assigned');
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamp('redeemed_at')->nullable();
            $table->timestamp('expires_at')->nullable();       // Per-customer expiry override
            $table->timestamps();
            // A customer can only hold a given code once
            $table->unique(['customer_id', 'discount_code_id']);
            $table->index(['customer_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_discount_codes');
    }
};
