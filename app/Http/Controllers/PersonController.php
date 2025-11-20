<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    
   public function index()
    {
        $people = Person::all();
        return view('usuarios.index', compact('people'));
    }

    public function create()
    {
        return view('usuarios.create');
    }

    public function edit(Person $person)
    {
        return view('usuarios.edit', compact('person'));
    }

    

    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Person $person)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Person $person)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Person $person)
    {
        //
    }
    public function search($dni)
    {
        $person = Person::where('dni', $dni)->with('user')->first();

        if ($person) {
            return response()->json($person);
        } else {
            return response()->json(null);
        }
    }
}
