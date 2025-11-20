<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Reserva;
use App\Mail\ConfirmacionReserva;

class ReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservas = Reserva::all();
        return view('reservas.index', compact('reservas'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return view('reservas.create');

    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
{
    // Validación mejorada
    $data = $request->validate([
        'nombre' => 'required|string|max:150',
        'email' => 'required|email|max:150',
        'producto' => 'required|string|max:150',
        'fecha' => 'required|date',
    ]);

    // Guardar en base de datos
    $reserva = Reserva::create($data);

    // Agregar el ID de la reserva al array para el email
    $data['id'] = $reserva->id;

    // Enviar correo de confirmación
    try {
        // Si querés enviar usando colas, cambialo a: queue()
        Mail::to($data['email'])->send(new ConfirmacionReserva($data));

        return redirect()
            ->route('reservas.index')
            ->with('success', 'Reserva creada y correo enviado correctamente.');
    } catch (\Exception $e) {
        // Si falla el correo, igual la reserva queda creada
        return redirect()
            ->route('reservas.index')
            ->with('error', 'La reserva fue creada, pero no se pudo enviar el correo de confirmación.');
    }
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
         $reserva = Reserva::findOrFail($id);
        return view('reservas.show', compact('reserva'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $reserva = Reserva::findOrFail($id);
        return view('reservas.edit', compact('reserva'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'nombre' => 'required|string',
            'email' => 'required|email',
            'producto' => 'required|string',
            'fecha' => 'required|date',
        ]);

        $reserva = Reserva::findOrFail($id);
        $reserva->update($data);

        return redirect()->route('reservas.index')->with('success', 'Reserva actualizada.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         $reserva = Reserva::findOrFail($id);
        $reserva->delete();

        return redirect()->route('reservas.index')->with('success', 'Reserva eliminada.');

    }
}
