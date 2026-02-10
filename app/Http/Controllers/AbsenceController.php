<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AbsenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\Absence::with('user', 'approver');

        if (!auth()->user()->isMaster() && !auth()->user()->isOwner()) {
            $query->where('user_id', auth()->id());
        } else if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->where(function ($q) use ($request) {
                $q->whereBetween('start_date', [$request->start_date, $request->end_date])
                  ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                  ->orWhere(function ($sq) use ($request) {
                      $sq->where('start_date', '<=', $request->start_date)
                         ->where('end_date', '>=', $request->end_date);
                  });
            });
        }

        return response()->json($query->orderBy('start_date', 'desc')->get());
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
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $absence = \App\Models\Absence::create([
            ...$validated,
            'approved_by' => auth()->id(),
        ]);

        return response()->json($absence, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, \App\Models\Absence $absence)
    {
        if (!auth()->user()->isMaster() && !auth()->user()->isOwner()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|required|date|after_or_equal:start_date',
            'type' => 'sometimes|required|string',
            'notes' => 'nullable|string',
        ]);

        $absence->update($validated);

        return response()->json($absence);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(\App\Models\Absence $absence)
    {
        if (!auth()->user()->isMaster() && !auth()->user()->isOwner()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $absence->delete();

        return response()->json(['message' => 'Absence deleted']);
    }
}
