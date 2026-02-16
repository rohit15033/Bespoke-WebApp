<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeadIntent;
use App\Models\Customer;
use App\Models\Appointments;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;

class LeadTrackingController extends Controller
{
    /**
     * Get the WhatsApp message template (public).
     */
    public function getTemplate()
    {
        $template = Setting::getValue('whatsapp_template', '');
        return response()->json(['template' => $template]);
    }

    /**
     * Update the WhatsApp message template (admin only).
     */
    public function updateTemplate(Request $request)
    {
        $request->validate(['template' => 'required|string|max:2000']);
        Setting::setValue('whatsapp_template', $request->template);
        return response()->json(['status' => 'success', 'message' => 'Template updated.']);
    }
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

    /**
     * Capture lead details (name & phone) from the public form.
     * Updates the anonymous "Visitor" placeholder with real information.
     */
    public function captureLead(Request $request)
    {
        $request->validate([
            'ref_id' => 'required|integer|exists:lead_intents,id',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
        ]);

        $intent = LeadIntent::findOrFail($request->ref_id);

        // Find the placeholder customer created by logIntent
        $placeholderCustomer = Customer::where('lead_intent_id', $intent->id)->first();

        if (!$placeholderCustomer) {
            return response()->json(['status' => 'error', 'message' => 'Lead not found.'], 404);
        }

        // Check if a customer with this phone already exists
        $existingCustomer = Customer::where('phone', $request->phone)
            ->where('id', '!=', $placeholderCustomer->id)
            ->first();

        if ($existingCustomer) {
            // Merge: Re-link intent to existing customer, delete placeholder
            $existingCustomer->update([
                'lead_intent_id' => $intent->id,
                'first_whatsapp_interaction_at' => $existingCustomer->first_whatsapp_interaction_at ?? Carbon::now(),
            ]);

            // Move any appointments from placeholder to existing
            Appointments::where('customer_id', $placeholderCustomer->id)
                ->update(['customer_id' => $existingCustomer->id]);

            $placeholderCustomer->delete();

            return response()->json([
                'status' => 'merged',
                'message' => 'Lead linked to existing customer.',
                'customer_id' => $existingCustomer->id,
                'whatsapp_number' => config('services.whatsapp.number', '6285190054707'),
            ]);
        }

        // Update the placeholder with real details
        $placeholderCustomer->update([
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        return response()->json([
            'status' => 'captured',
            'message' => 'Lead details saved.',
            'customer_id' => $placeholderCustomer->id,
            'whatsapp_number' => config('services.whatsapp.number', '6285190054707'),
        ]);
    }
}
