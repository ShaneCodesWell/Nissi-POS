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
        Schema::create('loyalty_tiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('name');                             // e.g. "Silver", "Gold", "Platinum"
            $table->string('description')->nullable();
            $table->unsignedInteger('min_points')->default(0); // Points needed to reach this tier
            $table->decimal('points_multiplier', 4, 2)->default(1.00);                             // e.g. 1.5 = earn 1.5x points per purchase
            $table->decimal('discount_percentage', 5, 2)->default(0);                              // Automatic tier discount on every purchase
            $table->string('color')->nullable();               // Hex color for UI badge e.g. "#C0C0C0"
            $table->unsignedInteger('sort_order')->default(0); // 0 = lowest tier
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['organization_id', 'name']);
            $table->index(['organization_id', 'min_points']); // Fast tier lookup by points balance
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loyalty_tiers');
    }
};
