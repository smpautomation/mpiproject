<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InspectionData>
 */
class InspectionDataFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'model' => $this->faker->word(),
            'lenght' => $this->faker->randomFloat(2, 0, 100),
            'width' => $this->faker->randomFloat(2, 0, 100),
            'thickness' => $this->faker->randomFloat(2, 0, 100),
            'material_grade' => $this->faker->word(),
            'br' => $this->faker->word(),
            'ihc' => $this->faker->word(),
            'ihk' => $this->faker->word(),
            'oven_machine_no' => $this->faker->word(),
            'time_loading'=> $this->faker->time(),
            'temperature_1' => $this->faker->randomFloat(2, 0, 100),
            'date' => $this->faker->date(),
            'time_unloading' => $this->faker->time(),
            'temperature_2'=> $this->faker->randomFloat(2, 0, 100),
            'shift' => $this->faker->word(),
            'operator' => $this->faker->name(),
            'mpi_sample' => $this->faker->word(),
            'ihc_ihk' => $this->faker->word(),
            'br_4-pie-la' => $this->faker->word(),
        ];
    }
}
