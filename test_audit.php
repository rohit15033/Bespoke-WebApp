<?php

use App\Models\User;
use App\Models\Order;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

// Assuming this script is run via 'php artisan tinker test_audit.php'
// or we need to bootstrap Laravel if run via 'php test_audit.php'

$user = User::first();
if (!$user) {
    echo "No user found\n";
    exit;
}

Auth::login($user);
echo "Logged in as: " . $user->email . "\n";

$initialCount = AuditLog::count();
echo "Initial logs count: $initialCount\n";

echo "Creating order...\n";
$order = Order::create([
    'order_number' => 'AUDIT-TEST-' . time(),
    'status' => 'draft',
    'customer_name' => 'Audit Test User',
    'customer_address' => 'Audit Test Address',
    'customer_phone_number' => '000000',
    'event_place' => 'Audit Test Place',
    'event_date' => now(),
    'total_price' => 500,
    'total_discount' => 0,
    'final_price' => 500
]);

echo "Updating order...\n";
$order->update(['status' => 'confirmed']);

echo "Deleting order...\n";
$order->delete();

$finalCount = AuditLog::count();
echo "Final logs count: $finalCount\n";
echo "Logs created during test: " . ($finalCount - $initialCount) . "\n";

$latestLogs = AuditLog::latest()->take(3)->get();
foreach ($latestLogs as $log) {
    echo "Action: {$log->action}, Module: {$log->module}, ID: {$log->module_id}\n";
}
