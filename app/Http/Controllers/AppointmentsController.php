<?php

namespace App\Http\Controllers;

use App\Models\Appointments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Rule;
use Carbon\Carbon;



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
            'page' => $request->input('page', 1), // Default to page 1 if not provided
            'limit' => $request->input('limit', 5), // Default to 5 items per page if not provided
            'sort' => $request->input('sort', 'at'), // Default sort by 'at' field
            'has_no_result' => $request->input('has_no_result'),
        ];

        $appointmentList = Appointments::getAppointmentList($payload);
        
        return response()->json([
            'message' => 'Appointment list retrieved successfully',
            'appointments' => $appointmentList
        ], 200); // 200 = OK
    }

    /**
     * Store a newly created appointment in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customerName' => 'required|string|max:255',
            'customerPhone' => 'required|string|max:255',
            'at' => 'required|date_format:Y-m-d\TH:i:s.v\Z',
            'note' => 'nullable|string',
            'bookingStatus' => ['nullable', Rule::in(['Scheduled', 'Confirmed', 'Canceled', 'Rescheduled'])],
            'purpose' => 'nullable|string',
            'result' => 'nullable|string',
            'resultNotes' => 'nullable|string',
            'customer_id' => 'nullable|exists:customers,id',
            'order_id' => 'nullable|exists:orders,id',
            'outcomeReasons' => 'nullable|array',
            'lead_intent_id' => 'nullable|integer|exists:lead_intents,id',
        ]);

        $customerId = $validated['customer_id'];
        $leadIntentId = $validated['lead_intent_id'] ?? null;

        // Automatic Lead Creation: If no customer_id, try to find by phone or create
        if (!$customerId) {
            $phone = $validated['customerPhone'];
            // Normalize phone for lookup (strip non-digits and handle prefix variations)
            $normalizedPhone = preg_replace('/\D/', '', $phone);
            if (str_starts_with($normalizedPhone, '0')) {
                $strippedPhone = substr($normalizedPhone, 1);
            } elseif (str_starts_with($normalizedPhone, '62')) {
                $strippedPhone = substr($normalizedPhone, 2);
            } else {
                $strippedPhone = $normalizedPhone;
            }

            // Find customer by various phone formats
            $customer = \App\Models\Customer::where('phone', $phone)
                ->orWhere('phone', 'like', '%' . $strippedPhone)
                ->first();
            
            if (!$customer) {
                $customer = \App\Models\Customer::create([
                    'name' => $validated['customerName'],
                    'phone' => $validated['customerPhone'],
                    'source' => 'Direct',
                    'lead_intent_id' => $leadIntentId,
                ]);
                // Automatically set purpose if this is a newly created customer record
                if (!isset($validated['purpose'])) {
                    $validated['purpose'] = 'new_customer';
                }
            } else {
                // Identity Completion: If customer exists but name is "Unknown", update it
                $updateData = [];
                if ($customer->name === 'Unknown' && $validated['customerName'] !== 'Unknown') {
                    $updateData['name'] = $validated['customerName'];
                }
                // If the found customer has 'Unknown' phone (placeholder), update it to real phone
                if ($customer->phone === 'Unknown' && $validated['customerPhone'] !== 'Unknown') {
                    $updateData['phone'] = $validated['customerPhone'];
                }
                if ($leadIntentId && !$customer->lead_intent_id) {
                    $updateData['lead_intent_id'] = $leadIntentId;
                }

                if (!empty($updateData)) {
                    $customer->update($updateData);
                }
            }
            $customerId = $customer->id;
        } else {
            // Even for existing customers, if a tracking ID is provided, link it
            $customer = \App\Models\Customer::find($customerId);
            if ($customer) {
                // Identity Completion for specified existing customer
                $updateData = [];
                if (($customer->name === 'Unknown' || str_contains($customer->name, 'Visitor #')) && $validated['customerName'] !== $customer->name) {
                    $updateData['name'] = $validated['customerName'];
                }
                if ($customer->phone === 'Unknown' && $validated['customerPhone'] !== 'Unknown') {
                    $updateData['phone'] = $validated['customerPhone'];
                }
                if ($leadIntentId && !$customer->lead_intent_id) {
                    $updateData['lead_intent_id'] = $leadIntentId;
                }
                
                if (!empty($updateData)) {
                    $customer->update($updateData);
                }
            }
        }

        $appointmentData = [
            'customer_name' => $validated['customerName'],
            'customer_phone' => $validated['customerPhone'],
            'at' => Carbon::parse($validated['at']),
            'notes' => $validated['note'],
            'booking_status' => $validated['bookingStatus'] ?? 'Scheduled',
            'purpose' => $validated['purpose'] ?? null,
            'result' => $validated['result'] ?? null,
            'result_notes' => $validated['resultNotes'] ?? null,
            'customer_id' => $customerId,
            'order_id' => $validated['order_id'] ?? null,
            'outcome_reasons' => $validated['outcomeReasons'] ?? null,
        ];

        // RESTRICTION: Result "deal" (Booked) is order-driven only
        if (isset($appointmentData['result']) && $appointmentData['result'] === 'deal') {
            return response()->json([
                'message' => 'The "Booked" status can only be set automatically when an order is created.',
            ], 422);
        }

        $appointment = Appointments::create($appointmentData);

        // If order_id is provided, link the order to this appointment
        if (isset($validated['order_id'])) {
            $order = \App\Models\Order::find($validated['order_id']);
            if ($order) {
                $order->update(['appointment_id' => $appointment->id]);
                // Trigger sync to ensure lead status and history are updated
                $order->syncStatus();
            }
        }

        return response()->json($appointment, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $appointment = Appointments::find($id);
        
        if (!$appointment) {
            return response()->json(['message' => 'Appointment not found'], 404);
        }

        return response()->json($appointment, 200);
    }

    /**
     * Display the count of appointments with filters.
     */
    public function count(Request $request)
    {
        $payload = [
            'customer_name' => $request->input('customer_name'),
            'customer_phone' => $request->input('customer_phone'),
            'booking_status' => $request->input('booking_status'),
            'fromAt' => $request->input('fromAt'),
            'toAt' => $request->input('toAt'),
            'exceptId' => $request->input('exceptId'),
            'has_no_result' => $request->input('has_no_result'),
        ];

        $count = Appointments::countAppointments($payload);
        
        return response()->json([
            'count' => $count
        ], 200);
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
            'bookingStatus' => 'sometimes|string|in:Scheduled,Confirmed,Canceled,Rescheduled',
            'note' => 'sometimes|nullable|string',
            'purpose' => 'sometimes|nullable|string',
            'result' => 'sometimes|nullable|string',
            'resultNotes' => 'sometimes|nullable|string',
            'customer_id' => 'sometimes|nullable|exists:customers,id',
            'order_id' => 'sometimes|nullable|exists:orders,id',
            'outcomeReasons' => 'sometimes|nullable|array',
            'lead_intent_id' => 'sometimes|nullable|integer|exists:lead_intents,id',
        ]);

        $map = [
            'customerName' => 'customer_name',
            'customerPhone' => 'customer_phone',
            'at' => 'at',
            'bookingStatus' => 'booking_status',
            'note' => 'notes',
            'purpose' => 'purpose',
            'result' => 'result',
            'resultNotes' => 'result_notes',
            'customer_id' => 'customer_id',
            'order_id' => 'order_id',
            'outcomeReasons' => 'outcome_reasons',
        ];

        // We handle lead_intent_id separately since it updates the Customer model
        if (isset($request->lead_intent_id)) {
            $customerId = $request->customer_id ?? Appointments::find($id)->customer_id;
            if ($customerId) {
                $customer = \App\Models\Customer::find($customerId);
                if ($customer && !$customer->lead_intent_id) {
                    $customer->update(['lead_intent_id' => $request->lead_intent_id]);
                }
            }
        }

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

            // IMMUTABILITY & RESTRICTION: 
            // 1. If already "Booked", block any changes to Phase 2 (result/notes)
            if ($appointment->result === 'deal') {
                if ((isset($appointmentData['result']) && $appointmentData['result'] !== 'deal') || 
                    (isset($appointmentData['result_notes']) && $appointmentData['result_notes'] !== $appointment->result_notes)) {
                    return response()->json([
                        'message' => 'This lead is already Booked. Status and progress notes are immutable via CRM.',
                    ], 422);
                }
            }

            // 2. Setting TO "Booked" is order-driven only
            if (isset($appointmentData['result']) && $appointmentData['result'] === 'deal' && $appointment->result !== 'deal') {
                return response()->json([
                    'message' => 'The "Booked" status can only be set automatically when an order is created.',
                ], 422);
            }

            // Custom logic for Rescheduled status
            if (isset($appointmentData['booking_status']) && $appointmentData['booking_status'] === 'Rescheduled') {
                // If a new date was provided in 'at', we move it to 'rescheduled_to_at' 
                // and keep the original 'at' date for the old record.
                if (isset($appointmentData['at'])) {
                    $rescheduledDate = $appointmentData['at'];
                    $appointmentData['rescheduled_to_at'] = $rescheduledDate;
                    unset($appointmentData['at']); // Keep original date

                    // Create a duplicate record for the new date
                    Appointments::create([
                        'customer_name' => $appointmentData['customer_name'] ?? $appointment->customer_name,
                        'customer_phone' => $appointmentData['customer_phone'] ?? $appointment->customer_phone,
                        'at' => $rescheduledDate,
                        'booking_status' => 'Scheduled',
                        'purpose' => $appointmentData['purpose'] ?? $appointment->purpose,
                        'notes' => $appointmentData['notes'] ?? $appointment->notes,
                    ]);
                }
            }

            // Update the appointment instance
            $success = $appointment->update($appointmentData);

            if (!$success) {
                // If update fails, return a failure response
                return response()->json([
                    'message' => 'Failed to update appointment!',
                ], 400);
            }

            // If order_id is provided, link the order to this appointment
            if (isset($validated['order_id'])) {
                $order = \App\Models\Order::find($validated['order_id']);
                if ($order) {
                    $order->update(['appointment_id' => $appointment->id]);
                    // Trigger sync to ensure lead status and history are updated
                    $order->syncStatus();
                }
            }

            return response()->json([
                'message' => 'Appointment updated successfully!'
            ], 200);
        } catch (\Exception $e) {
            // If any error occurs, catch it and return a failure response
            return response()->json([
                'message' => 'Failed to update appointment!',
                'error' => $e->getMessage()
            ], 400);
        }
    }
    
    /**
     * Delete appointment by id
     */
    public function destroy($id)
    {
        $appointment = Appointments::find($id);

        if (!$appointment) {
            return response()->json([
                'message' => 'Appointment not found',
            ], 404);
        }

        $this->authorize('delete', $appointment);

        $appointment->delete();

        return response()->json([
            'message' => 'Appointment deleted successfully',
        ], 200);
    }
}
