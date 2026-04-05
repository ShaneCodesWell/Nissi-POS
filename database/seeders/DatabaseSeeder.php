<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Order matters here — foreign key dependencies must be
     * satisfied before dependent records are created.
     *
     * Run with: php artisan db:seed
     * Fresh install: php artisan migrate:fresh --seed
     */
    public function run(): void
    {
        $this->call([
            // 1. Organisation structure first — everything else hangs off this
            OrganizationSeeder::class,
 
            // 2. Products, categories and inventory
            ProductSeeder::class,
 
            // 3. CRM — loyalty tiers and customers
            // (tiers must exist before customers reference them)
            CrmSeeder::class,
        ]);
    }
}
