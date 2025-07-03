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

    public function get(Request $request, $id)
    {
        $appointment = Appointments::find($id);
        if (!$appointment) {
            return response()->json([
                'message' => 'Appointment not found',
            ], 404);
        }
        return response()->json($appointment, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate incoming request
        $validated = $request->validate([
            'customerName' => 'sometimes|string|max:255',
            'customerPhone' => 'sometimes|string|max:20',
            'at' => 'sometimes|date_format:Y-m-d\TH:i:s.v\Z',
            'bookingStatus' => 'sometimes|string|in:Scheduled,Canceled,Deal',
            'note' => 'sometimes|nullable|string'
        ]);

        $map = [
            'customerName' => 'customer_name',
            'customerPhone' => 'customer_phone',
            'at' => 'at',
            'bookingStatus' => 'booking_status',
            'note' => 'notes'
        ];

        $appointmentData = [];
        foreach ($map as $input => $column) {
            if (isset($validated[$input])) {
                // Parse 'at' field using Carbon
                if ($input === 'at') {
                    $appointmentData[$column] = Carbon::parse($validated[$input]);
                } else {
                    $appointmentData[$column] = $validated[$input];
                }
            }
        }

        try {
            // Find the appointment by its ID
            $appointment = Appointments::find($id);

            // Check if the appointment exists
            if (!$appointment) {
                return response()->json([
                    'message' => 'Appointment not found!'
                ], 404);
            }

            // Update the appointment instance
            $success = $appointment->update($appointmentData);

            if (!$success) {
                // If update fails, return a failure response
                return response()->json([
                    'message' => 'Failed to update appointment!',
                ], 400);
            } else {
                return response()->json([
                    'message' => 'Appointment updated successfully!'
                ], 200);
            }
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
