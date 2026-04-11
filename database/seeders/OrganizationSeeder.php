<?php
namespace Database\Seeders;

use App\Models\Location;
use App\Models\Organization;
use App\Models\Terminal;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Seeding organisation structure...');

        $organization = Organization::create([
            'name'      => 'Accra Retail Group',
            'slug'      => 'accra-retail-group',
            'email'     => 'info@accaretail.com',
            'phone'     => '+233 30 000 0000',
            'address'   => '14 Independence Avenue',
            'city'      => 'Accra',
            'country'   => 'Ghana',
            'currency'  => 'GHS',
            'timezone'  => 'Africa/Accra',
            'is_active' => true,
        ]);

        $mainBranch = Location::create([
            'organization_id' => $organization->id,
            'name'            => 'Accra Main Branch',
            'code'            => 'ACR01',
            'address'         => '14 Independence Avenue',
            'city'            => 'Accra',
            'phone'           => '+233 30 000 0001',
            'email'           => 'accra@accaretail.com',
            'is_active'       => true,
        ]);

        $temaBranch = Location::create([
            'organization_id' => $organization->id,
            'name'            => 'Tema Branch',
            'code'            => 'TMA01',
            'address'         => 'Community 1, Tema',
            'city'            => 'Tema',
            'phone'           => '+233 30 000 0002',
            'email'           => 'tema@accaretail.com',
            'is_active'       => true,
        ]);

        Terminal::create(['location_id' => $mainBranch->id, 'name' => 'Terminal 1', 'identifier' => 'ACR01-T1', 'is_active' => true]);
        Terminal::create(['location_id' => $mainBranch->id, 'name' => 'Terminal 2', 'identifier' => 'ACR01-T2', 'is_active' => true]);
        Terminal::create(['location_id' => $temaBranch->id, 'name' => 'Terminal 1', 'identifier' => 'TMA01-T1', 'is_active' => true]);

        // Admin — is_admin true, no location pivot
        $admin = User::firstOrCreate(
            ['email' => 'admin@accaretail.com'],
            ['name' => 'Kwame Asante', 'password' => Hash::make('password'), 'is_admin' => true]
        );

        $manager  = User::create(['name' => 'Ama Owusu', 'email' => 'ama@accaretail.com', 'password' => Hash::make('password'), 'is_admin' => false]);
        $cashier1 = User::create(['name' => 'Kofi Mensah', 'email' => 'kofi@accaretail.com', 'password' => Hash::make('password'), 'is_admin' => false]);
        $cashier2 = User::create(['name' => 'Abena Boateng', 'email' => 'abena@accaretail.com', 'password' => Hash::make('password'), 'is_admin' => false]);
        $cashier3 = User::create(['name' => 'Yaw Darko', 'email' => 'yaw@accaretail.com', 'password' => Hash::make('password'), 'is_admin' => false]);

        $mainBranch->users()->attach($manager->id, ['role' => 'manager', 'is_active' => true]);
        $mainBranch->users()->attach($cashier1->id, ['role' => 'cashier', 'is_active' => true]);
        $mainBranch->users()->attach($cashier2->id, ['role' => 'cashier', 'is_active' => true]);
        $temaBranch->users()->attach($manager->id, ['role' => 'manager', 'is_active' => true]);
        $temaBranch->users()->attach($cashier3->id, ['role' => 'cashier', 'is_active' => true]);

        $this->command->line('');
        $this->command->info('  Credentials (all passwords: "password"):');
        $this->command->info('  Admin:   admin@accaretail.com  → /admin');
        $this->command->info('  Cashier: kofi@accaretail.com   → terminal select');
        $this->command->line('');
    }
}
