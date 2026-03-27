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
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->unique()->constrained()->cascadeOnDelete(); // One receipt per sale
            $table->string('receipt_number')->unique();        // Printable receipt number e.g. "RCP-00001"
            // Full frozen snapshot of the sale at completion time.
            // Stored as JSON so the receipt is always reproducible, even if
            // products, prices, or business details change later.
            $table->json('snapshot');
            $table->string('sent_to')->nullable();             // Email or phone receipt was sent to
            $table->string('delivery_method')->default('none');
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};
