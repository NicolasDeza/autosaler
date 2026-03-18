<?php

use App\Models\SubscriptionPlan;
use App\Models\User;
use Database\Seeders\CountriesCitiesSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Database\Seeders\SubscriptionPlansSeeder;
use Database\Seeders\UserSeeder;

it('creates the autosalerpro admin account', function () {
    $this->seed(RolesAndPermissionsSeeder::class);
    $this->seed(CountriesCitiesSeeder::class);
    $this->seed(UserSeeder::class);

    $admin = User::query()->where('email', 'autosalerpro@gmail.com')->first();

    expect($admin)->not->toBeNull();
    expect($admin?->first_name)->toBe('Alex');
    expect($admin?->last_name)->toBe('Martin');
    expect($admin?->hasRole('admin'))->toBeTrue();
});

it('creates subscription plans from seeder', function () {
    $this->seed(SubscriptionPlansSeeder::class);

    expect(SubscriptionPlan::query()->count())->toBe(4);
    expect(SubscriptionPlan::query()->where('key', 'starter')->exists())->toBeTrue();
});
