<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'full_name' => 'SuperAdmin SuperAdmin',
            'email' => 'SuperAdmin@gmail.com',
            'password' => Hash::make('SuperAdmin333222'),
            'role' => 'super_admin'
        ]);
    }
}
