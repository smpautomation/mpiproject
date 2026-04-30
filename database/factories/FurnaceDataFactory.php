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
        $number = $this->faker->numberBetween(10, 99);

        return [
            'furnace_name' => 'K-' . $this->faker->unique()->numberBetween(10, 99),
            'description' => $this->faker->sentence(),
            'encoded_by' => $this->faker->name(),
        ];
    }
}
