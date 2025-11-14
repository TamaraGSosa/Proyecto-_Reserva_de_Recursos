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
        $categories = Category::all(); // solo categorías
        $status_resources = StatusResource::all(); // solo estados
        $resources = Resource::all(); // solo recursos, sin relaciones

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
        $resource->name = $request->get('name');
        $resource->marca = $request->get('marca');
        $resource->description = $request->get('description');
        $resource->status_resource_id = $request->get('status_resource_id');
        $resource->category_id = $request->get('category_id');

        $resource->update();

        return redirect()->route('resources.index')
            ->with('success', 'Recurso "' . $resource->name . '" actualizado exitosamente.');
    }

    public function destroy(Resource $resource)
    {
        $resource->delete();
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
           ->where(function($q) use($start, $end){
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
