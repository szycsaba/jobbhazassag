<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use App\Models\Subscriber;

class ThankYouController extends Controller
{
    public function show(string $session_id)
    {
        $headers = [
            'X-Frame-Options'        => 'SAMEORIGIN',
            'X-Content-Type-Options' => 'nosniff',
            'Referrer-Policy'        => 'no-referrer',
            'Cache-Control'          => 'no-store',
        ];

        $sessionId = $session_id;
        if (!$sessionId) {
            return redirect('/')->withHeaders($headers);
        }

        $paymentStatus = null;
        $customerName  = null;
        $customerEmail = null;
        $errorMsg      = null;

        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            $session = StripeSession::retrieve($sessionId, []);
            $paymentStatus = $session->payment_status ?? null;
            $customerName  = $session->customer_details['name']  ?? null;
            $customerEmail = $session->customer_details['email'] ?? null;
            
            // If payment is successful, ensure subscriber record exists (fallback if webhook failed)
            if ($paymentStatus === 'paid') {
                $this->ensureSubscriberExists($session);
            }
            
            // Payment successful - show thank you page first, then redirect via JavaScript
        } catch (\Throwable $e) {
            Log::error('Stripe session retrieve failed', [
                'session_id' => $sessionId ?? null,
                'msg' => $e->getMessage(),
            ]);
            $errorMsg = 'Nem sikerült beolvasni a fizetési adatokat.';
        }

        return response()
            ->view('public.thank-you', compact(
                'sessionId','paymentStatus','customerName','customerEmail','errorMsg'
            ))
            ->withHeaders($headers);
    }

    /**
     * Ensure subscriber record exists as fallback if webhook failed
     */
    private function ensureSubscriberExists($session): void
    {
        try {
            // Check if subscriber already exists by session ID or customer ID
            $existingSubscriber = null;
            if ($session->customer) {
                $existingSubscriber = Subscriber::where('stripe_customer_id', $session->customer)->first();
            } else {
                $existingSubscriber = Subscriber::where('stripe_session_id', $session->id)->first();
            }
            
            if (!$existingSubscriber) {
                Log::info('Creating subscriber record from ThankYou page (webhook fallback)', [
                    'session_id' => $session->id,
                    'customer_id' => $session->customer,
                    'customer_email' => $session->customer_email
                ]);

                // Extract email from customer_details if customer_email is null
                $email = $session->customer_email ?? $session->customer_details->email ?? null;
                
                // Skip if no email available
                if (!$email) {
                    Log::warning('Skipping subscriber creation from ThankYou page - no email available', [
                        'session_id' => $session->id,
                        'customer_email' => $session->customer_email,
                        'customer_details' => $session->customer_details ? (array) $session->customer_details : null
                    ]);
                    return;
                }

                $subscriberData = [
                    'stripe_customer_id' => $session->customer ?: 'session_' . $session->id,
                    'email' => $email,
                    'name' => $session->customer_details->name ?? null,
                    'stripe_session_id' => $session->id,
                    'payment_status' => $session->payment_status,
                    'amount_total' => $session->amount_total,
                    'currency' => $session->currency,
                    'last_payment_at' => now(),
                    'metadata' => $session->metadata ? (array) $session->metadata : null,
                    'customer_details' => $session->customer_details ? (array) $session->customer_details : null,
                ];

                Subscriber::create($subscriberData);
                
                Log::info('Subscriber created successfully from ThankYou page', [
                    'session_id' => $session->id,
                    'customer_id' => $session->customer ?: 'session_' . $session->id
                ]);
            } else {
                Log::info('Subscriber already exists, updating payment status', [
                    'session_id' => $session->id,
                    'customer_id' => $session->customer,
                    'subscriber_id' => $existingSubscriber->id
                ]);

                // Update payment status if needed
                $existingSubscriber->update([
                    'payment_status' => $session->payment_status,
                    'last_payment_at' => now(),
                    'stripe_session_id' => $session->id,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error ensuring subscriber exists from ThankYou page', [
                'session_id' => $session->id,
                'customer_id' => $session->customer,
                'error' => $e->getMessage()
            ]);
        }
    }
}
