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
        // Navicosoft Writer
        User::firstOrCreate(
            ['email' => 'writer@navicosoft.com'],
            [
                'name' => 'Navicosoft Writer',
                'password' => Hash::make('password'),
                'is_admin' => false,
                'email_verified_at' => now(),
            ]
        );

        // Navicosoft Admin
        User::firstOrCreate(
            ['email' => 'admin@navicosoft.com'],
            [
                'name' => 'Navicosoft Admin',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        // Abdullah Writer
        User::firstOrCreate(
            ['email' => 'writer@abdullah.com'],
            [
                'name' => 'Abdullah Writer',
                'password' => Hash::make('password'),
                'is_admin' => false,
                'email_verified_at' => now(),
            ]
        );

        // Abdullah Admin
        User::firstOrCreate(
            ['email' => 'admin@abdullah.com'],
            [
                'name' => 'Abdullah Admin',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        // Hannan Writer
        User::firstOrCreate(
            ['email' => 'writer@hannan.com'],
            [
                'name' => 'Hannan Writer',
                'password' => Hash::make('password'),
                'is_admin' => false,
                'email_verified_at' => now(),
            ]
        );

        // Hannan Admin
        User::firstOrCreate(
            ['email' => 'admin@hannan.com'],
            [
                'name' => 'Hannan Admin',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
