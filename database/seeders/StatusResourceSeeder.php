<?php

namespace Database\Seeders;

use App\Models\StatusResource;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StatusResource::create([
            'name' => 'Disponible'
        ]);
        StatusResource::create([
            'name' => 'No disponible'
        ]);
        StatusResource::create([
            'name' => 'Eliminado'
        ]);
    }
}
