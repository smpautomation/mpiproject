<?php

namespace Database\Factories;

use App\Models\LayerData;
use App\Models\FurnaceData;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LayerData>
 */
class LayerDataFactory extends Factory
{
    public function definition(): array
    {
        return [
            'layer_no' => $this->faker->randomNumber(),
            'layer_name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'furnace_id' => FurnaceData::inRandomOrder()->first()->furnace_id
        ];
    }
}
