<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\InspectionData;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InitialControlSheet>
 */
class InitialControlSheetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $model = InspectionData::inRandomOrder()->value('model');

        $useFraction = $this->faker->boolean(60);
        $base = $useFraction
            ? $this->faker->numberBetween(10, 999) . '/' . $this->faker->numberBetween(10, 999)
            : $this->faker->numberBetween(10, 9999);

        $lot = $base . '-' . $this->faker->numberBetween(1, 9);

        return [
            'model_name' => $model,
            'lot_no' => $lot,
            'layer_data' => $this->generateLayerData($model, $lot),
            'excess_data' => null, // or similar generator
            'total_boxes' => 10,
        ];
    }

    private function generateLayerData($model, $lot)
    {
        $allBoxes = ['A','B','C','D','E','F','G','H','J','K'];

        // 🔥 Step 1: pick random subset size
        $count = rand(1, count($allBoxes));

        // 🔥 Step 2: randomly select that many boxes
        $selectedBoxes = collect($allBoxes)
            ->shuffle()
            ->take($count)
            ->values()
            ->all();

        // 🔥 Step 3: generator uses ONLY selected boxes
        $generateRow = function ($valueGenerator) use ($selectedBoxes) {
            $row = [];
            foreach ($selectedBoxes as $box) {
                $row[$box] = $valueGenerator();
            }
            return $row;
        };

        return [
            [
                'rowTitle' => 'MODEL:',
                'data' => $generateRow(fn() => $model),
            ],
            [
                'rowTitle' => 'LT. No.:',
                'data' => $generateRow(fn() => $lot),
            ],
            [
                'rowTitle' => 'COATING M/C No.:',
                'data' => $generateRow(fn() => 'C-' . rand(10, 99)),
            ],
            [
                'rowTitle' => 'QTY (PCS):',
                'data' => $generateRow(fn() => rand(1000, 2000)),
            ],
            [
                'rowTitle' => 'WT (KG):',
                'data' => $generateRow(fn() => rand(1400, 1500) / 100),
            ],
            [
                'rowTitle' => 'BOX No.:',
                'data' => $generateRow(fn() => str_pad(rand(1, 9999), 5, '0', STR_PAD_LEFT)),
            ],
            [
                'rowTitle' => 'RAW MATERIAL CODE:',
                'data' => $generateRow(fn() => strtoupper(\Str::random(7))),
            ],
        ];
    }


}
