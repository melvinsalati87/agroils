<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Default System Administrator
        User::updateOrCreate(
            ['email' => 'melvin.salati@agroils.com'],
            [
                'user_uuid' => (string) Str::uuid(),
                'name' => 'AgrOils Administrator',
                'username' => 'melvin.salati',
                'first_name' => 'System',
                'last_name' => 'Administrator',
                'middle_name' => '',
                'gender' => 'male',
                'date_of_birth' => '1990-01-01',
                'user_type' => 1,
                'password' => Hash::make(
                    env('TEST_ADMIN_PASSWORD', 'ChangeMe123!')
                ),
                'email_verified_at' => now(),
            ]
        );

        // 2. Extra Users Setup
        $extraUsers = [
            [
                'email' => 'john.doe@agroils.com',
                'name' => 'John Doe',
                'username' => 'john.doe',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'gender' => 'Male',
                'date_of_birth' => '1992-05-15',
                'user_type' => 2,
            ],
            [
                'email' => 'jane.smith@agroils.com',
                'name' => 'Jane Smith',
                'username' => 'jane.smith',
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'gender' => 'Female',
                'date_of_birth' => '1994-08-22',
                'user_type' => 2,
            ],
            [
                'email' => 'alex.jones@agroils.com',
                'name' => 'Alex Jones',
                'username' => 'alex.jones',
                'first_name' => 'Alex',
                'last_name' => 'Jones',
                'gender' => 'Male',
                'date_of_birth' => '1995-11-30',
                'user_type' => 2,
            ],
        ];

        foreach ($extraUsers as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'user_uuid' => (string) Str::uuid(),
                    'name' => $userData['name'],
                    'username' => $userData['username'],
                    'first_name' => $userData['first_name'],
                    'last_name' => $userData['last_name'],
                    'middle_name' => '',
                    'gender' => $userData['gender'],
                    'date_of_birth' => $userData['date_of_birth'],
                    'user_type' => $userData['user_type'],
                    'password' => Hash::make('Password123!'),
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
