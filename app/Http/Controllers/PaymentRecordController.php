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

        return response()->json($query->get());
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

        $payment->delete();

        return response()->json(null, 204);
    }
}
