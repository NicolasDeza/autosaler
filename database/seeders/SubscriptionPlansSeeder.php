<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlansSeeder extends Seeder
{
    public function run(): void
    {

        $subscriptionPlans = [
            [
                'key' => 'starter',
                'price' => 9.99,
                'listing_limit' => 10,
                'featured_limit' => 0,
                'duration_days' => 30,
                'image_limit_per_vehicle' => 20,
            ],
            [
                'key' => 'pro',
                'price' => 19.99,
                'listing_limit' => 25,
                'featured_limit' => 0,
                'duration_days' => 30,
                'image_limit_per_vehicle' => 20,
            ],
            [
                'key' => 'business',
                'price' => 39.99,
                'listing_limit' => 50,
                'featured_limit' => 0,
                'duration_days' => 30,
                'image_limit_per_vehicle' => 20,
            ],
            [
                'key' => 'premium',
                'price' => 79.99,
                'listing_limit' => 100,
                'featured_limit' => 0,
                'duration_days' => 30,
                'image_limit_per_vehicle' => 20,
            ],
        ];

        foreach ($subscriptionPlans as $subscriptionPlan) {
            SubscriptionPlan::create($subscriptionPlan);
        }
    }
}
