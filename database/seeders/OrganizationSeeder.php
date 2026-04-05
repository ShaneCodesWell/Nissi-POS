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

        // ─── Organisation ─────────────────────────────────────────────────────

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

        $this->command->info("  Created organisation: {$organization->name}");

        // ─── Locations ────────────────────────────────────────────────────────

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

        $this->command->info("  Created locations: {$mainBranch->name}, {$temaBranch->name}");

        // ─── Terminals ────────────────────────────────────────────────────────

        $terminal1 = Terminal::create([
            'location_id' => $mainBranch->id,
            'name'        => 'Terminal 1',
            'identifier'  => 'ACR01-T1',
            'is_active'   => true,
        ]);

        $terminal2 = Terminal::create([
            'location_id' => $mainBranch->id,
            'name'        => 'Terminal 2',
            'identifier'  => 'ACR01-T2',
            'is_active'   => true,
        ]);

        $terminal3 = Terminal::create([
            'location_id' => $temaBranch->id,
            'name'        => 'Terminal 1',
            'identifier'  => 'TMA01-T1',
            'is_active'   => true,
        ]);

        $this->command->info('  Created 3 terminals across 2 locations');

        // ─── Users ────────────────────────────────────────────────────────────
        // Creates a set of realistic staff accounts for testing.
        // All passwords are 'password' — change before going live.

        $admin = User::firstOrCreate(
            ['email' => 'admin@accaretail.com'],
            [
                'name'     => 'Kwame Asante',
                'password' => Hash::make('password'),
            ]
        );

        $manager = User::create([
            'name'     => 'Ama Owusu',
            'email'    => 'ama@accaretail.com',
            'password' => Hash::make('password'),
        ]);

        $cashier1 = User::create([
            'name'     => 'Kofi Mensah',
            'email'    => 'kofi@accaretail.com',
            'password' => Hash::make('password'),
        ]);

        $cashier2 = User::create([
            'name'     => 'Abena Boateng',
            'email'    => 'abena@accaretail.com',
            'password' => Hash::make('password'),
        ]);

        $cashier3 = User::create([
            'name'     => 'Yaw Darko',
            'email'    => 'yaw@accaretail.com',
            'password' => Hash::make('password'),
        ]);

        $this->command->info('  Created 5 staff users');

        // ─── Assign users to locations via pivot ──────────────────────────────

        // Admin and manager oversee both branches
        $mainBranch->users()->attach($admin->id, ['role' => 'manager', 'is_active' => true]);
        $mainBranch->users()->attach($manager->id, ['role' => 'manager', 'is_active' => true]);
        $mainBranch->users()->attach($cashier1->id, ['role' => 'cashier', 'is_active' => true]);
        $mainBranch->users()->attach($cashier2->id, ['role' => 'cashier', 'is_active' => true]);

        $temaBranch->users()->attach($admin->id, ['role' => 'manager', 'is_active' => true]);
        $temaBranch->users()->attach($cashier3->id, ['role' => 'cashier', 'is_active' => true]);

        $this->command->info('  Assigned staff to locations');
        $this->command->info('');
        $this->command->info('  Login credentials (all passwords: "password"):');
        $this->command->info("  Admin:    admin@accaretail.com");
        $this->command->info("  Manager:  ama@accaretail.com");
        $this->command->info("  Cashier:  kofi@accaretail.com");
        $this->command->line('');
    }
}
