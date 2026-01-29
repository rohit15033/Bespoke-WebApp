<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DailyReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $query = \App\Models\DailyReport::with('user');

        if (!auth()->user()->isMaster()) {
            $query->where('user_id', auth()->id());
        }

        $reports = $query->orderBy('report_date', 'desc')->paginate(15);
        return response()->json($reports);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validate([
            'content' => 'required|string',
            'report_date' => 'required|date',
        ]);

        $report = \App\Models\DailyReport::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'report_date' => $data['report_date'],
            ],
            [
                'content' => $data['content'],
            ]
        );

        return response()->json($report, 201);
    }
}
