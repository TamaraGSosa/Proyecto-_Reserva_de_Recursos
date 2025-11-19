<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ReservationResource;
use App\Models\Resource;
use App\Models\StatusResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // <-- para DB::table()
use Illuminate\Support\Facades\Log;
// <-- para Request


class ResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Obtener todas las categorías
        $categories = Category::all();

        // Obtener todos los estados excepto "Eliminado"
        $status_resources = StatusResource::where('name', '!=', 'Eliminado')->get();

        // Traer solo recursos que no tengan estado "Eliminado"
        $deletedStatus = StatusResource::where('name', 'Eliminado')->first();
        $resources = Resource::where('status_resource_id', '!=', optional($deletedStatus)->id)->get();

        return view('panel.resource.index', compact('categories', 'status_resources', 'resources'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $resource = new Resource();
        $categories = Category::get();
        $status_resources = StatusResource::get();

        return view('panel.resource.create', compact('resource', 'categories', 'status_resources'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validación
        $request->validate([
            'name' => 'required|string|max:255',
            'marca' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status_resource_id' => 'required|exists:status_resources,id',
            'category_id' => 'required|exists:categories,id',
        ], [
            'name.required' => 'El nombre del recurso es obligatorio.',
            'status_resource_id.required' => 'Debe seleccionar un estado.',
            'status_resource_id.exists' => 'El estado seleccionado no es válido.',
            'category_id.required' => 'Debe seleccionar una categoría.',
            'category_id.exists' => 'La categoría seleccionada no existe.',
        ]);
        $resource = new Resource();
        $resource->name = $request->get('name');
        $resource->marca = $request->get('marca');
        $resource->description = $request->get('description');
        $resource->status_resource_id = $request->get('status_resource_id');
        $resource->category_id = $request->get('category_id');

        $resource->save();
        return redirect()->route('resources.index')->with('success', 'Recurso creado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Resource $resource)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function json(Resource $resource)
    {
        $resource->load('status', 'category'); // carga relaciones
        return response()->json($resource);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Resource $resource)
    {
        // Validación
        $request->validate([
            'name' => 'required|string|max:255',
            'marca' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status_resource_id' => 'required|exists:status_resources,id',
            'category_id' => 'required|exists:categories,id',
        ], [
            'name.required' => 'El nombre del recurso es obligatorio.',
            'status_resource_id.required' => 'Debe seleccionar un estado.',
            'status_resource_id.exists' => 'El estado seleccionado no es válido.',
            'category_id.required' => 'Debe seleccionar una categoría.',
            'category_id.exists' => 'La categoría seleccionada no existe.',
        ]);

        // Actualizar datos
        $resource->name = $request->get('name');
        $resource->marca = $request->get('marca');
        $resource->description = $request->get('description');
        $resource->status_resource_id = $request->get('status_resource_id');
        $resource->category_id = $request->get('category_id');
        $resource->save(); // usar save() o update() aquí está bien

        return redirect()->route('resources.index')
            ->with('success', 'Recurso "' . $resource->name . '" actualizado exitosamente.');
    }


    public function destroy(Resource $resource)
    {
        // Revisar si hay reservas que impidan eliminar
        $blockedReservations = $resource->reservations()
            ->whereHas('status', function ($query) {
                $query->whereIn('name', ['Pendiente', 'En curso', 'No entregado']);
            })
            ->exists();

        if ($blockedReservations) {
            return redirect()->route('resources.index')
                ->with('error', 'El recurso no se puede eliminar porque tiene reservas activas o no entregadas.');
        }
        $deletedStatus = StatusResource::where('name', 'Eliminado')->first();
        $resource->status_resource_id = $deletedStatus->id;
        $resource->save();

        return redirect()->route('resources.index')
            ->with('success', 'Recurso eliminado exitosamente.');
    }
    public function available(Request $request)
    {
        $start = str_replace('T', ' ', $request->start_time) . ':00';
        $end = str_replace('T', ' ', $request->end_time) . ':00';
        $excludeReservationId = $request->reservation_id; // opcional

        try {
            $query = DB::table('reservation_resources')
                ->join('reservations', 'reservation_resources.reservation_id', '=', 'reservations.id')
                ->join('status_reservations', 'reservations.status_reservation_id', '=', 'status_reservations.id')
                ->whereIn('status_reservations.name', ['Pendiente', 'En curso'])
                ->where(function ($q) use ($start, $end) {
                    $q->where('reservations.start_time', '<', $end)
                        ->where('reservations.end_time', '>', $start);
                });

            if ($excludeReservationId) {
                $query->where('reservations.id', '!=', $excludeReservationId);
            }

            $occupiedIds = $query->pluck('reservation_resources.resource_id')->toArray();

            $available = Resource::whereNotIn('id', $occupiedIds)->get();

            return response()->json($available);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
