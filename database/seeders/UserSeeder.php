<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin account
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin POS',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Regular user account
        User::firstOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name' => 'User Kasir',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]
        );
    }
}
