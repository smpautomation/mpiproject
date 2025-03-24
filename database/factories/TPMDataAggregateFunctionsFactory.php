<?php

namespace Database\Factories;

use App\Models\TPMDataAggregateFunctions;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TPMDataAggregateFunctions>
 */
class TPMDataAggregateFunctionsFactory extends Factory
{
    protected $model = TPMDataAggregateFunctions::class;
    public function definition(): array
    {
        return [
            'average' => json_encode([
                'Br' => $this->faker->randomFloat(),
                'iHc' => $this->faker->randomFloat(),
                'iHk' => $this->faker->randomFloat(),
                'BHMax' => $this->faker->randomFloat(),
                'Hr95' => $this->faker->randomFloat(),
                'Hr98' => $this->faker->randomFloat(),
                'iHciHk' => $this->faker->randomFloat(),
                'Br4pai' => $this->faker->randomFloat(),
                'bHc' => $this->faker->randomFloat(),
                'Squareness' => $this->faker->randomFloat(),
                '4paild' => $this->faker->randomFloat(),
                '4pails' => $this->faker->randomFloat(),
                '4paila' => $this->faker->randomFloat()
            ]),
            'maximum' => json_encode([
                'Br' => $this->faker->randomFloat(),
                'iHc' => $this->faker->randomFloat(),
                'iHk' => $this->faker->randomFloat(),
                'BHMax' => $this->faker->randomFloat(),
                'Hr95' => $this->faker->randomFloat(),
                'Hr98' => $this->faker->randomFloat(),
                'iHciHk' => $this->faker->randomFloat(),
                'Br4pai' => $this->faker->randomFloat(),
                'bHc' => $this->faker->randomFloat(),
                'Squareness' => $this->faker->randomFloat(),
                '4paild' => $this->faker->randomFloat(),
                '4pails' => $this->faker->randomFloat(),
                '4paila' => $this->faker->randomFloat()
            ]),
            'minimum' => json_encode([
                'Br' => $this->faker->randomFloat(),
                'iHc' => $this->faker->randomFloat(),
                'iHk' => $this->faker->randomFloat(),
                'BHMax' => $this->faker->randomFloat(),
                'Hr95' => $this->faker->randomFloat(),
                'Hr98' => $this->faker->randomFloat(),
                'iHciHk' => $this->faker->randomFloat(),
                'Br4pai' => $this->faker->randomFloat(),
                'bHc' => $this->faker->randomFloat(),
                'Squareness' => $this->faker->randomFloat(),
                '4paild' => $this->faker->randomFloat(),
                '4pails' => $this->faker->randomFloat(),
                '4paila' => $this->faker->randomFloat()
            ]),
            'ng_counter' => json_encode([
                'Br' => $this->faker->randomFloat(),
                'iHc' => $this->faker->randomFloat(),
                'iHk' => $this->faker->randomFloat(),
                'BHMax' => $this->faker->randomFloat(),
                'Hr95' => $this->faker->randomFloat(),
                'Hr98' => $this->faker->randomFloat(),
                'iHciHk' => $this->faker->randomFloat(),
                'Br4pai' => $this->faker->randomFloat(),
                'bHc' => $this->faker->randomFloat(),
                'Squareness' => $this->faker->randomFloat(),
                '4paild' => $this->faker->randomFloat(),
                '4pails' => $this->faker->randomFloat(),
                '4paila' => $this->faker->randomFloat()
            ])
        ];
    }
}
