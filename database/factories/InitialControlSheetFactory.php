<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\InspectionData;

class InitialControlSheetFactory extends Factory
{
    /*
    |--------------------------------------------------------------------------
    | 1. SCHEMA (single source of truth inside factory)
    |--------------------------------------------------------------------------
    */

    private array $boxes = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K'];

    private function baseRows(string $model, string $lot, $faker): array
    {
        return [
            'MODEL:' => fn() => $model,
            'COATING M/C No.:' => fn() => 'C-' . rand(10, 99),
            'LT. No.:' => fn() => $lot,
            'QTY (PCS):' => fn() => rand(100, 2000),
            'COATING:' => fn() => rand(1, 4),
            'WT (KG):' => fn() => rand(1000, 2000) / 100,
            'BOX No.:' => fn() => strtoupper(Str::random(8)),
            'Magnet prepared by:' => fn() => $faker->firstName(),
            'Box prepared by:' => fn() => $faker->firstName(),
            'RAW MATERIAL CODE:' => fn() => strtoupper('OG0V' . rand(100, 999)),
            'TOTAL QTY' => fn() => rand(1000, 2000),
            'BOX WEIGHT' => fn() => rand(300, 600) / 100,
            'BOX WITH MAGNET WEIGHT' => fn() => rand(1500, 2000) / 100,
        ];
    }

    private function excessRows(string $model, string $lot, $faker): array
    {
        return [
            'MODEL:' => fn() => $model,
            'COATING M/C No.:' => fn() => 'C-' . rand(10, 99),
            'LT. No.:' => fn() => $lot,
            'QTY (PCS):' => fn() => rand(50, 1200),
            'COATING:' => fn() => rand(1, 4),
            'WT (KG):' => fn() => rand(800, 1800) / 100,
            'BOX No.:' => fn() => strtoupper(Str::random(8)),
            'Magnet prepared by:' => fn() => $faker->firstName(),
            'Box prepared by:' => fn() => $faker->firstName(),
            'RAW MATERIAL CODE:' => fn() => strtoupper('EX' . rand(100, 999)),
            'TOTAL QTY' => fn() => rand(500, 1500),
            'BOX WEIGHT' => fn() => rand(200, 500) / 100,
            'BOX WITH MAGNET WEIGHT' => fn() => rand(1000, 1700) / 100,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | 2. HELPERS (pure logic, no model dependency)
    |--------------------------------------------------------------------------
    */

    private function pickBoxes(): array
    {
        $start = rand(0, count($this->boxes) - 2);
        $length = rand(2, count($this->boxes) - $start);

        return array_slice($this->boxes, $start, $length);
    }

    private function buildTable(array $rows, array $boxes, array $ctx): array
    {
        $output = [];

        foreach ($rows as $title => $generator) {
            $data = [];

            foreach ($boxes as $box) {
                $data[$box] = $generator($ctx);
            }

            $output[] = [
                'rowTitle' => $title,
                'data' => $data,
            ];
        }

        return $output;
    }

    private function calculateTotalBoxes(array $layer, ?array $excess): int
    {
        $countDataset = function ($dataset) {
            if (!$dataset) return 0;

            return collect($dataset)
                ->flatMap(fn($row) => array_keys($row['data']))
                ->unique() // only C, D, E per dataset
                ->count();
        };

        return $countDataset($layer)
            + $countDataset($excess);
    }

    /*
    |--------------------------------------------------------------------------
    | 3. MAIN FACTORY ENTRY
    |--------------------------------------------------------------------------
    */

    public function definition(): array
    {
        $model = InspectionData::inRandomOrder()->value('model');
        $lot = rand(10, 9999) . '-' . rand(1, 9);
        $faker = $this->faker;

        $layerBoxes = $this->pickBoxes();
        $excessBoxes = $faker->boolean(40)
            ? $this->pickBoxes()
            : null;

        $ctx = [
            'model' => $model,
            'lot' => $lot,
            'faker' => $faker,
        ];

        $layer = $this->buildTable(
            $this->baseRows($model, $lot, $faker),
            $layerBoxes,
            $ctx
        );

        $excess = null;

        if ($excessBoxes) {
            $excess = $this->buildTable(
                $this->excessRows($model, $lot, $faker),
                $excessBoxes,
                $ctx
            );
        }

        return [
            'model_name' => $model,
            'lot_no' => $lot,
            'layer_data' => $layer,
            'excess_data' => $excess,
            'total_boxes' => $this->calculateTotalBoxes($layer, $excess),
        ];
    }
}
