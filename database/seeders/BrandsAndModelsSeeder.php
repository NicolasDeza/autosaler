<?php

namespace Database\Seeders;

use App\Models\VehicleBrand;
use App\Models\VehicleModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandsAndModelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = database_path('seeders/brands_models_final.json');

        if (! file_exists($jsonPath)) {
            $this->command->error("JSON file not found at: {$jsonPath}");

            return;
        }

        $jsonData = file_get_contents($jsonPath);
        $data = json_decode($jsonData, true);

        if (! $data) {
            $this->command->error('Failed to decode JSON data.');

            return;
        }

        // Group models by brand
        $brandsObj = [];
        foreach ($data as $item) {
            $brandName = trim($item['brand']);
            $modelName = trim($item['model']);

            if (! isset($brandsObj[$brandName])) {
                $brandsObj[$brandName] = [];
            }
            // Use array key to automatically remove duplicates
            $brandsObj[$brandName][$modelName] = true;
        }

        $this->command->info(sprintf('Found %d unique brands. Starting seeding...', count($brandsObj)));

        // Optimize performance using transactions
        DB::beginTransaction();

        try {
            foreach ($brandsObj as $brandName => $modelsMap) {
                // Get or create brand
                $brand = VehicleBrand::firstOrCreate(['name' => $brandName]);

                $modelsToInsert = [];
                // Retrieve all lower-case model names for this brand to prevent Unique Constraint Violaions
                $existingModels = VehicleModel::where('brand_id', $brand->id)
                    ->pluck('name')
                    ->map(fn ($name) => strtolower($name))
                    ->toArray();

                foreach (array_keys($modelsMap) as $modelName) {
                    $lowerModelName = strtolower($modelName);
                    if (! in_array($lowerModelName, $existingModels)) {
                        $modelsToInsert[] = [
                            'brand_id' => $brand->id,
                            'name' => $modelName,
                        ];
                        // Add to existingModels so case variants like "QUATTRO" and "quattro" don't get appended both
                        $existingModels[] = $lowerModelName;
                    }
                }

                // Chunk inserts to avoid query size limits (PDO placeholders limit)
                foreach (array_chunk($modelsToInsert, 500) as $chunk) {
                    VehicleModel::insertOrIgnore($chunk);
                }
            }

            DB::commit();
            $this->command->info('Successfully seeded Brands and Models!');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Failed to seed data: '.$e->getMessage());
        }
    }
}
