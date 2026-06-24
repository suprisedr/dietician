<?php

namespace Database\Seeders;

use App\Models\PricingPackage;
use Illuminate\Database\Seeder;

class PricingPackageSeeder extends Seeder
{
    /**
     * Seed the pricing_packages table with the four MindfulNutrico plans.
     */
    public function run(): void
    {
        $packages = [
            [
                'slug'            => 'free',
                'name'            => 'Free',
                'badge_label'     => 'Free',
                'badge_style'     => 'free',
                'price_zar'       => 0,
                'tagline'         => 'Essential calculators to get started',
                'is_featured'     => false,
                'is_active'       => true,
                'max_users'       => 1,
                'group_price_zar' => null,
                'sort_order'      => 1,
                'features'        => [
                    'BMI calculator',
                    'ABW · IBW · AF calculations',
                    'RMR · BMR estimations',
                    '1 device / 1 user',
                ],
            ],
            [
                'slug'               => 'package_1',
                'name'               => 'Package 1',
                'badge_label'        => 'Most Popular',
                'badge_style'        => 'popular',
                'price_zar'          => 499,
                'paystack_plan_code' => 'PLN_tx73xve6evpo9es',
                'paystack_amount'    => 49900, // kobo (499 ZAR × 100)
                'tagline'            => 'Full clinical nutrition toolkit',
                'is_featured'        => true,
                'is_active'          => true,
                'max_users'          => 1,
                'group_price_zar'    => null,
                'sort_order'         => 2,
                'features'           => [
                    'Everything in Free',
                    'Macro distribution (C · P · F) & fibre/fluid targets',
                    'Food exchange list items',
                    'Meal plan template (downloadable)',
                    'Grocery list generator',
                    '1 device / 1 user',
                ],
            ],
            [
                'slug'            => 'package_2',
                'name'               => 'Package 2',
                'badge_label'        => 'Package 2',
                'badge_style'        => 'pro',
                'price_zar'          => 699,
                'paystack_plan_code' => 'PLN_gandzkdwrnu6z4a',
                'paystack_amount'    => 69900, // kobo (699 ZAR × 100)
                'tagline'            => 'For dieticians with assistants',
                'is_featured'     => false,
                'is_active'       => true,
                'max_users'       => 2,
                'group_price_zar' => null,
                'sort_order'      => 3,
                'features'        => [
                    'Everything in Package 1',
                    'Recipe library',
                    'Patient food diary',
                    'Weekly email reminders',
                    '2 devices / 2 users',
                ],
            ],
            [
                'slug'            => 'package_3',
                'name'               => 'Package 3',
                'badge_label'        => 'Team',
                'badge_style'        => 'team',
                'price_zar'          => 899,
                'paystack_plan_code' => 'PLN_6vrqtsc3v1mbqnl',
                'paystack_amount'    => 89900, // kobo (899 ZAR × 100)
                'tagline'            => 'Multi-dietician practice or clinic',
                'is_featured'     => false,
                'is_active'       => true,
                'max_users'       => 5,
                'group_price_zar' => 1800,
                'sort_order'      => 4,
                'features'        => [
                    'Everything in Package 2',
                    'Enteral feed calculations & selection',
                    'Daily monitoring (rate / volume)',
                    'Group package — up to 5 users',
                    '5-user group: R 1 800 / month',
                ],
            ],
        ];

        foreach ($packages as $data) {
            PricingPackage::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }

        $this->command->info('Pricing packages seeded successfully (' . count($packages) . ' records).');
    }
}
