<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add is_admin flag to users
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false)->after('email');
        });

        // Drop the unused admins table — users handles both roles
        Schema::dropIfExists('admins');
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_admin');
        });

        // Recreate admins table on rollback
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }
};