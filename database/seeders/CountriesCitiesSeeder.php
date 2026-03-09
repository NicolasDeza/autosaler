<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountriesCitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $belgium = Country::create([
            'code' => 'Belgique',
            'iso2' => 'BE',
            'iso3' => 'BEL',
            'phone_code' => '+32',
        ]);

        $cities = json_decode(file_get_contents(database_path('seeders/belgian_municipalities.json')), true);

        DB::beginTransaction();
        foreach ($cities as $city) {
            City::create([
                'country_id' => $belgium->id,
                'code' => $city['municipality_FR'],
                'zip_code' => $city['zip'],
            ]);
        }
        DB::commit();
        $this->command->info('Cities seeded successfully.');
    }
}
