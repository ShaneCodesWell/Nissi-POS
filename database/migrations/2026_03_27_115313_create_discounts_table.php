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
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('name');                            // e.g. "Black Friday 20% Off"
            $table->string('description')->nullable();
            $table->string('type')->nullable();
            $table->decimal('value', 12, 2);                  // The discount amount or percentage
            $table->string('scope')->default('order');
            $table->decimal('minimum_order_amount', 12, 2)->default(0);                                // Minimum basket value to qualify
            $table->unsignedInteger('max_uses')->nullable();   // Null = unlimited global uses
            $table->unsignedInteger('used_count')->default(0); // Running total of uses
            $table->boolean('is_active')->default(true);
            $table->timestamp('starts_at')->nullable();        // Null = active immediately
            $table->timestamp('expires_at')->nullable();       // Null = never expires
            $table->timestamps();
            $table->softDeletes();
            $table->index(['organization_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
