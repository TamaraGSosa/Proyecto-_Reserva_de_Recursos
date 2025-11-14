<?php

namespace Database\Seeders;

use App\Models\StatusReservation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StatusReservation::create([
            'name' => 'Pendiente', // reserva creada pero no retirada
        ]);

        StatusReservation::create([
            'name' => 'En curso', // recurso retirado, en uso
        ]);

        StatusReservation::create([
            'name' => 'Cancelada', // reserva anulada antes de retirar
        ]);

        StatusReservation::create([
            'name' => 'Entregado', // recurso entregado de vuelta
        ]);
        StatusReservation::create([
            'name' => 'No entregado', // recurso entregado de vuelta
        ]);
        
    }
}
