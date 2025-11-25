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

    public function generarReporteReservas(Request $request)
    {
        $request->validate([
            'tipo_de_informe' => 'required|in:day,range',
            'fecha' => 'nullable|date|required_if:tipo_de_informe,day',
            'fecha_de_inicio' => 'nullable|date|required_if:tipo_de_informe,range',
            'fecha_final' => 'nullable|date|required_if:tipo_de_informe,range|after_or_equal:fecha_de_inicio',
        ]);

        $reportType = $request->input('tipo_de_informe');
        $reservations;
        $title;

        if ($reportType == 'day') {
            $fecha = \Carbon\Carbon::parse($request->input('fecha'))->startOfDay();
            $title = 'Reporte Diario de Reservas (' . $fecha->format('Y-m-d') . ')';
            $reservations = Reservation::with(['profile.person', 'resources', 'status'])
                                      ->whereDate('created_at', $fecha)
                                      ->get();
        } else { // range
            $startDate = \Carbon\Carbon::parse($request->input('fecha_de_inicio'))->startOfDay();
            $endDate = \Carbon\Carbon::parse($request->input('fecha_final'))->endOfDay();
            $title = 'Reporte de Reservas (' . $startDate->format('Y-m-d') . ' al ' . $endDate->format('Y-m-d') . ')';
            $reservations = Reservation::with(['profile.person', 'resources', 'status'])
                                      ->whereBetween('created_at', [$startDate, $endDate])
                                      ->get();
        }

        $pdf = Pdf::loadView('pdf.reservas', compact('reservations', 'title'));
        return $pdf->stream('reporte_reservas.pdf');
    }
}
