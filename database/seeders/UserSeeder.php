<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        // Create Writer User
        User::firstOrCreate(
            ['email' => 'writer@example.com'],
            [
                'name' => 'Writer User',
                'password' => Hash::make('password'),
                'is_admin' => false,
                'email_verified_at' => now(),
            ]
        );

        // Create additional test writers
        for ($i = 1; $i <= 3; $i++) {
            User::firstOrCreate(
                ['email' => "writer{$i}@example.com"],
                [
                    'name' => "Test Writer {$i}",
                    'password' => Hash::make('password'),
                    'is_admin' => false,
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
