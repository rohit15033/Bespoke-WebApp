<?php

namespace App\Http\Controllers;

use App\Models\Appointments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use PhpParser\Node\Stmt\TryCatch;

use function Pest\Laravel\json;

class AppointmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $payload = [
            'customer_name' => $request->input('customer_name'),
            'customer_phone' => $request->input('customer_phone'),
            'booking_status' => $request->input('booking_status'),
            'fromAt' => $request->input('fromAt'),
            'toAt' => $request->input('toAt'),
            // 'page' => $request->input('page', 1), // Default to page 1 if not provided
            // 'limit' => $request->input('limit', 5), // Default to 5 items per page if not provided
            // 'sort' => $request->input('sort', 'at'), // Default sort by 'at' field
        ];

        $appointmentList = Appointments::getAppointmentList($payload);
        
        return response()->json([
            'message' => 'Appointment list retrieved successfully',
            'appointments' => $appointmentList
        ], 200); // 200 = OK
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
    try {
        // Validate incoming request
        $fields = $request->validate([
            'booking_status' => ['required'],
            'customer_name' => ['required'],
            'customer_phone' => ['required'],
            'at' => ['required'],
        ]);

        // Create a new appointment
        $appointment = Appointments::create($fields);

        // Return success response
        return response()->json([
            'message' => 'Appointment created!',
            'appointment' => $appointment
        ], 201); // 201 = Created

    } catch (\Exception $e) {
        // If any error occurs, catch it and return a failure response
        return response()->json([
            'message' => 'Failed to create appointment!',
            'error' => $e->getMessage()
        ], 400);
    }
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
    public function update(Request $request, $id)
    {

        $appointment = Appointments::findOrFail($id);
        try {
            // Validate incoming request
            $fields = $request->validate([
                'booking_status' => ['required'],
                'customer_name' => ['required'],
                'customer_phone' => ['required'],
                'at' => ['required'],
            ]);

            // Update the appointment
            $appointment->update($fields);

            // Return success response
            return response()->json([
                'message' => 'Appointment updated!',
                'appointment' => $appointment
            ], 200); // 200 = OK
        } catch (\Exception $e) {
            // If any error occurs, catch it and return a failure response
            return response()->json([
                'message' => 'Failed to update appointment!',
                'error' => $e->getMessage()
            ], 400);
        }


    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointments $appointments)
    {
        //
    }
}
