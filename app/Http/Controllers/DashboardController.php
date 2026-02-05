<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PaymentRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function stats(Request $request)
    {
        // Default to current month if not provided
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        // 1. Earnings: Total payments in the date range
        $earnings = PaymentRecord::whereBetween('payment_date', [$startDate, $endDate])
            ->sum('amount');

        // 2. New Customers: Count of orders where the FIRST payment date is within the range
        // This indicates a "conversion" or acquisition during this period.
        $newCustomers = PaymentRecord::select('order_id', DB::raw('MIN(payment_date) as first_payment_date'))
            ->groupBy('order_id')
            ->havingBetween('first_payment_date', [$startDate, $endDate])
            ->get()
            ->count();

        // 3. Active Orders: Orders in 'confirmed' status
        // We calculate this dynamically to ensure accuracy for orders that might have passed their event date
        $activeOrders = Order::where(function($q) {
                // Not Draft (has payments)
                $q->whereHas('payments')
                // Not yet Completed (either total < final OR event hasn't passed)
                ->where(function($sq) {
                    $sq->whereRaw('(select coalesce(sum(amount), 0) from payment_records where order_id = orders.id) < final_price')
                       ->orWhere('event_date', '>', \Carbon\Carbon::now()->toDateString());
                });
            })
            ->where('status', '!=', 'cancelled')
            ->count();

        // 4. Upcoming Events: Next 5 orders with event_date >= today
        // Independent of filter range (usually just want "what's next")
        $upcomingEvents = Order::where('event_date', '>=', Carbon::now()->toDateString())
            ->orderBy('event_date', 'asc')
            ->limit(5)
            ->get(['id', 'order_number', 'customer_name', 'event_date', 'event_place', 'status']);

        return response()->json([
            'meta' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
            'stats' => [
                'earnings' => (float) $earnings,
                'new_customers' => $newCustomers,
                'active_orders' => $activeOrders,
            ],
            'upcoming_events' => $upcomingEvents,
        ]);
    }
}
