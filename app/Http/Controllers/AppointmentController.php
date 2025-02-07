<?php

namespace App\Http\Controllers;

use App\Models\appointment;

use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        //$appointments = appointment::latest()->get();
        // return inertia("Appointments");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //

        //return inertia("Appointments/Create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $fields = $request->validate([
         
            'customer_name' => 'required',
            'customer_phone' => 'required'
        ]);
        $fields['type'] = 'pending';
        appointment::create($fields);
        return redirect("/appointment");

    }

    /**
     * Display the specified resource.
     */
    public function show(appointment $appointment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(appointment $appointment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, appointment $appointment)
    {
        //
        $fields = $request->validate([
         
            'customer_name' => 'required',
            'customer_phone' => 'required'
        ]);

        $appointment->update($fields);
        return redirect("/appointment")->with(
            'success', 'Appointment Updated'
        );;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(appointment $appointment)
    {
        //
        $appointment->delete();
        return redirect('/')->with(
            'message', 'Post Deleted'
        );
    }
}
