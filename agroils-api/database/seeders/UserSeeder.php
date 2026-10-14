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
    }
}
