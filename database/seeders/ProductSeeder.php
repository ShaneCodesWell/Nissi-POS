<?php
namespace Database\Seeders;

use App\Models\Inventory;
use App\Models\Location;
use App\Models\Organization;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Seeding products and inventory...');

        $organization = Organization::where('slug', 'accra-retail-group')->firstOrFail();
        $locations    = Location::where('organization_id', $organization->id)->get();
        $mainBranch   = $locations->firstWhere('code', 'ACR01');
        $temaBranch   = $locations->firstWhere('code', 'TMA01');

        // ─── Categories ───────────────────────────────────────────────────────

        $clothing = $organization->categories()->create([
            'name'       => 'Clothing',
            'slug'       => 'clothing',
            'sort_order' => 1,
            'is_active'  => true,
        ]);

        $electronics = $organization->categories()->create([
            'name'       => 'Electronics',
            'slug'       => 'electronics',
            'sort_order' => 2,
            'is_active'  => true,
        ]);

        $beverages = $organization->categories()->create([
            'name'       => 'Beverages',
            'slug'       => 'beverages',
            'sort_order' => 3,
            'is_active'  => true,
        ]);

        // Sub-categories
        $menswear = $organization->categories()->create([
            'parent_id'  => $clothing->id,
            'name'       => "Men's Wear",
            'slug'       => 'mens-wear',
            'sort_order' => 1,
            'is_active'  => true,
        ]);

        $womenswear = $organization->categories()->create([
            'parent_id'  => $clothing->id,
            'name'       => "Women's Wear",
            'slug'       => 'womens-wear',
            'sort_order' => 2,
            'is_active'  => true,
        ]);

        $this->command->info('  Created 5 categories (2 top-level, 3 sub-categories)');

        // ─── Products ─────────────────────────────────────────────────────────

        // 1. T-Shirt — has size variants
        $tshirt = $organization->products()->create([
            'category_id'  => $menswear->id,
            'name'         => 'Classic Cotton T-Shirt',
            'slug'         => 'classic-cotton-t-shirt',
            'description'  => 'Premium 100% cotton t-shirt. Available in S, M, L, XL.',
            'has_variants' => true,
            'is_active'    => true,
        ]);

        $tshirtVariants = [];
        foreach (['S', 'M', 'L', 'XL'] as $index => $size) {
            $tshirtVariants[$size] = $tshirt->variants()->create([
                'name' => $size,
                'sku'  => "TSH-M-{$size}",
                'price'      => 45.00,
                'cost_price' => 18.00,
                'attributes' => ['size' => $size, 'color' => 'White'],
                'is_active'  => true,
            ]);
        }

        // 2. Women's Dress — has size and color variants
        $dress = $organization->products()->create([
            'category_id'  => $womenswear->id,
            'name'         => 'Summer Wrap Dress',
            'slug'         => 'summer-wrap-dress',
            'description'  => 'Lightweight wrap dress. Available in Blue and Red, sizes S–L.',
            'has_variants' => true,
            'is_active'    => true,
        ]);

        $dressVariants = [];
        foreach (['Blue', 'Red'] as $color) {
            foreach (['S', 'M', 'L'] as $size) {
                $key                 = "{$color}-{$size}";
                $dressVariants[$key] = $dress->variants()->create([
                    'name' => "{$color} / {$size}",
                    'sku' => 'DRS-W-' . strtoupper(substr($color, 0, 3)) . "-{$size}",
                    'price' => 120.00,
                    'cost_price' => 55.00,
                    'attributes' => ['size' => $size, 'color' => $color],
                    'is_active' => true,
                ]);
            }
        }

        // 3. Wireless Earbuds — single variant (no size/color)
        $earbuds = $organization->products()->create([
            'category_id'  => $electronics->id,
            'name'         => 'Wireless Earbuds Pro',
            'slug'         => 'wireless-earbuds-pro',
            'description'  => 'Bluetooth 5.0 earbuds with noise cancellation. 24hr battery life.',
            'has_variants' => false,
            'is_active'    => true,
        ]);

        $earbudsVariant = $earbuds->variants()->create([
            'name'       => null, // No variant name — single default variant
            'sku'        => 'EAR-PRO-BLK',
            'barcode'    => '5901234123457',
            'price'      => 350.00,
            'cost_price' => 180.00,
            'is_active'  => true,
        ]);

        // 4. Phone Charger — single variant
        $charger = $organization->products()->create([
            'category_id'  => $electronics->id,
            'name'         => 'USB-C Fast Charger 65W',
            'slug'         => 'usb-c-fast-charger-65w',
            'description'  => '65W GaN charger with dual USB-C ports.',
            'has_variants' => false,
            'is_active'    => true,
        ]);

        $chargerVariant = $charger->variants()->create([
            'name'       => null,
            'sku'        => 'CHG-USBC-65W',
            'barcode'    => '5901234123458',
            'price'      => 85.00,
            'cost_price' => 32.00,
            'is_active'  => true,
        ]);

        // 5. Bottled Water — single variant, high turnover
        $water = $organization->products()->create([
            'category_id'  => $beverages->id,
            'name'         => 'Voltic Mineral Water 500ml',
            'slug'         => 'voltic-mineral-water-500ml',
            'description'  => 'Natural mineral water, 500ml bottle.',
            'has_variants' => false,
            'is_active'    => true,
        ]);

        $waterVariant = $water->variants()->create([
            'name'       => null,
            'sku'        => 'BEV-WATER-500',
            'barcode'    => '6001000100000',
            'price'      => 3.50,
            'cost_price' => 1.20,
            'is_active'  => true,
        ]);

        // 6. Malt Drink — single variant
        $malt = $organization->products()->create([
            'category_id'  => $beverages->id,
            'name'         => 'Malta Guinness 330ml',
            'slug'         => 'malta-guinness-330ml',
            'description'  => 'Non-alcoholic malt beverage, 330ml can.',
            'has_variants' => false,
            'is_active'    => true,
        ]);

        $maltVariant = $malt->variants()->create([
            'name'       => null,
            'sku'        => 'BEV-MALT-330',
            'barcode'    => '6001000200000',
            'price'      => 5.00,
            'cost_price' => 2.50,
            'is_active'  => true,
        ]);

        $this->command->info('  Created 6 products with ' . ProductVariant::count() . ' variants total');

        // ─── Inventory ────────────────────────────────────────────────────────
        // Seed stock levels for every variant at both locations.
        // Main branch gets more stock — it's the busier location.

        $allVariants = ProductVariant::all();

        foreach ($allVariants as $variant) {
            // Main branch — healthy stock
            Inventory::create([
                'product_variant_id'  => $variant->id,
                'location_id'         => $mainBranch->id,
                'quantity_on_hand'    => $this->stockQuantity($variant->sku, 'main'),
                'low_stock_threshold' => 5,
                'reorder_quantity'    => 20,
            ]);

            // Tema branch — slightly lower stock
            Inventory::create([
                'product_variant_id'  => $variant->id,
                'location_id'         => $temaBranch->id,
                'quantity_on_hand'    => $this->stockQuantity($variant->sku, 'tema'),
                'low_stock_threshold' => 3,
                'reorder_quantity'    => 10,
            ]);
        }

        $this->command->info('  Seeded inventory for ' . $allVariants->count() . ' variants at 2 locations');
        $this->command->line('');
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Return a realistic stock quantity for a variant at a location.
     * High-turnover items (beverages) get more stock.
     * Low-turnover items (electronics) get less.
     */
    protected function stockQuantity(string $sku, string $location): int
    {
        $multiplier = $location === 'main' ? 1 : 0.6;

        $base = match (true) {
            str_starts_with($sku, 'BEV-') => 100, // Beverages — high volume
            str_starts_with($sku, 'TSH-') => 30,  // T-shirts — medium volume
            str_starts_with($sku, 'DRS-') => 15,  // Dresses — medium volume
            str_starts_with($sku, 'EAR-') => 8,   // Electronics — low volume
            str_starts_with($sku, 'CHG-') => 12,  // Chargers — low-medium
            default                       => 20,
        };

        return (int) round($base * $multiplier);
    }
}
