<?php

namespace Database\Seeders;

use App\Data\VehicleFeatures;
use App\Models\FeaturesCatalog;
use Illuminate\Database\Seeder;

class FeaturesCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $sortOrder = 0;
        $added = 0;
        $updated = 0;

        foreach (VehicleFeatures::all() as $category => $features) {
            foreach ($features as $name) {
                $existing = FeaturesCatalog::where('name', $name)->first();

                if ($existing) {
                    $existing->update([
                        'category'   => $category,
                        'sort_order' => $sortOrder++,
                    ]);
                    $updated++;
                } else {
                    FeaturesCatalog::create([
                        'name'       => $name,
                        'category'   => $category,
                        'is_active'  => true,
                        'sort_order' => $sortOrder++,
                    ]);
                    $added++;
                }
            }
        }

        $this->command->info("features_catalog: {$added} yeni eklendi, {$updated} güncellendi.");
    }
}
