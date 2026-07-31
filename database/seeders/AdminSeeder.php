<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@poptix.com'],
            [
                'name' => 'Admin PoPTix',
                'password' => Hash::make('password123'),
            ]
        );

        $admin->assignRole('admin');
    }
}