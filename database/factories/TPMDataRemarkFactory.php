<?php

namespace Database\Factories;

use App\Models\TPMDataRemark;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TPMDataRemark>
 */
class TPMDataRemarkFactory extends Factory
{
    protected $model = TPMDataRemark::class;
    public function definition(): array
    {
        return [
            'Br_remarks' => $this->faker->numberBetween(0,1),
            '4paiId_remarks' => $this->faker->numberBetween(0,1),
            'iHc_remarks' => $this->faker->numberBetween(0,1),
            'bHc_remarks' => $this->faker->numberBetween(0,1),
            'BHMax_remarks' => $this->faker->numberBetween(0,1),
            'Squareness_remarks' => $this->faker->numberBetween(0,1),
            '4paiIs_remarks' => $this->faker->numberBetween(0,1),
            'iHk_remarks' => $this->faker->numberBetween(0,1),
            '4paiIa_remarks' => $this->faker->numberBetween(0,1),
            'Density_remarks' => $this->faker->numberBetween(0,1),
            'iHkiHc_remarks' => $this->faker->numberBetween(0,1),
            'Br4pai_remarks' => $this->faker->numberBetween(0,1),
            'iHr95_remarks' => $this->faker->numberBetween(0,1),
            'iHr98_remarks' => $this->faker->numberBetween(0,1),
            'Tracer_remarks' => $this->faker->numberBetween(0,1),
            'HRX_remarks' => $this->faker->numberBetween(0,1),
            'MRX_remarks' => $this->faker->numberBetween(0,1),
            'HRY_remarks' => $this->faker->numberBetween(0,1),
            'MRY_remarks' => $this->faker->numberBetween(0,1),
            'IHKA_remarks' => $this->faker->numberBetween(0,1),
            'MRA_remarks' => $this->faker->numberBetween(0,1),
            'IHKB_remarks' => $this->faker->numberBetween(0,1),
            'MRB_remarks' => $this->faker->numberBetween(0,1),
            'IHKC_remarks' => $this->faker->numberBetween(0,1),
            'MRC_remarks' => $this->faker->numberBetween(0,1),
            'HR_remarks' => $this->faker->numberBetween(0,1),
            'HRO_remarks' => $this->faker->numberBetween(0,1)
        ];
    }
}
