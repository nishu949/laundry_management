<?php

namespace Database\Seeders;

use App\Models\RateCard;
use Illuminate\Database\Seeder;

class RateCardSeeder extends Seeder
{
    public function run(): void
    {
        RateCard::updateOrCreate(
            ['service_type' => 'wash_fold'],
            [
                'name' => 'Wash & Fold',
                'base_rate' => 2.50,
                'tiers' => [
                    ['min_quantity' => 1,  'rate' => 2.50],
                    ['min_quantity' => 10, 'rate' => 2.00],
                    ['min_quantity' => 25, 'rate' => 1.50],
                ],
            ]
        );

        RateCard::updateOrCreate(
            ['service_type' => 'dry_clean'],
            [
                'name' => 'Dry Clean',
                'base_rate' => 8.00,
                'tiers' => [
                    ['min_quantity' => 1, 'rate' => 8.00],
                    ['min_quantity' => 5, 'rate' => 7.00],
                ],
            ]
        );

        RateCard::updateOrCreate(
            ['service_type' => 'iron_only'],
            [
                'name' => 'Iron Only',
                'base_rate' => 1.50,
                'tiers' => [
                    ['min_quantity' => 1,  'rate' => 1.50],
                    ['min_quantity' => 20, 'rate' => 1.00],
                ],
            ]
        );
    }
}