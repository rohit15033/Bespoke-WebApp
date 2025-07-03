<?php

namespace App\Http\Controllers;

use App\Models\Appointments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

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
     * Create appointment
     */
    public function create(Request $request)
    {
        $validated = $request->validate([
            'customerName' => 'required|string|max:255',
            'customerPhone' => 'required|string|max:255',
            'at' => 'required|date_format:Y-m-d\TH:i:s.v\Z',
            'note' => 'nullable|string',
            'bookingStatus' => ['nullable', Rule::in(['Scheduled', 'Canceled', 'Deal'])]
        ]);

        $appointmentData = [
            'customer_name' => $validated['customerName'],
            'customer_phone' => $validated['customerPhone'],
            'at' => Carbon::parse($validated['at']),
            'notes' => $validated['note'],
            'booking_status' => $validated['bookingStatus'] ?? 'Scheduled',
        ];

        $appointment = Appointments::create($appointmentData);

        return response()->json($appointment, 201);
    }

    public function count(Request $request)
    {
        $payload = [
            'customer_name' => $request->input('customer_name'),
            'customer_phone' => $request->input('customer_phone'),
            'booking_status' => $request->input('booking_status'),
            'fromAt' => $request->input('fromAt'),
            'toAt' => $request->input('toAt'),
        ];

        $count = Appointments::countAppointments($payload);
        
        return response()->json([
            'count' => $count
        ], 200); // 200 = OK
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
