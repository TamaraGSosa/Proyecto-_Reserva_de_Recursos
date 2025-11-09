<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Resource;
use App\Models\StatusResource;
use Illuminate\Http\Request;
use PHPUnit\Logging\OpenTestReporting\Status;

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
}
