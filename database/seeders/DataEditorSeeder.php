<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FieldEditRequest;
use App\Models\LayerDeleteRequest;

class DataEditorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FieldEditRequest::factory()
            ->count(1)
            ->create();

        LayerDeleteRequest::factory()
            ->count(10)
            ->create();
    }
}
