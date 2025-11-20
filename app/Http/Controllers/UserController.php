<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    $users = User::all();
    return view('usuarios.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('usuarios.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'dni' => 'required|numeric',
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        DB::transaction(function () use ($request) {
            $person = Person::where('dni', $request->dni)->first();

            if ($person) {
                if ($person->user) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'dni' => ['Este DNI ya está asociado a un usuario.'],
                    ]);
                }
            } else {
                $person = Person::create([
                    'DNI' => $request->dni,
                    'first_name' => $request->nombre,
                    'last_name' => $request->apellido,
                    'email' => $request->email,
                ]);
            }

            User::create([
                'name' => $request->nombre . ' ' . $request->apellido,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'person_id' => $person->id,
            ]);
        });

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');
    }
    /**
     * Display the specified resource.
     */
    public function show(User $usuario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $usuario)
    {
        return view('usuarios.edit', compact('usuario'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $usuario)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $usuario->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = $request->only('name', 'email');
        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        $usuario->update($data);

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $usuario)
    {
    if ($usuario->reservations()->exists()) {
        return redirect()->route('usuarios.index')->with('error', 'No se puede eliminar el usuario porque tiene reservas asociadas.');
    }

    $usuario->update(['estado' => 'inactivo']);
    return redirect()->route('usuarios.index')->with('success', 'Usuario desactivado correctamente.');
    }
}
