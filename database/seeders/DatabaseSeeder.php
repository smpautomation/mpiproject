<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\TPMData;
use App\Models\TPMDataRemark;
use App\Models\TPMDataAggregateFunctions;
use App\Models\FurnaceData;
use App\Models\InspectionData;
use App\Models\LayerData;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        FurnaceData::factory(50)->create();
        LayerData::factory(50)->create();
        TPMData::factory(50)->create()->each(function ($tpmData) {
            // Create related remarks and aggregate functions for each TpmData
            TPMDataRemark::factory()->create([
                'tpm_data_id' => $tpmData->id,
            ]);
    
            TPMDataAggregateFunctions::factory()->create([
                'tpm_data_id' => $tpmData->id,
            ]);
        });
        InspectionData::factory(50)->create();
    }
}
