<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Resource;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    public function generarPdfDiario(Request $request)
    {
        $fechaInput = $request->input('fecha', today()->toDateString());
        $fecha = \Carbon\Carbon::parse($fechaInput);

        // 1. Obtener los datos necesarios
        $totalRecursos = Resource::count();
        
        // Contar recursos únicos que tienen una reserva creada en la fecha seleccionada
        $recursosReservados = Reservation::whereDate('reservations.created_at', $fecha)
                                            ->join('reservation_resources', 'reservations.id', '=', 'reservation_resources.reservation_id')
                                            ->distinct('reservation_resources.resource_id')
                                            ->count('reservation_resources.resource_id');

        // Obtener el detalle de las reservas de la fecha seleccionada
        $reservas = Reservation::with(['resource', 'user', 'statusReservation'])
                                ->whereDate('reservations.created_at', $fecha)
                                ->orderBy('reservations.created_at', 'desc')
                                ->get();

        $fechaFormateada = $fecha->format('d-m-Y');

        // 2. Cargar la nueva vista del PDF con los datos
        $pdf = Pdf::loadView('pdf.reporte_diario_mejorado', [
            'totalRecursos' => $totalRecursos,
            'recursosReservadosHoy' => $recursosReservados,
            'reservas' => $reservas,
            'fecha' => $fechaFormateada
        ]);

        // 3. Generar y enviar el PDF al navegador
        return $pdf->stream('reporte-diario-' . $fechaFormateada . '.pdf');
    }
}
