<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Reservation;
use App\Models\Resource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PdfController extends Controller
{


    public function exportarRecursosPorDia(Request $request)
    {
        $fecha = $request->input('fecha', Carbon::today()->toDateString());

        // Obtener los recursos que tienen reservas en la fecha dada
        $recursos = Resource::whereHas('reservations', function ($query) use ($fecha) {
            $query->whereDate('start_time', '=', $fecha);
        })->get();

        $pdf = Pdf::loadView('pdf.reporte_recursos_diario', compact('recursos', 'fecha'));
        return $pdf->stream('reporte_recursos_'.$fecha.'.pdf');
    }

    public function exportarRecursos(Request $request)
    {
        $reportType = $request->input('report_type');
        $startDate = null;
        $endDate = null;

        if ($reportType === 'day') {
            $fecha = $request->input('fecha', Carbon::today()->toDateString());
            $startDate = Carbon::parse($fecha)->startOfDay();
            $endDate = Carbon::parse($fecha)->endOfDay();
        } elseif ($reportType === 'range') {
            $startDate = $request->input('start_date', Carbon::today()->toDateString());
            $endDate = $request->input('end_date', Carbon::today()->toDateString());
            $startDate = Carbon::parse($startDate)->startOfDay();
            $endDate = Carbon::parse($endDate)->endOfDay();
        } else {
            // Default to today's report if no type is specified
            $startDate = Carbon::today()->startOfDay();
            $endDate = Carbon::today()->endOfDay();
        }

        // Obtener todas las reservas que se superponen con el rango de fechas
        $reservationsInPeriod = Reservation::where(function ($query) use ($startDate, $endDate) {
            $query->whereBetween('start_time', [$startDate, $endDate])
                  ->orWhereBetween('end_time', [$startDate, $endDate])
                  ->orWhere(function ($query) use ($startDate, $endDate) {
                      $query->where('start_time', '<', $startDate)
                            ->where('end_time', '>', $endDate);
                  });
        })->get();

        // Obtener todos los recursos
        $allResources = Resource::with('category')->get();

        $availableResources = collect();
        $unavailableResources = collect();

        foreach ($allResources as $resource) {
            $isAvailable = true;
            foreach ($reservationsInPeriod as $reservation) {
                if ($reservation->resource_id === $resource->id) {
                    // Check for overlap with the current resource
                    $resStartTime = Carbon::parse($reservation->start_time);
                    $resEndTime = Carbon::parse($reservation->end_time);

                    if ($resStartTime->lessThanOrEqualTo($endDate) && $resEndTime->greaterThanOrEqualTo($startDate)) {
                        $isAvailable = false;
                        break;
                    }
                }
            }

            if ($isAvailable) {
                $availableResources->push($resource);
            } else {
                $unavailableResources->push($resource);
            }
        }

        $pdf = Pdf::loadView('pdf.reporte_recursos', compact('availableResources', 'unavailableResources', 'startDate', 'endDate', 'reportType'));
        return $pdf->stream('reporte_recursos_'.($reportType === 'day' ? $startDate->format('Y-m-d') : $startDate->format('Y-m-d').'_'.$endDate->format('Y-m-d')).'.pdf');
    }

    public function exportarPorRangoDeFechas(Request $request)
    {
        try {
            $startDate = $request->input('start_date', Carbon::today()->toDateString());
            $endDate = $request->input('end_date', Carbon::today()->toDateString());

            // Asegurarse de que las fechas sean objetos Carbon
            $startDate = Carbon::parse($startDate)->startOfDay();
            $endDate = Carbon::parse($endDate)->endOfDay();

            // Obtener todas las reservas dentro del rango de fechas
            $reservations = Reservation::with(['resource', 'user'])
                ->whereBetween('start_time', [$startDate, $endDate])
                ->orWhereBetween('end_time', [$startDate, $endDate])
                ->orWhere(function ($query) use ($startDate, $endDate) {
                    $query->where('start_time', '<', $startDate)
                          ->where('end_time', '>', $endDate);
                })
                ->get();

            // Obtener todos los recursos
            $allResources = Resource::with('category')->get();

            $availableResources = collect();
            $unavailableResources = collect();

            foreach ($allResources as $resource) {
                $isAvailable = true;
                foreach ($reservations as $reservation) {
                    if ($reservation->resource_id === $resource->id) {
                        // Check for overlap
                        $resStartTime = Carbon::parse($reservation->start_time);
                        $resEndTime = Carbon::parse($reservation->end_time);

                        if ($resStartTime->lessThanOrEqualTo($endDate) && $resEndTime->greaterThanOrEqualTo($startDate)) {
                            $isAvailable = false;
                            break;
                        }
                    }
                }

                if ($isAvailable) {
                    $availableResources->push($resource);
                } else {
                    $unavailableResources->push($resource);
                }
            }

            $pdf = Pdf::loadView('pdf.reporte_rango_fechas', compact('reservations', 'availableResources', 'unavailableResources', 'startDate', 'endDate'));
            return $pdf->stream('reporte_reservas_rango_fechas_'.$startDate->format('Y-m-d').'_'.$endDate->format('Y-m-d').'.pdf');
        } catch (\Exception $e) {
            Log::error('Error al generar PDF por rango de fechas: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al generar el reporte PDF por rango de fechas. Por favor, inténtelo de nuevo.');
        }
    }
}
