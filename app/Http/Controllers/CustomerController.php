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

        if ($request->has('customer_type')) {
            if ($request->input('customer_type') === 'booked') {
                $query->has('orders');
            } elseif ($request->input('customer_type') === 'leads') {
                $query->doesntHave('orders');
            }
        }

        return $query->orderBy('name')->paginate(20);
    }

    public function store(Request $request)
    {
        // 1. Sanitize Phone First to check for existence
        $phone = $request->input('phone');
        
        // Normalize phone for lookup
        $normalizedPhone = preg_replace('/\D/', '', $phone);
        if (str_starts_with($normalizedPhone, '0')) {
            $strippedPhone = substr($normalizedPhone, 1);
        } elseif (str_starts_with($normalizedPhone, '62')) {
            $strippedPhone = substr($normalizedPhone, 2);
        } else {
            $strippedPhone = $normalizedPhone;
        }

        // Search for existing customer by phone permutations
        // We do this BEFORE validation to prevent "Phone taken" error
        $existingCustomer = Customer::where('phone', $phone)
            ->orWhere('phone', 'like', '%' . $strippedPhone)
            ->first();

        if ($existingCustomer) {
            // Identity Completion: If existing customer has no name or generic name, update it
            $name = $request->input('name');
            $updateData = [];
            
            // Only update name if the new name is "better" (not Unknown) and old name Is "Unknown" or "Visitor"
            if ($name && $name !== 'Unknown' && ($existingCustomer->name === 'Unknown' || str_contains($existingCustomer->name, 'Visitor #'))) {
                $updateData['name'] = $name;
            }
            
            if (!empty($updateData)) {
                $existingCustomer->update($updateData);
            }
            
            return response()->json($existingCustomer, 200);
        }

        // 2. If not found, proceed with strict validation and creation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => ['required', 'string', 'max:20', \Illuminate\Validation\Rule::unique('customers', 'phone')->whereNot('phone', 'Unknown')],
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
            'source' => 'nullable|string',
        ]);

        $customer = Customer::create($validated);

        return response()->json($customer, 201);
    }

    public function show(Customer $customer)
    {
        return $customer->load(['appointments', 'orders']);
    }

    public function update(Request $request, Customer $customer)
    {
        // Role-based check for "Edit Once" logic
        $user = $request->user();
        $isMaster = $user && ($user->role === 'master' || $user->role === 'owner');

        // If not master/owner, and phone is already set (not "Unknown"), 
        // prevent name/phone update.
        if (!$isMaster && $customer->phone !== 'Unknown') {
            if ($request->has('name') || $request->has('phone')) {
                if ($request->input('name') !== $customer->name || $request->input('phone') !== $customer->phone) {
                    return response()->json([
                        'message' => 'Only Master admins can update contact details once identity has been established.'
                    ], 403);
                }
            }
        }

        // Check for duplication / merge scenario
        if ($request->has('phone') && $request->phone !== $customer->phone && $request->phone !== 'Unknown') {
            $newPhone = $request->phone;
            
            // Check if ANY other customer has this phone
            $conflictCustomer = Customer::where('phone', $newPhone)
                ->where('id', '!=', $customer->id)
                ->first();

            if ($conflictCustomer) {
                // MERGE STRATEGY:
                // The user is trying to update 'Visitor A' to be 'John Doe' (who already exists).
                // We should move Visitor A's history to John Doe, then delete Visitor A.

                // 1. Reassign Appointments
                foreach ($customer->appointments as $appt) {
                    $appt->update(['customer_id' => $conflictCustomer->id]);
                }

                // 2. Reassign Orders (unlikely for leads, but safe to do)
                foreach ($customer->orders as $order) {
                    $order->update(['customer_id' => $conflictCustomer->id]);
                }

                // 3. Reassign Lead Intent (if target has none)
                if (!$conflictCustomer->lead_intent_id && $customer->lead_intent_id) {
                    $conflictCustomer->update(['lead_intent_id' => $customer->lead_intent_id]);
                }

                // 4. Update Target Name if needed (if target was also generic?)
                if ($request->has('name') && ($conflictCustomer->name === 'Unknown' || str_contains($conflictCustomer->name, 'Visitor #'))) {
                   $conflictCustomer->update(['name' => $request->input('name')]);
                }

                // 5. Delete the old 'Visitor' record
                $customer->delete();

                // 6. Return the 'Master' record
                return response()->json($conflictCustomer);
            }
        }

        // Standard Update (No conflict)
        $validated = $request->validate([
            'name' => 'string|max:255',
            'phone' => ['string', 'max:20', \Illuminate\Validation\Rule::unique('customers', 'phone')->ignore($customer->id)->whereNot('phone', 'Unknown')],
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
            'customer' => $customer->load('leadIntent'),
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
        }, 'leadIntent'])->get();

        // 2. The rest (Leads)
        $leads = Customer::doesntHave('orders')->with(['appointments' => function($q) {
            $q->orderBy('at', 'desc');
        }, 'leadIntent'])->get();

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
        }, 'leadIntent'])->get()->map(function($c) {
            // Absolute latest for activity tracking
            $absoluteLatest = $c->appointments->first();
            
            // INTERACTION FALLBACK: If no appointments, use tracking dates
            $interactionAt = $absoluteLatest ? $absoluteLatest->at : ($c->first_whatsapp_interaction_at ?: $c->created_at);

            // Identify the appointment that defines the lead's current status in the pipeline.
            // We prioritize the most recent appointment that actually HAS a result,
            // specifically for new_customer or consultation purposes.
            $statusDefining = $c->appointments->filter(function($a) {
                return !empty($a->result) && in_array($a->purpose, ['new_customer', 'consultation']);
            })->first();

            // If no sales interaction has a result yet, fallback to the absolute latest (which would be 'Pending')
            $displayAppointment = $statusDefining ?: $absoluteLatest;

            return [
                'id' => $c->id,
                'name' => $c->name,
                'phone' => $c->phone,
                'latest_appointment' => $displayAppointment,
                'actual_latest_at' => $interactionAt,
                'latest_order' => $c->orders->first(),
                'source' => $c->source,
                'lead_intent' => $c->leadIntent,
            ];
        })->sortByDesc('actual_latest_at')->values();

        return response()->json([
            'stats' => $stats,
            'lists' => $lists,
            'clients' => $allClients
        ]);
    }

    public function destroy(Request $request, Customer $customer)
    {
        $user = $request->user();
        if (!$user || !($user->isMaster() || $user->isOwner())) {
            return response()->json(['message' => 'Unauthorized. Only Master admins/Owners can delete records.'], 403);
        }

        // Safety: Prevent deletion if there are linked orders
        if ($customer->orders()->count() > 0) {
            return response()->json(['message' => 'Cannot delete customer with active orders.'], 422);
        }

        // Delete associated appointments and lead intent if necessary
        $customer->appointments()->delete();
        $customer->delete();

        return response()->json(['message' => 'Customer record deleted successfully.']);
    }
}
