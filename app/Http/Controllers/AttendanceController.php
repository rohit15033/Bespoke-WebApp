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

            // Calculate Late Status
            if ($attendance->user && $attendance->user->work_start_time) {
                $scheduledStart = Carbon::parse($attendance->date->format('Y-m-d') . ' ' . $attendance->user->work_start_time);
                // Add a small buffer (e.g., 1 minute) or strict? Let's be strict but rely on seconds.
                if ($attendance->clock_in->gt($scheduledStart)) {
                    $attendance->setAttribute('is_late', true);
                    $attendance->setAttribute('late_minutes', $attendance->clock_in->diffInMinutes($scheduledStart));
                } else {
                    $attendance->setAttribute('is_late', false);
                    $attendance->setAttribute('late_minutes', 0);
                }
                $attendance->setAttribute('scheduled_start', $attendance->user->work_start_time);
            }

            return $attendance;
        });

        return response()->json($attendances);
    }

    public function summary(Request $request): \Illuminate\Http\JsonResponse
    {
        if (!auth()->user()->isMaster() && auth()->user()->role !== 'owner') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $startDate = $request->query('start_date', Carbon::now('Asia/Jakarta')->startOfMonth()->toDateString());
        $endDate = $request->query('end_date', Carbon::now('Asia/Jakarta')->endOfMonth()->toDateString());

        $users = \App\Models\User::where('role', '!=', 'owner')->get();
        $summary = [];

        foreach ($users as $user) {
            // Present and Late Stats
            $attendances = \App\Models\Attendance::where('user_id', $user->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->get();

            $presentCount = $attendances->count();
            $lateCount = 0;

            if ($user->work_start_time) {
                foreach ($attendances as $attendance) {
                    $scheduledStart = Carbon::parse($attendance->date->format('Y-m-d') . ' ' . $user->work_start_time);
                    if ($attendance->clock_in->gt($scheduledStart)) {
                        $lateCount++;
                    }
                }
            }

            // Absence Stats (Sick, Holiday)
            $absences = \App\Models\Absence::where('user_id', $user->id)
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('start_date', [$startDate, $endDate])
                        ->orWhereBetween('end_date', [$startDate, $endDate])
                        ->orWhere(function ($q) use ($startDate, $endDate) {
                            $q->where('start_date', '<=', $startDate)
                              ->where('end_date', '>=', $endDate);
                        });
                })
                ->get();

            $sickCount = 0;
            $holidayCount = 0;

            foreach ($absences as $absence) {
                $start = Carbon::parse($absence->start_date)->max(Carbon::parse($startDate));
                $end = Carbon::parse($absence->end_date)->min(Carbon::parse($endDate));
                $days = $start->diffInDays($end) + 1;

                if ($absence->type === 'sick') {
                    $sickCount += $days;
                } elseif ($absence->type === 'holiday') {
                    $holidayCount += $days;
                }
            }

            $summary[] = [
                'user_id' => $user->id,
                'name' => $user->name,
                'role' => $user->role,
                'stats' => [
                    'present' => $presentCount,
                    'late' => $lateCount,
                    'sick' => $sickCount,
                    'holiday' => $holidayCount,
                ]
            ];
        }

        return response()->json($summary);
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
        if (!auth()->user()->isMaster() && !auth()->user()->isOwner()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'clock_in' => 'required|date_format:H:i',
            'clock_out' => 'nullable|date_format:H:i|after:clock_in',
            'status' => 'required|string',
        ]);

        $date = $validated['date'];
        $clockIn = Carbon::parse("$date {$validated['clock_in']}");
        $clockOut = $validated['clock_out'] ? Carbon::parse("$date {$validated['clock_out']}") : null;

        $attendance = \App\Models\Attendance::create([
            'user_id' => $validated['user_id'],
            'date' => $validated['date'],
            'clock_in' => $clockIn,
            'clock_out' => $clockOut,
            'status' => $validated['status'],
        ]);

        return response()->json($attendance, 201);
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
        if (!auth()->user()->isMaster() && !auth()->user()->isOwner()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $attendance = \App\Models\Attendance::findOrFail($id);

        $validated = $request->validate([
            'date' => 'sometimes|required|date',
            'clock_in' => 'sometimes|required|date_format:H:i',
            'clock_out' => 'nullable|date_format:H:i',
            'status' => 'sometimes|required|string',
        ]);

        $date = $validated['date'] ?? $attendance->date->format('Y-m-d');
        
        if (isset($validated['clock_in'])) {
            $attendance->clock_in = Carbon::parse("$date {$validated['clock_in']}");
        }
        
        if (array_key_exists('clock_out', $validated)) {
             $attendance->clock_out = $validated['clock_out'] ? Carbon::parse("$date {$validated['clock_out']}") : null;
        }
        
        if (isset($validated['date'])) {
            $attendance->date = $validated['date'];
        }

        if (isset($validated['status'])) {
            $attendance->status = $validated['status'];
        }

        $attendance->save();

        return response()->json($attendance);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
