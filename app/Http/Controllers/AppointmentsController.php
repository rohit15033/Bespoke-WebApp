<?php

namespace App\Http\Controllers;

use App\Models\Appointments;
use Illuminate\Http\Request;

use function Pest\Laravel\json;

class AppointmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $appointments = Appointments::latest()->get();   
        return $appointments;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return "<h1>Create form page</h1>";
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
      
        $fields = $request->validate([
            'customer_name' => ['required'],
            'customer_phone' => ['required'],
            'type' => ['required'],
        ]);

        $fields['dateTime'] = now();

        $appointments = Appointments::create($fields);
        return response()->json([
            'message' => 'Appointment created!',
            'appointment' => $appointments
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointments $appointments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointments $appointments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointments $appointments)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointments $appointments)
    {
        //
    }
}
