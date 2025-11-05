<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
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
                $data = collect($columns)->mapWithKeys(function ($col) use ($rowTitle) {
                    switch ($rowTitle) {
                        case 'LT. No.:':
                            $value = rand(1000, 9999) . '-' . rand(1, 5);
                            break;

                        case 'QTY (PCS):':
                            $value = rand(1000, 3000);
                            break;

                        case 'HT (PCS):':
                        case 'LT (PCS):':
                            $value = ''; // Always empty
                            break;

                        case 'COATING:':
                            $value = rand(1, 10);
                            break;

                        case 'WT (KG)':
                            $value = number_format(rand(1000, 2000) / 50, 2);
                            break;

                        case 'COATING M/C No.:':
                            $value = 'C-' . rand(10, 50);
                            break;

                        case 'BOX No.:':
                            $letters = strtoupper(Str::random(3));
                            $digits = str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
                            $value = $letters . $digits;
                            break;

                        default:
                            $value = fake()->word();
                            break;
                    }

                    return [$col => $value];
                });

                return [
                    'rowTitle' => $rowTitle,
                    'data' => $data,
                ];
            });
        };

        function generateOrdinal($number) {
            $lastDigit = $number % 10;
            $lastTwoDigits = $number % 100;

            if ($lastTwoDigits >= 11 && $lastTwoDigits <= 13) {
                $suffix = 'TH';
            } else {
                $suffix = match ($lastDigit) {
                    1 => 'ST',
                    2 => 'ND',
                    3 => 'RD',
                    default => 'TH',
                };
            }

            return $number . $suffix;
        }

        for ($i = 1; $i <= 10; $i++) {
            $generateMassProd = generateOrdinal(rand(500, 999));
            $generateFurnace = 'K-' . rand(10, 80);
            $cleanFurnace = str_replace('-', '', $generateFurnace); // 'K40'
            $randomSerial = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT); // '0581'

            MassProduction::create([
                'mass_prod' => $generateMassProd,
                'furnace' => $generateFurnace,
                'batch_cycle_no' => $generateMassProd,
                'machine_no' => $generateFurnace,
                'cycle_no' => $cleanFurnace . '-' . $randomSerial,
                'pattern_no' => rand(1, 99),
                'cycle_pattern' => fake()->randomElement(['ABNORMAL', '']),
                'current_pattern' => fake()->randomElement(['ABNORMAL', '']),
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
                //'grand_total_weight' => rand(900, 1500),
                //'grand_total_quantity' => rand(1000, 5000),
                'layer_1' => json_encode($jsonMatrixTemplate()->toArray()),
                'layer_2' => json_encode($jsonMatrixTemplate()->toArray()),
                'layer_3' => json_encode($jsonMatrixTemplate()->toArray()),
                'layer_4' => json_encode($jsonMatrixTemplate()->toArray()),
                'layer_5' => json_encode($jsonMatrixTemplate()->toArray()),
                'layer_6' => json_encode($jsonMatrixTemplate()->toArray()),
                'layer_7' => json_encode($jsonMatrixTemplate()->toArray()),
                'layer_8' => json_encode($jsonMatrixTemplate()->toArray()),
                'layer_9' => json_encode($jsonMatrixTemplate()->toArray()),
                'layer_9_5' => json_encode($jsonMatrixTemplate()->toArray()),
                'layer_1_serial' => '2508' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT),
                'layer_2_serial' => '2508' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT),
                'layer_3_serial' => '2508' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT),
                'layer_4_serial' => '2508' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT),
                'layer_5_serial' => '2508' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT),
                'layer_6_serial' => '2508' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT),
                'layer_7_serial' => '2508' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT),
                'layer_8_serial' => '2508' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT),
                'layer_9_serial' => '2508' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT),
                'layer_9_5_serial' => '2508' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT),
            ]);
        }
    }
}
