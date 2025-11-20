<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Person;
use App\Models\Profile;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Administrador
        User::create([
            'name' => 'Admin',
            'email' => 'admin@reserva.com',
            'password' => Hash::make('123456')
        ])->assignRole('administrador');

        // Personal 1
        $user1 = User::create([
            'name' => 'personal1',
            'email' => 'personal1@personal.com',
            'password' => Hash::make('123456')
        ]);
        $user1->assignRole('personal');

        // Crear persona y profile para personal1
        $person1 = Person::create([
            'DNI' => '12345678',
            'first_name' => 'Juan',
            'last_name' => 'Pérez'
        ]);

        Profile::create([
            'person_id' => $person1->id,
            'user_id' => $user1->id
        ]);

        // Personal 2
        $user2 = User::create([
            'name' => 'cesar rojas',
            'email' => 'cesar@inbox.mailtrap.io',
            'password' => Hash::make('123456')
        ]);
        $user2->assignRole('personal');

        // Crear persona y profile para personal2
        $person2 = Person::create([
            'DNI' => '87654321',
            'first_name' => 'cesar',
            'last_name' => 'Rojas'
        ]);

        Profile::create([
            'person_id' => $person2->id,
            'user_id' => $user2->id
        ]);
    }

}
