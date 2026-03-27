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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('loyalty_tier_id')->nullable()->constrained()->nullOnDelete();              // Null = no tier assigned yet
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->date('date_of_birth')->nullable();         // For birthday rewards
            $table->enum('gender', ['male', 'female', 'other', 'prefer_not_to_say'])->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            // Loyalty summary columns — derived from loyalty_transactions ledger.
            // Always update these via an observer or service, never directly.
            $table->unsignedInteger('points_balance')->default(0);
            $table->unsignedInteger('points_earned_total')->default(0);  // Lifetime earned
            $table->unsignedInteger('points_redeemed_total')->default(0);// Lifetime redeemed
            // Spend summary — updated on each completed sale
            $table->decimal('total_spend', 14, 2)->default(0);
            $table->unsignedInteger('total_transactions')->default(0);
            $table->timestamp('last_purchase_at')->nullable();
            $table->string('notes')->nullable();               // Internal CRM notes
            $table->boolean('marketing_opt_in')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['organization_id', 'email']);
            $table->unique(['organization_id', 'phone']);
            $table->index(['organization_id', 'loyalty_tier_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
