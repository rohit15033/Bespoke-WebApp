<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('name')->paginate(20);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20', // Allow flexible phone formats
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        // Check for duplicates roughly? Or just allow creation.
        // Let's assume the frontend helps avoid duplicates, but backend allows for same name.
        // But maybe unique phone? For now, standard create.

        $customer = Customer::create($validated);

        return response()->json($customer, 201);
    }

    public function show(Customer $customer)
    {
        return $customer->load(['appointments', 'orders']);
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'string|max:255',
            'phone' => 'string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $customer->update($validated);

        return response()->json($customer);
    }

    public function analyticsStatus()
    {
        // Define "New Customer" based on appointment purpose
        $newCustomerQuery = \App\Models\Appointments::where('purpose', 'new_customer');

        $stats = [
            'total_new' => (clone $newCustomerQuery)->count(),
            'deal' => (clone $newCustomerQuery)->where('result', 'deal')->count(),
            'no_deal' => (clone $newCustomerQuery)->where('result', 'no_deal')->count(),
            'potential' => (clone $newCustomerQuery)->where('result', 'potential')->count(),
            'pending' => (clone $newCustomerQuery)->whereNull('result')->count(),
        ];

        // Also get the list of customers for each status to display in the dashboard
        $lists = [
            'deal' => (clone $newCustomerQuery)->where('result', 'deal')->with('customer')->get(),
            'no_deal' => (clone $newCustomerQuery)->where('result', 'no_deal')->with('customer')->get(),
            'potential' => (clone $newCustomerQuery)->where('result', 'potential')->with('customer')->get(),
            'pending' => (clone $newCustomerQuery)->whereNull('result')->with('customer')->get(),
        ];

        return response()->json([
            'stats' => $stats,
            'lists' => $lists,
        ]);
    }

    public function history(Customer $customer)
    {
        $appointments = $customer->appointments()->orderBy('at', 'desc')->get();
        $orders = $customer->orders()->orderBy('created_at', 'desc')->get();

        return response()->json([
            'customer' => $customer,
            'appointments' => $appointments,
            'orders' => $orders,
        ]);
    }

    public function indexWithStatus()
    {
        // 1. Converted Customers (Deals) - Anyone with an order
        $dealtCustomers = Customer::has('orders')->with(['appointments' => function($q) {
            $q->orderBy('at', 'desc');
        }, 'orders' => function($q) {
            $q->orderBy('event_date', 'desc');
        }])->get();

        // 2. The rest (Leads)
        $leads = Customer::doesntHave('orders')->with(['appointments' => function($q) {
            $q->orderBy('at', 'desc');
        }])->get();

        $potential = collect();
        $noDeal = collect();
        $pending = collect();

        foreach ($leads as $lead) {
            $latestResult = $lead->appointments->first()?->result;
            
            if ($latestResult === 'potential') {
                $potential->push($lead);
            } elseif ($latestResult === 'no_deal') {
                $noDeal->push($lead);
            } else {
                // If result is null or something else (like fit_complete but no order yet?)
                $pending->push($lead);
            }
        }

        $stats = [
            'total_customers' => Customer::count(),
            'deal' => $dealtCustomers->count(),
            'no_deal' => $noDeal->count(),
            'potential' => $potential->count(),
            'pending' => $pending->count(),
        ];

        $lists = [
            'deal' => $dealtCustomers->map(fn($c) => ['customer' => $c, 'at' => $c->orders->first()?->created_at, 'result_notes' => 'Has Order']),
            'no_deal' => $noDeal->map(fn($c) => ['customer' => $c, 'at' => $c->appointments->first()?->at, 'result_notes' => $c->appointments->first()?->result_notes]),
            'potential' => $potential->map(fn($c) => ['customer' => $c, 'at' => $c->appointments->first()?->at, 'result_notes' => $c->appointments->first()?->result_notes]),
            'pending' => $pending->map(fn($c) => ['customer' => $c, 'at' => $c->appointments->first()?->at, 'result_notes' => $c->appointments->first()?->result_notes]),
        ];

        $allClients = Customer::with(['appointments' => function($q) {
            $q->orderBy('at', 'desc');
        }, 'orders' => function($q) {
            $q->orderBy('event_date', 'desc');
        }])->get()->map(function($c) {
            return [
                'id' => $c->id,
                'name' => $c->name,
                'phone' => $c->phone,
                'latest_appointment' => $c->appointments->first(),
                'latest_order' => $c->orders->first(),
            ];
        });

        return response()->json([
            'stats' => $stats,
            'lists' => $lists,
            'clients' => $allClients
        ]);
    }
}
