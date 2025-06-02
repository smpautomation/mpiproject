<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create 10 random users via factory (make sure your UserFactory is set up)
        User::factory()->count(50)->create();

        User::updateOrCreate(
            ['email' => 'admin@example.com'], // unique identifier
            [
                'name' => 'Admin User',
                'username' => 'admin',
                'email' => 'admin@example.com',
                'plant' => 'Main Plant',
                'employee_id' => '006645',
                'password' => Hash::make('password123'), // choose a secure default password here
            ]
        );
    }
}
