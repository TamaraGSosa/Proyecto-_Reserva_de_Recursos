<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        User::create([
            'name' => 'Admin',
            'email' => 'admin@reserva.com',
            'password' => Hash::make('123456')
        ])->assignRole('administrador');
        User::create([
            'name' => 'personal1',
            'email' => 'personal1@personal.com',
            'password' => Hash::make('123456')
        ])->assignRole('personal');
    }
}
