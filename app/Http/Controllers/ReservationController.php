<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\Profile;
use App\Models\Reservation;
use App\Models\Resource;
use App\Models\StatusReservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class ReservationController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $resources = Resource::all();
        $status_reservations = StatusReservation::all();

        if (Auth::user()->roles->contains('name', 'personal')) {
            $reservations = Reservation::with(['profile.person', 'resources', 'status'])
                ->where('create_by_user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->get();

            return view('panel.reservation.personal', compact('reservations', 'status_reservations', 'resources'));
        } else {
            $reservations = Reservation::with(['profile.person', 'creator.roles', 'resources', 'status'])->get();

            return view('panel.reservation.index', compact('reservations', 'status_reservations', 'resources'));
        }
    }

    public function dashboard()
    {
        $hoy = now()->toDateString();

        // Estadísticas generales del día
        $reservasDelDia = \App\Models\Reservation::whereDate('start_time', $hoy)->count();
        $reservasNoEntregadas = \App\Models\Reservation::where('status_reservation_id', 5)->count(); // No entregado

        // Detalles para los modales
        $reservasDelDiaDetalles = \App\Models\Reservation::whereDate('start_time', $hoy)
            ->with(['profile.person', 'resources', 'status', 'creator'])
            ->orderBy('start_time')
            ->get();

        $reservasNoEntregadasDetalles = \App\Models\Reservation::where('status_reservation_id', 5)
            ->with(['profile.person', 'resources', 'status', 'creator'])
            ->orderBy('end_time', 'desc')
            ->get();

        // Categorías principales a mostrar
        $categoriasPrincipales = ['Proyector', 'Notebook', 'Salón de actos'];

        // Obtener categorías principales con sus recursos
        $categories = \App\Models\Category::whereIn('name', $categoriasPrincipales)
            ->with('resources')
            ->get();

        // Para cada categoría, obtener recursos con su estado de reserva del día
        $categoriesWithResources = $categories->map(function ($category) use ($hoy) {
            $resources = $category->resources->map(function ($resource) use ($hoy) {
                // Buscar reservas del día para este recurso
                $reservationsToday = \App\Models\Reservation::whereDate('start_time', $hoy)
                    ->whereHas('resources', function ($query) use ($resource) {
                        $query->where('resources.id', $resource->id);
                    })
                    ->whereIn('status_reservation_id', [2, 4]) // En curso o Entregado
                    ->with(['profile.person'])
                    ->get();

                $isReserved = $reservationsToday->isNotEmpty();

                $reservationInfo = null;
                if ($isReserved) {
                    $reservation = $reservationsToday->first();
                    $reservationInfo = [
                        'start_time' => $reservation->start_time,
                        'end_time' => $reservation->end_time,
                        'person' => $reservation->profile->person->first_name . ' ' . $reservation->profile->person->last_name,
                        'status' => $reservation->status->name
                    ];
                }

                return [
                    'id' => $resource->id,
                    'name' => $resource->name,
                    'marca' => $resource->marca,
                    'is_reserved' => $isReserved,
                    'reservation' => $reservationInfo
                ];
            });

            return [
                'name' => $category->name,
                'resources' => $resources,
                'total_resources' => $resources->count(),
                'available_resources' => $resources->where('is_reserved', false)->count(),
                'reserved_resources' => $resources->where('is_reserved', true)->count()
            ];
        });

        if (Auth::user()->roles->contains('name', 'personal')) {
            // Datos personales
            $userId = Auth::id();
            $misReservasDelDia = \App\Models\Reservation::whereDate('start_time', $hoy)
                ->where('create_by_user_id', $userId)
                ->count();

            $misReservasNoEntregadas = \App\Models\Reservation::where('status_reservation_id', 5)
                ->where('create_by_user_id', $userId)
                ->whereDate('start_time', $hoy)
                ->count();

            $misReservasDelDiaDetalles = \App\Models\Reservation::whereDate('start_time', $hoy)
                ->where('create_by_user_id', $userId)
                ->with(['profile.person', 'resources', 'status'])
                ->orderBy('start_time')
                ->get();

            $misReservasNoEntregadasDetalles = \App\Models\Reservation::where('status_reservation_id', 5)
                ->where('create_by_user_id', $userId)
                ->whereDate('start_time', $hoy)
                ->with(['profile.person', 'resources', 'status'])
                ->orderBy('end_time', 'desc')
                ->get();

            return view('panel.personal', compact(
                'categoriesWithResources',
                'misReservasDelDia',
                'misReservasNoEntregadas',
                'misReservasDelDiaDetalles',
                'misReservasNoEntregadasDetalles'
            ));
        }

        return view('panel.index', compact(
            'categoriesWithResources',
            'reservasDelDia',
            'reservasNoEntregadas',
            'reservasDelDiaDetalles',
            'reservasNoEntregadasDetalles'
        ));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $reservation = new Reservation();


        return view('panel.reservation.create', compact('reservation'));
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    $user = Auth::user();

    if (!$user) {
        return redirect()->back()->withErrors(['auth' => 'Debes estar autenticado para crear una reserva.']);
    }

    // --- 1️⃣ Validación base: fechas y recursos ---
    $validator = Validator::make($request->all(), [
        'start_time' => 'required|date|after_or_equal:today',
        'end_time' => 'required|date|after:start_time',
        'resource_id' => 'required|array|min:1',
        'resource_id.*' => 'exists:resources,id',
    ], [
        'start_time.required' => 'Debes seleccionar una fecha de inicio.',
        'start_time.after_or_equal' => 'La fecha de inicio no puede ser pasada.',
        'end_time.required' => 'Debes seleccionar una fecha de fin.',
        'end_time.after' => 'La fecha de fin debe ser posterior a la fecha de inicio.',
        'resource_id.required' => 'Debes seleccionar al menos un recurso.',
        'resource_id.*.exists' => 'Uno de los recursos seleccionados no existe.',
    ]);

    // --- 2️⃣ Validación de disponibilidad de recursos ---
    $resourceIds = $request->resource_id ?? [];
    if (count($resourceIds) > 0) {
        $conflict = Reservation::whereHas('resources', function($q) use ($resourceIds) {
            $q->whereIn('resources.id', $resourceIds);
        })->where(function($q) use ($request) {
            $q->whereBetween('start_time', [$request->start_time, $request->end_time])
              ->orWhereBetween('end_time', [$request->start_time, $request->end_time]);
        })->exists();

        if ($conflict) {
            $validator->errors()->add('resource_id', 'Uno o más recursos ya están reservados en este horario.');
        }
    }

    // --- 3️⃣ Validación de administrador: DNI, Nombre y Apellido ---
    if (!$user->roles->contains('name', 'personal')) {
        $adminValidator = Validator::make($request->all(), [
            'dni' => 'required|string|max:20',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'status_id' => 'required|in:1,2',
        ], [
            'dni.required' => 'El DNI es obligatorio.',
            'first_name.required' => 'El nombre es obligatorio.',
            'last_name.required' => 'El apellido es obligatorio.',
            'status_id.required' => 'Debes seleccionar un estado.',
        ]);

        if ($adminValidator->fails()) {
            foreach ($adminValidator->errors()->messages() as $key => $msgs) {
                foreach ($msgs as $msg) {
                    $validator->errors()->add($key, $msg);
                }
            }
        }
    }

    // --- 4️⃣ Si hay errores, redirigir con todos ---
    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    // --- 5️⃣ Crear reserva ---
    if ($user->roles->contains('name', 'personal')) {
        $profile = $user->profile;
        if (!$profile) {
            return redirect()->back()->withErrors(['profile' => 'No tienes un perfil configurado.']);
        }
        $statusId = 1; // Pendiente para personal
    } else {
        // ✅ Aquí nos aseguramos de que dni, first_name y last_name existan
        if (!$request->dni || !$request->first_name || !$request->last_name) {
            return redirect()->back()->withErrors([
                'dni' => 'El DNI, nombre y apellido son obligatorios para crear una reserva .'
            ])->withInput();
        }

        $person = Person::firstOrCreate(
            ['DNI' => $request->dni],
            ['first_name' => $request->first_name, 'last_name' => $request->last_name]
        );

        $profile = Profile::firstOrCreate(
            ['person_id' => $person->id],
            ['user_id' => $user->id]
        );

        $statusId = $request->status_id;
    }

    // --- 6️⃣ Crear la reserva ---
    $reservation = Reservation::create([
        'profile_id' => $profile->id,
        'status_reservation_id' => $statusId,
        'start_time' => $request->start_time,
        'end_time' => $request->end_time,
        'create_by_user_id' => $user->id,
    ]);

    $reservation->resources()->attach($request->resource_id);

    return redirect()->route('reservations.index')->with('success', 'Reserva creada correctamente');
}



    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function json(Reservation $reservation)
    {
        $reservation->load('profile.person', 'resources', 'status', 'creator');
        return response()->json($reservation);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservation $reservation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reservation $reservation)
    {
        $request->validate([
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'resource_id' => 'required|array|min:1',
            'resource_id.*' => 'exists:resources,id',
        ]);

        $reservation->update([
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        // Update resources
        $reservation->resources()->sync($request->resource_id);

        return redirect()->route('reservations.index')->with('success', 'Reserva actualizada correctamente');
    }

    /**
     * Change reservation status
     */
    public function changeStatus(Request $request, Reservation $reservation)
    {
        $request->validate([
            'status_id' => 'required|exists:status_reservations,id'
        ]);

        $reservation->update([
            'status_reservation_id' => $request->status_id
        ]);

        $statusName = $reservation->status->name;
        return redirect()->route('reservations.index')->with('success', "Reserva marcada como {$statusName} correctamente");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return redirect()->route('reservations.index')->with('success', 'Reserva eliminada correctamente');
    }

    public function actualizarEstados()
    {
        $ahora = now()->setTimezone('America/Argentina/Buenos_Aires');

        // Solo marcar como "No entregado" reservas EN CURSO que pasaron su tiempo de finalización
        $reservasVencidas = Reservation::where('status_reservation_id', 2) // Solo En curso
            ->where('end_time', '<', $ahora)
            ->whereDate('start_time', now()->toDateString()) // Solo del día actual
            ->get();

        $actualizadas = 0;

        foreach ($reservasVencidas as $reserva) {
            $reserva->status_reservation_id = 5; // No entregado
            $reserva->save();
            $actualizadas++;
        }

        $message = $actualizadas > 0 ? "Se marcaron {$actualizadas} reservas como 'No entregado'" : "No hay reservas vencidas para actualizar";

        return redirect()->back()->with('success', $message);
    }
}
