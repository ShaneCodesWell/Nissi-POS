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
        Schema::create('discount_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discount_id')->constrained()->cascadeOnDelete();
            $table->string('code')->unique();                  // The voucher code e.g. "SAVE20"
            $table->unsignedInteger('max_uses')->nullable();   // Null = unlimited uses for this code
            $table->unsignedInteger('used_count')->default(0); // How many times this code has been used
            $table->boolean('is_active')->default(true);
            $table->timestamp('expires_at')->nullable();       // Can differ from parent discount expiry
            $table->timestamps();
            $table->softDeletes();
            $table->index(['code', 'is_active']);              // Fast lookup when cashier enters a code
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount_codes');
    }
};
