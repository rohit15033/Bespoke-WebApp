<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeadIntent;
use App\Models\Customer;
use App\Models\Appointments;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;

class LeadTrackingController extends Controller
{
    /**
     * Track the intent and redirect to WhatsApp.
     */
    public function contactRedirect(Request $request, $platform = 'whatsapp')
    {
        // 1. Log the intent
        $intent = LeadIntent::create([
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'platform' => $platform,
        ]);

        // 2. Automatic Ingestion: Create a placeholder Lead
        $customer = Customer::create([
            'name' => "Visitor #{$intent->id}",
            'phone' => 'Unknown',
            'source' => ucfirst($platform),
            'lead_intent_id' => $intent->id,
            'first_whatsapp_interaction_at' => Carbon::now(),
        ]);

        // 4. Redirect to the REAL WhatsApp number
        $whatsappNumber = config('services.whatsapp.number', '6285190054707');
        $message = urlencode("Hi Berkat Kebaya, I'm interested in your services! (Ref: #{$intent->id})");
        
        return redirect("https://wa.me/{$whatsappNumber}?text={$message}");
    }

    /**
     * Get a list of trackable redirect links.
     */
    public function getRedirectLinks()
    {
        $platforms = ['whatsapp', 'instagram', 'tiktok', 'facebook', 'other'];
        $links = array_map(function($platform) {
            return [
                'platform' => $platform,
                'url' => URL::to("/contact-{$platform}"),
                'label' => ucfirst($platform) . " Redirect"
            ];
        }, $platforms);

        // Add the generic one
        array_unshift($links, [
            'platform' => 'generic',
            'url' => URL::to("/contact"),
            'label' => 'Generic Redirect'
        ]);

        return response()->json($links);
    }

    /**
     * Log the intent from the frontend (for react-based redirects).
     */
    public function logIntent(Request $request)
    {
        $platform = $request->input('platform', 'whatsapp');

        // 1. Log the intent
        $intent = LeadIntent::create([
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'platform' => $platform,
        ]);

        // 2. Automatic Ingestion: Create a placeholder Lead
        Customer::create([
            'name' => "Visitor #{$intent->id}",
            'phone' => 'Unknown',
            'source' => ucfirst($platform),
            'lead_intent_id' => $intent->id,
            'first_whatsapp_interaction_at' => Carbon::now(),
        ]);

        // Return the ref ID so the frontend can append it to the message
        return response()->json([
            'status' => 'success',
            'ref_id' => $intent->id,
            'whatsapp_number' => config('services.whatsapp.number', '6285190054707')
        ]);
    }
}
