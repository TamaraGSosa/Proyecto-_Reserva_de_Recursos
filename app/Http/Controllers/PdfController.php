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
    public function exportarRecursos(Request $request)
    {
        $reportType = $request->input('report_type', 'day');
        $startDate = null;
        $endDate = null;
        $fecha = null;

        if ($reportType === 'day') {
            $fecha = $request->input('fecha', Carbon::today()->toDateString());
            $startDate = Carbon::parse($fecha)->startOfDay();
            $endDate = Carbon::parse($fecha)->endOfDay();
        } elseif ($reportType === 'range') {
            $startDate = $request->input('start_date', Carbon::today()->toDateString());
            $endDate = $request->input('end_date', Carbon::today()->toDateString());
            $startDate = Carbon::parse($startDate)->startOfDay();
            $endDate = Carbon::parse($endDate)->endOfDay();
            $fecha = $startDate->toDateString(); // Use start date for display
        } else {
            // Default to today's report if no type is specified
            $fecha = Carbon::today()->toDateString();
            $startDate = Carbon::today()->startOfDay();
            $endDate = Carbon::today()->endOfDay();
            $reportType = 'day';
        }

        // Obtener todas las reservas que se superponen con el rango de fechas
        $reservations = Reservation::with(['resources', 'user', 'profile.person'])
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_time', [$startDate, $endDate])
                      ->orWhereBetween('end_time', [$startDate, $endDate])
                      ->orWhere(function ($query) use ($startDate, $endDate) {
                          $query->where('start_time', '<', $startDate)
                                ->where('end_time', '>', $endDate);
                      });
            })
            ->get();

        // Obtener todos los recursos
        $allResources = Resource::with('category')->get();

        $availableResources = collect();
        $unavailableResources = collect();

        foreach ($allResources as $resource) {
            $isReserved = false;
            foreach ($reservations as $reservation) {
                if ($reservation->resources->contains('id', $resource->id)) {
                    $isReserved = true;
                    break;
                }
            }

            if ($isReserved) {
                $unavailableResources->push($resource);
            } else {
                $availableResources->push($resource);
            }
        }
        
        $pdf = Pdf::loadView('pdf.reporte_recursos', compact('reservations', 'availableResources', 'unavailableResources', 'startDate', 'endDate', 'reportType', 'fecha'));
        
        $fileName = 'reporte_recursos_';
        if ($reportType === 'day') {
            $fileName .= $startDate->format('Y-m-d');
        } else {
            $fileName .= $startDate->format('Y-m-d') . '_' . $endDate->format('Y-m-d');
        }
        $fileName .= '.pdf';

        return $pdf->stream($fileName);
    }
}