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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained()->cascadeOnDelete();
            $table->string('method')->default('cash');
            $table->string('status')->default('pending');
            $table->decimal('amount', 12, 2);                 // Amount paid via this method
            $table->decimal('change_given', 12, 2)->default(0); // Cash change returned to customer
            // External reference — MoMo transaction ID, card auth code, etc.
            $table->string('reference')->nullable();
            $table->string('provider')->nullable();            // e.g. "MTN", "Vodafone", "Visa"
            $table->string('account_number')->nullable();      // Last 4 digits of card / MoMo number
            $table->timestamp('paid_at')->nullable();          // When payment was confirmed
            $table->timestamps();
            $table->index(['sale_id', 'method']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
