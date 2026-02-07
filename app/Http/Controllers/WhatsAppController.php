<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;

class WhatsAppController extends Controller
{
    /**
     * Handle incoming WhatsApp webhooks.
     */
    public function webhook(Request $request)
    {
        // Challenge verification (GET request)
        if ($request->isMethod('get')) {
            $verifyToken = env('WHATSAPP_VERIFY_TOKEN', 'bespoke_app_verification_token');
            
            $mode = $request->query('hub_mode');
            $token = $request->query('hub_verify_token');
            $challenge = $request->query('hub_challenge');
            
            if ($mode && $token) {
                if ($mode === 'subscribe' && $token === $verifyToken) {
                    return response($challenge, 200);
                }
                return response()->json([], 403);
            }
            return response()->json([], 400);
        }

        // Handle POST events (Messages)
        try {
            $body = $request->all();
            
            // Basic structure check for WhatsApp Cloud API
            if (isset($body['object']) && $body['object'] === 'whatsapp_business_account') {
                foreach ($body['entry'] as $entry) {
                    foreach ($entry['changes'] as $change) {
                        if ($change['field'] === 'messages') {
                            $value = $change['value'];
                            
                            if (isset($value['messages']) && !empty($value['messages'])) {
                                $message = $value['messages'][0];
                                $from = $message['from']; // Senders phone number
                                $text = isset($message['text']) ? $message['text']['body'] : '';
                                
                                // Normalize phone number (remove specific prefixes if needed, but usually match exact)
                                $phone = '+' . $from; 
                                
                                // Find or Create Customer
                                $customer = Customer::where('phone', $from)
                                    ->orWhere('phone', $phone)
                                    ->first();
                                
                                if (!$customer) {
                                    // New Lead!
                                    // Try to determine source from entry point or default
                                    $source = 'Whatsapp Direct'; // Default
                                    
                                    // Create generic name from phone if profile name not available
                                    $name = isset($value['contacts'][0]['profile']['name']) 
                                        ? $value['contacts'][0]['profile']['name'] 
                                        : 'WA User ' . substr($from, -4);
                                        
                                    $customer = Customer::create([
                                        'name' => $name,
                                        'phone' => $from, // Store raw format from WA
                                        'source' => $source,
                                        'source_meta' => $message, // Store initial metadata
                                        'first_whatsapp_interaction_at' => now(),
                                    ]);
                                    
                                    Log::info("New Lead Created via WhatsApp: {$customer->name} ({$customer->phone})");
                                } else {
                                    // Existing Customer
                                    if (!$customer->first_whatsapp_interaction_at) {
                                        $customer->update(['first_whatsapp_interaction_at' => now()]);
                                    }
                                    Log::info("Incoming WhatsApp from existing customer: {$customer->name}");
                                }
                            }
                        }
                    }
                }
                return response()->json(['status' => 'success'], 200);
            }
            
            return response()->json([], 404);
            
        } catch (\Exception $e) {
            Log::error('WhatsApp Webhook Error: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }
}
