<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function status(): \Illuminate\Http\JsonResponse
    {
        $now = Carbon::now('Asia/Jakarta');
        $today = $now->toDateString();

        // First try to find a shift for today (in Asia/Jakarta)
        $attendance = \App\Models\Attendance::where('user_id', auth()->id())
            ->where('date', $today)
            ->first();

        // If no shift for today, check if there's an ongoing shift from "yesterday" (any record with no clock_out)
        if (!$attendance) {
            $attendance = \App\Models\Attendance::where('user_id', auth()->id())
                ->whereNull('clock_out')
                ->orderBy('date', 'desc')
                ->first();
        }

        return response()->json($attendance);
    }

    public function clockIn(Request $request): \Illuminate\Http\JsonResponse
    {
        // Check if there's already an active shift (no clock_out)
        $activeShift = \App\Models\Attendance::where('user_id', auth()->id())
            ->whereNull('clock_out')
            ->exists();

        if ($activeShift) {
            return response()->json([
                'message' => 'You are already clocked in.'
            ], 422);
        }

        $now = Carbon::now('Asia/Jakarta');

        $attendance = \App\Models\Attendance::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'date' => $now->toDateString(),
            ],
            [
                'clock_in' => $now,
                'status' => 'present',
                'clock_out' => null, // Ensure clock_out is cleared if re-clocking in for same day
            ]
        );

        return response()->json($attendance);
    }

    public function clockOut(Request $request): \Illuminate\Http\JsonResponse
    {
        // Find the active shift (latest record for this user where clock_out is null)
        $attendance = \App\Models\Attendance::where('user_id', auth()->id())
            ->whereNull('clock_out')
            ->orderBy('date', 'desc')
            ->first();

        if (!$attendance) {
            return response()->json([
                'message' => 'No active shift found to clock out from.'
            ], 422);
        }


        // Check if user has submitted a daily report for the date of this shift
        $reportExists = \App\Models\DailyReport::where('user_id', auth()->id())
            ->where('report_date', $attendance->date->format('Y-m-d'))
            ->exists();

        if (!$reportExists) {
            return response()->json([
                'message' => 'Please submit your daily report before clocking out.',
                'shift_date' => $attendance->date->format('Y-m-d')
            ], 422);
        }

        // Check for pending appointments (past time, confirmed status, but missing details)
        // Only enforce this for marketers as per user request
        if (auth()->user()->isMarketer()) {
            $appointments = \App\Models\Appointments::whereDate('at', $attendance->date)->get();

            foreach ($appointments as $appointment) {
                if ($appointment->booking_status === 'Scheduled') {
                     return response()->json([
                        'message' => "Cannot clock out. Appointment for {$appointment->customer_name} at " . Carbon::parse($appointment->at)->format('H:i') . " is still marked as 'Scheduled'. Please update its status.",
                    ], 422);
                }

                $missing = [];
                
                // For Confirmed appointments, check Purpose, Result and Result Notes
                if ($appointment->booking_status === 'Confirmed') {
                    if (empty($appointment->purpose)) $missing[] = 'Purpose';
                    if (empty($appointment->result_notes)) $missing[] = 'Result Notes';
                    if ($appointment->purpose === 'new_customer' && empty($appointment->result)) $missing[] = 'Result (Booked/Lost Lead)';
                } 
                // For Rescheduled or Canceled, check for a Reason (stored in result_notes or notes)
                else if (in_array($appointment->booking_status, ['Rescheduled', 'Canceled'])) {
                    if (empty($appointment->result_notes) && empty($appointment->notes)) {
                        $missing[] = 'Reason/Note';
                    }
                }

                if (!empty($missing)) {
                    return response()->json([
                        'message' => "Cannot clock out. {$appointment->booking_status} appointment for {$appointment->customer_name} is missing: " . implode(', ', $missing),
                    ], 422);
                }
            }
        }

        $attendance->update(['clock_out' => Carbon::now('Asia/Jakarta')]);

        return response()->json($attendance);
    }

    public function history(Request $request): \Illuminate\Http\JsonResponse
    {
        $query = \App\Models\Attendance::with('user');
        
        // Master and Owner can see everyone's records
        if (!auth()->user()->isMaster() && auth()->user()->role !== 'owner') {
            $query->where('user_id', auth()->id());
        } else if ($request->has('user_id')) {
            // Allow filtering by user_id for admins
            $query->where('user_id', $request->user_id);
        }

        // Filter by specific date or date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        } else if ($request->filled('date')) {
            $query->where('date', $request->date);
        }

        $attendances = $query->orderBy('date', 'desc')->paginate(50); // Increased pagination for range reports

        // For each attendance, try to find the corresponding daily report and appointments
        $attendances->getCollection()->transform(function ($attendance) {
            $report = \App\Models\DailyReport::where('user_id', $attendance->user_id)
                ->where('report_date', $attendance->date)
                ->first();
            
            // Only attach appointments for marketers
            $appointments = [];
            if ($attendance->user && $attendance->user->isMarketer()) {
                $appointments = \App\Models\Appointments::whereDate('at', $attendance->date)
                    ->select('id', 'customer_name', 'customer_phone', 'at', 'booking_status', 'purpose', 'result', 'result_notes', 'notes', 'rescheduled_to_at')
                    ->orderBy('at', 'asc')
                    ->get();
            }
            
            $attendance->setAttribute('report', $report);
            $attendance->setAttribute('appointments', $appointments);
            return $attendance;
        });

        return response()->json($attendances);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
