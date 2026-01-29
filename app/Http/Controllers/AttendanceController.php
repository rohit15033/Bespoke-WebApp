<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function status(): \Illuminate\Http\JsonResponse
    {
        // First try to find a shift for today
        $attendance = \App\Models\Attendance::where('user_id', auth()->id())
            ->where('date', date('Y-m-d'))
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

        $attendance = \App\Models\Attendance::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'date' => date('Y-m-d'),
            ],
            [
                'clock_in' => now(),
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

        $attendance->update(['clock_out' => now()]);

        return response()->json($attendance);
    }

    public function history(Request $request): \Illuminate\Http\JsonResponse
    {
        $query = \App\Models\Attendance::query();
        
        if (!auth()->user()->isMaster()) {
            $query->where('user_id', auth()->id());
        } else if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $attendances = $query->orderBy('date', 'desc')->paginate(15);

        // For each attendance, try to find the corresponding daily report
        $attendances->getCollection()->transform(function ($attendance) {
            $report = \App\Models\DailyReport::where('user_id', $attendance->user_id)
                ->where('report_date', $attendance->date)
                ->first();
            
            $attendance->report = $report;
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
