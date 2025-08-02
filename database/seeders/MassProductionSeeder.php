<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MassProduction;

class MassProductionSeeder extends Seeder
{
    public function run(): void
    {
        $rowTitles = [
            'MODEL:',
            'COATING M/C No.:',
            'LT. No.:',
            'QTY (PCS):',
            'HT (PCS):',
            'LT (PCS):',
            'COATING:',
            'WT (KG)',
            'BOX No.:',
            'Magnet prepared by:',
            'Box prepared by:',
        ];

        $columns = ['A','B','C','D','E','F','G','H','J','K','L'];

        $jsonMatrixTemplate = function () use ($rowTitles, $columns) {
            return collect($rowTitles)->map(function ($rowTitle) use ($columns) {
                $data = collect($columns)->mapWithKeys(function ($col) {
                    return [$col => fake()->word()];
                });
                return [
                    'rowTitle' => $rowTitle,
                    'data' => $data,
                ];
            });
        };

        for ($i = 1; $i <= 10; $i++) {
            MassProduction::create([
                'mass_prod' => "MP-2025-" . str_pad($i, 3, '0', STR_PAD_LEFT),
                'furnace' => 'Furnace-0' . rand(1, 5),
                'batch_cycle_no' => 'BCN-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'machine_no' => 'MC-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'cycle_no' => 'CN-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'pattern_no' => rand(1, 10),
                'cycle_pattern' => 'P' . rand(1, 5),
                'current_pattern' => 'CP' . rand(1, 5),
                'date_start' => now()->subDays(rand(1, 5)),
                'time_start' => now()->subDays(1)->format('H:i:s'),
                'date_finished' => now(),
                'time_finished' => now()->format('H:i:s'),
                'loader' => fake()->name(),
                'unloader' => fake()->name(),
                'box_condition' => fake()->randomElement(['Good', 'Fair', 'Poor']),
                'box_cover' => fake()->randomElement(['Secured', 'Loose']),
                'box_arrangement' => fake()->randomElement(['Standard', 'Compact']),
                'encoded_by' => 'admin',
                'remarks1' => fake()->sentence(),
                'remarks2' => fake()->sentence(),
                'remarks3' => fake()->sentence(),
                'grand_total_weight' => rand(900, 1500),
                'grand_total_quantity' => rand(1000, 5000),
                'layer_1' => $jsonMatrixTemplate()->toArray(),
                'layer_2' => $jsonMatrixTemplate()->toArray(),
                'layer_3' => $jsonMatrixTemplate()->toArray(),
                'layer_4' => $jsonMatrixTemplate()->toArray(),
                'layer_5' => $jsonMatrixTemplate()->toArray(),
                'layer_6' => $jsonMatrixTemplate()->toArray(),
                'layer_7' => $jsonMatrixTemplate()->toArray(),
                'layer_8' => $jsonMatrixTemplate()->toArray(),
                'layer_9' => $jsonMatrixTemplate()->toArray(),
                'layer_9.5' => $jsonMatrixTemplate()->toArray(),
                'layer_1_serial' => rand(1000, 9999),
                'layer_2_serial' => rand(1000, 9999),
                'layer_3_serial' => rand(1000, 9999),
                'layer_4_serial' => rand(1000, 9999),
                'layer_5_serial' => rand(1000, 9999),
                'layer_6_serial' => rand(1000, 9999),
                'layer_7_serial' => rand(1000, 9999),
                'layer_8_serial' => rand(1000, 9999),
                'layer_9_serial' => rand(1000, 9999),
                'layer_9.5_serial' => rand(1000, 9999),
            ]);
        }
    }
}
