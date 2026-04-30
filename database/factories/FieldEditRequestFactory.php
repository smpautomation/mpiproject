<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FieldEditRequest>
 */
class FieldEditRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $number = $this->faker->numberBetween(1, 1000);

        $suffix = 'TH';
        if (!in_array($number % 100, [11, 12, 13])) {
            $lastDigit = $number % 10;

            if ($lastDigit === 1) $suffix = 'ST';
            elseif ($lastDigit === 2) $suffix = 'ND';
            elseif ($lastDigit === 3) $suffix = 'RD';
        }

        $massProd = $number . $suffix;

        $furnace = 'K-' . $this->faker->numberBetween(30, 60);
        $prefix = str_replace('-', '', $furnace); // K-32 → K32

        return [
            'mass_prod' => $massProd,
            'furnace' => $furnace,
            'current_data' => $prefix . '-' . $this->faker->numberBetween(1000, 9999),
            'new_data' => $prefix . '-' . $this->faker->numberBetween(1000, 9999),
            'target_column' => 'cycle_no',
            'reason' => $this->faker->sentence(),
            'corrective_action' => $this->faker->sentence(),
            'request_by' => $this->faker->name(),
            'status' => $this->faker->randomElement(['Pending', 'Approved', 'Rejected']),
        ];
    }
}
