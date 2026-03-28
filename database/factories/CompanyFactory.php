<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Company;
use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'email' => fake()->unique()->companyEmail(),
            'tva_number' => 'BE0'.fake()->numerify('#########'),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'country_id' => Country::first()?->id ?? Country::factory(),
            'city_id' => City::first()?->id ?? City::factory(),
        ];
    }
}
