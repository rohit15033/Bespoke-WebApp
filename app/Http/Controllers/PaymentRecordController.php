<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PaymentRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PaymentRecord::with('order')->orderBy('payment_date', 'desc');

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->whereHas('order', function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('start_date')) {
            $query->where('payment_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('payment_date', '<=', $request->end_date);
        }

        $startDate = $request->input('start_date', \Carbon\Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', \Carbon\Carbon::now()->endOfMonth()->toDateString());

        $payments = $query->get();

        // Calculate New Customers for this window
        $newCustomers = PaymentRecord::select('order_id', \Illuminate\Support\Facades\DB::raw('MIN(payment_date) as first_payment_date'))
            ->groupBy('order_id')
            ->havingBetween('first_payment_date', [$startDate, $endDate])
            ->get()
            ->count();

        return response()->json([
            'payments' => $payments,
            'stats' => [
                'total_earnings' => $payments->sum('amount'),
                'total_transactions' => $payments->count(),
                'new_customers' => $newCustomers
            ]
        ]);
    }

    /**
     * Store a newly created payment record in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string',
            'proof_image' => 'required|image|max:2048', // Max 2MB, now required
            'note' => 'nullable|string',
        ]);

        $path = null;
        if ($request->hasFile('proof_image')) {
            $path = $request->file('proof_image')->store('payment_proofs', 'public');
        }

        $payment = PaymentRecord::create([
            'order_id' => $validated['order_id'],
            'amount' => $validated['amount'],
            'payment_date' => $validated['payment_date'],
            'payment_method' => $validated['payment_method'],
            'proof_image_path' => $path,
            'note' => $validated['note'] ?? null,
        ]);

        // Sync order status
        $payment->order->syncStatus();

        return response()->json($payment, 201);
    }

    /**
     * Remove the specified payment record from storage.
     */
    public function destroy($id)
    {
        $payment = PaymentRecord::findOrFail($id);
        
        // Optionally delete the image file if it exists
        if ($payment->proof_image_path) {
            Storage::disk('public')->delete($payment->proof_image_path);
        }

        $order = $payment->order;
        $payment->delete();

        // Sync order status
        $order->syncStatus();

        return response()->json(null, 204);
    }
}
