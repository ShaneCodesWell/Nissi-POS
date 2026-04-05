<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Discount;
use App\Models\DiscountCode;
use App\Models\LoyaltyTiers;
use App\Models\Organization;
use Illuminate\Database\Seeder;

class CrmSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Seeding CRM data...');

        $organization = Organization::where('slug', 'accra-retail-group')->firstOrFail();

        // ─── Loyalty tiers ────────────────────────────────────────────────────

        $bronze = LoyaltyTiers::create([
            'organization_id'     => $organization->id,
            'name'                => 'Bronze',
            'description'         => 'Entry level. Earn 1 point per GHS 1 spent.',
            'min_points'          => 0,
            'points_multiplier'   => 1.00,
            'discount_percentage' => 0,
            'color'               => '#CD7F32',
            'sort_order'          => 1,
            'is_active'           => true,
        ]);

        $silver = LoyaltyTiers::create([
            'organization_id'     => $organization->id,
            'name'                => 'Silver',
            'description'         => 'Earn 1.25x points. 5% off every purchase.',
            'min_points'          => 500,
            'points_multiplier'   => 1.25,
            'discount_percentage' => 5.00,
            'color'               => '#C0C0C0',
            'sort_order'          => 2,
            'is_active'           => true,
        ]);

        $gold = LoyaltyTiers::create([
            'organization_id'     => $organization->id,
            'name'                => 'Gold',
            'description'         => 'Earn 1.5x points. 10% off every purchase.',
            'min_points'          => 1500,
            'points_multiplier'   => 1.50,
            'discount_percentage' => 10.00,
            'color'               => '#FFD700',
            'sort_order'          => 3,
            'is_active'           => true,
        ]);

        $platinum = LoyaltyTiers::create([
            'organization_id'     => $organization->id,
            'name'                => 'Platinum',
            'description'         => 'Earn 2x points. 15% off every purchase.',
            'min_points'          => 5000,
            'points_multiplier'   => 2.00,
            'discount_percentage' => 15.00,
            'color'               => '#E5E4E2',
            'sort_order'          => 4,
            'is_active'           => true,
        ]);

        $this->command->info('  Created 4 loyalty tiers: Bronze, Silver, Gold, Platinum');

        // ─── Customers ────────────────────────────────────────────────────────
        // A mix of tiers so you can test tier-specific behaviour at the terminal.

        $customers = [
            [
                'first_name'    => 'Kwabena',
                'last_name'     => 'Frimpong',
                'email'         => 'kwabena@example.com',
                'phone'         => '+233 24 100 0001',
                'date_of_birth' => '1990-03-15',
                'gender'        => 'male',
                'tier'          => $bronze,
                'points'        => 120,
                'total_spend'   => 340.00,
            ],
            [
                'first_name'    => 'Akosua',
                'last_name'     => 'Adjei',
                'email'         => 'akosua@example.com',
                'phone'         => '+233 24 100 0002',
                'date_of_birth' => '1985-07-22',
                'gender'        => 'female',
                'tier'          => $silver,
                'points'        => 780,
                'total_spend'   => 1250.00,
            ],
            [
                'first_name'    => 'Nana',
                'last_name'     => 'Acheampong',
                'email'         => 'nana@example.com',
                'phone'         => '+233 24 100 0003',
                'date_of_birth' => '1978-11-05',
                'gender'        => 'male',
                'tier'          => $gold,
                'points'        => 2100,
                'total_spend'   => 4800.00,
            ],
            [
                'first_name'    => 'Efua',
                'last_name'     => 'Asante',
                'email'         => 'efua@example.com',
                'phone'         => '+233 24 100 0004',
                'date_of_birth' => '1995-01-30',
                'gender'        => 'female',
                'tier'          => $platinum,
                'points'        => 6800,
                'total_spend'   => 12500.00,
            ],
            // Walk-in style customer — no tier yet
            [
                'first_name'    => 'Kojo',
                'last_name'     => 'Baah',
                'email'         => 'kojo@example.com',
                'phone'         => '+233 24 100 0005',
                'date_of_birth' => null,
                'gender'        => 'male',
                'tier'          => $bronze,
                'points'        => 0,
                'total_spend'   => 0,
            ],
        ];

        foreach ($customers as $data) {
            Customer::create([
                'organization_id'    => $organization->id,
                'loyalty_tier_id'    => $data['tier']->id,
                'first_name'         => $data['first_name'],
                'last_name'          => $data['last_name'],
                'email'              => $data['email'],
                'phone'              => $data['phone'],
                'date_of_birth'      => $data['date_of_birth'],
                'gender'             => $data['gender'],
                'points_balance'     => $data['points'],
                'points_earned_total'=> $data['points'],
                'total_spend'        => $data['total_spend'],
                'total_transactions' => $data['total_spend'] > 0
                                            ? (int) ($data['total_spend'] / 150)
                                            : 0,
                'marketing_opt_in'   => true,
                'is_active'          => true,
            ]);
        }

        $this->command->info('  Created 5 customers across all loyalty tiers');

        // ─── Discounts & codes ────────────────────────────────────────────────

        // 1. Welcome discount — 10% off any order for new customers
        $welcome = Discount::create([
            'organization_id'      => $organization->id,
            'name'                 => 'Welcome Discount',
            'description'          => '10% off your first purchase.',
            'type'                 => 'percentage',
            'value'                => 10.00,
            'scope'                => 'order',
            'minimum_order_amount' => 0,
            'is_active'            => true,
        ]);

        DiscountCode::create([
            'discount_id' => $welcome->id,
            'code'        => 'WELCOME10',
            'max_uses'    => null,   // Unlimited uses
            'is_active'   => true,
        ]);

        // 2. Flash sale — GHS 20 off orders over GHS 100
        $flash = Discount::create([
            'organization_id'      => $organization->id,
            'name'                 => 'Flash Sale — GHS 20 Off',
            'description'          => 'GHS 20 off orders over GHS 100.',
            'type'                 => 'fixed',
            'value'                => 20.00,
            'scope'                => 'order',
            'minimum_order_amount' => 100.00,
            'max_uses'             => 50,
            'is_active'            => true,
            'expires_at'           => now()->addDays(7),
        ]);

        DiscountCode::create([
            'discount_id' => $flash->id,
            'code'        => 'FLASH20',
            'max_uses'    => 50,
            'is_active'   => true,
            'expires_at'  => now()->addDays(7),
        ]);

        // 3. Staff discount — 15% off, for internal use
        $staff = Discount::create([
            'organization_id'      => $organization->id,
            'name'                 => 'Staff Discount',
            'description'          => '15% off for staff members.',
            'type'                 => 'percentage',
            'value'                => 15.00,
            'scope'                => 'order',
            'minimum_order_amount' => 0,
            'is_active'            => true,
        ]);

        DiscountCode::create([
            'discount_id' => $staff->id,
            'code'        => 'STAFF15',
            'max_uses'    => null,
            'is_active'   => true,
        ]);

        // 4. Bulk beverage discount — 5% off all items
        $bulk = Discount::create([
            'organization_id'      => $organization->id,
            'name'                 => 'Bulk Beverage Deal',
            'description'          => '5% off every item in the basket.',
            'type'                 => 'percentage',
            'value'                => 5.00,
            'scope'                => 'item',
            'minimum_order_amount' => 50.00,
            'is_active'            => true,
        ]);

        DiscountCode::create([
            'discount_id' => $bulk->id,
            'code'        => 'BULK5',
            'max_uses'    => null,
            'is_active'   => true,
        ]);

        $this->command->info('  Created 4 discounts with codes: WELCOME10, FLASH20, STAFF15, BULK5');
        $this->command->line('');
        $this->command->info('CRM seeding complete.');
        $this->command->line('');
    }
}