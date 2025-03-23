<?php

namespace Database\Factories;

use App\Models\FurnaceData;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FurnaceData>
 */
class FurnaceDataFactory extends Factory
{
    public function definition(): array
    {
        return [
            'furnace_no' => $this->faker->randomNumber(),
            'furnace_name' => $this->faker->word(),
            'description' => $this->faker->sentence()
        ];
    }
}
