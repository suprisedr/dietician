<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DietPreset;
use App\Models\DietPresetItem;

class DietPresetsSeeder extends Seeder
{
    public function run(): void
    {
        $presets = config('diet_presets');

        foreach ($presets as $key => $data) {
            $preset = DietPreset::updateOrCreate(
                ['key' => $key],
                [
                    'name'        => $data['name'],
                    'description' => $data['description'],
                    'kcal_target' => $data['kcal_target'],
                ]
            );

            // Replace all items for this preset fresh
            $preset->items()->delete();

            foreach ($data['items'] as $item) {
                DietPresetItem::create(array_merge($item, ['diet_preset_id' => $preset->id]));
            }
        }
    }
}
