<?php

namespace Database\Factories;

use App\Models\TPMData;
use App\Models\FurnaceData;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TPMData>
 */
class TPMDataFactory extends Factory
{
    protected $model = TPMData::class;
    public function definition(): array
    {
        return [
            'date' => $this->faker->date(),
            'serial_no' => $this->faker->unique()->numerify('#######'),
            'code_no' => $this->faker->numerify('###'),
            'order_no' => $this->faker->numerify('####'),
            'type' => $this->faker->word(),
            'press_1'=> $this->faker->word(),
            'press_2'=> $this->faker->word(),
            'machine_no'=> $this->faker->numerify('machine###'),
            'sintering_furnace_no' => $this->faker->numerify('###'),
            'furnace_no' => $this->faker->word(),
            'zone' => $this->faker->word(),
            'pass_no' => $this->faker->word(),
            'Br' => $this->faker->randomFloat(),
            '4paiId' => $this->faker->randomFloat(),
            'iHc' => $this->faker->randomFloat(),
            'bHc' => $this->faker->randomFloat(),
            'BHMax' => $this->faker->randomFloat(),
            'Squareness' => $this->faker->randomFloat(),
            '4paiIs' => $this->faker->randomFloat(),
            'iHk' => $this->faker->randomFloat(),
            '4paiIa' => $this->faker->randomFloat(),
            'Density' => $this->faker->randomFloat(),
            'iHkiHc' => $this->faker->randomFloat(),
            'Br4pai' => $this->faker->randomFloat(),
            'iHr95' => $this->faker->randomFloat(),
            'iHr98' => $this->faker->randomFloat(),
            'Tracer' => $this->faker->randomFloat(),
            'HRX' => $this->faker->randomFloat(),
            'MRX' => $this->faker->randomFloat(),
            'HRY' => $this->faker->randomFloat(),
            'MRY' => $this->faker->randomFloat(),
            'IHKA' => $this->faker->randomFloat(),
            'MRA' => $this->faker->randomFloat(),
            'IHKB' => $this->faker->randomFloat(),
            'MRB' => $this->faker->randomFloat(),
            'IHKC' => $this->faker->randomFloat(),
            'MRC' => $this->faker->randomFloat(),
            'HR' => $this->faker->randomFloat(),
            'HRO' => $this->faker->randomFloat(),
            'x' => json_encode([1, 1, 1, 1, 1, 1, 1]),
            'y' => json_encode([2, 2, 2, 2, 2, 2]),
            'furnace_id' => FurnaceData::inRandomOrder()->first()->furnace_id,
            'layer_no' => $this->faker->numerify('###')
        ];
    }
}
