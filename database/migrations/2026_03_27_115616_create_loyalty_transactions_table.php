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
        Schema::create('loyalty_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sale_id')->nullable()->constrained()->nullOnDelete(); // Null = manual adjustment by staff
            $table->string('type')->nullable();
            $table->integer('points');                 // Positive = earn, negative = redeem/expire
            $table->unsignedInteger('balance_after');  // Points balance after this transaction
            $table->string('description')->nullable(); // Human-readable note e.g. "Purchase #ORD-00042"
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete(); // Staff member who made manual adjustment, if any
            $table->timestamps();
            $table->index(['customer_id', 'created_at']); // Fast per-customer history queries
            $table->index(['organization_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loyalty_transactions');
    }
};
