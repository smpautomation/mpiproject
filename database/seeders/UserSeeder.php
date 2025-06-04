<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create 50 random users with firstName, surname, username, etc.
        User::factory()->count(15)->create();

        // Admin user with all required fields
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'firstName'   => 'Admin',
                'surname'     => 'User',
                'username'    => 'admin',
                'email'       => 'admin@example.com',
                'plant'       => 'Main Plant',
                'employee_id' => '006644',
                'access_type' => 'Final Approver',
                'password'    => Hash::make('password123'),
            ]
        );
    }
}
