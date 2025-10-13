<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;
use Stripe\Checkout\Session;

class StripeWebhookController extends Controller
{
    public function handle(Request $request): Response
    {
        $payload = $request->getContent();
        $sig     = $request->header('Stripe-Signature');
        $secret  = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sig, $secret);
        } catch (\Throwable $e) {
            return response('Signature verification failed', 400);
        }

        switch ($event->type) {
            case 'checkout.session.completed':
                $this->handleCheckoutSessionCompleted($event->data->object);
                break;

            case 'payment_intent.succeeded':
                $this->handlePaymentIntentSucceeded($event->data->object);
                break;

            case 'customer.subscription.created':
                $this->handleSubscriptionCreated($event->data->object);
                break;

            case 'customer.subscription.updated':
                $this->handleSubscriptionUpdated($event->data->object);
                break;

            case 'customer.subscription.deleted':
                $this->handleSubscriptionDeleted($event->data->object);
                break;

            case 'invoice.payment_succeeded':
                $this->handleInvoicePaymentSucceeded($event->data->object);
                break;

            case 'invoice.payment_failed':
                $this->handleInvoicePaymentFailed($event->data->object);
                break;

            default:
                Log::channel('stripe')->info('Unhandled Stripe webhook event: ' . $event->type);
                break;
        }

        return response('OK', 200);
    }

    /**
     * Handle successful checkout session completion
     */
    private function handleCheckoutSessionCompleted($session): void
    {
        try {
            Log::channel('stripe')->info('Processing checkout.session.completed', [
                'session_id' => $session->id,
                'customer' => $session->customer,
                'customer_email' => $session->customer_email,
                'payment_status' => $session->payment_status
            ]);

            // Handle cases where there's no customer ID (payment links, guest checkout)
            if (!$session->customer) {
                Log::channel('stripe')->info('Processing checkout.session.completed without customer ID - using session data', [
                    'session_id' => $session->id,
                    'payment_status' => $session->payment_status,
                    'customer_email' => $session->customer_email
                ]);

                // Extract email from customer_details if customer_email is null
                $email = $session->customer_email ?? $session->customer_details->email ?? null;
                
                // Skip if no email available
                if (!$email) {
                    Log::channel('stripe')->warning('Skipping checkout.session.completed - no email available', [
                        'session_id' => $session->id,
                        'customer_email' => $session->customer_email,
                        'customer_details' => $session->customer_details ? (array) $session->customer_details : null
                    ]);
                    return;
                }

                // Create subscriber using session ID as unique identifier since no customer ID
                $subscriberData = [
                    'stripe_customer_id' => 'session_' . $session->id, // Use session ID as fallback
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

                Subscriber::updateOrCreate(
                    ['stripe_session_id' => $session->id],
                    $subscriberData
                );

                Log::info('Subscriber created using session ID as fallback', ['session_id' => $session->id]);
                return;
            }

            $subscriberData = [
                'stripe_customer_id' => $session->customer,
                'email' => $session->customer_email,
                'name' => $session->customer_details->name ?? null,
                'stripe_session_id' => $session->id,
                'payment_status' => $session->payment_status,
                'amount_total' => $session->amount_total,
                'currency' => $session->currency,
                'last_payment_at' => now(),
                'metadata' => $session->metadata ? (array) $session->metadata : null,
                'customer_details' => $session->customer_details ? (array) $session->customer_details : null,
            ];

            // Update or create subscriber
            Subscriber::updateOrCreate(
                ['stripe_customer_id' => $session->customer],
                $subscriberData
            );

            Log::channel('stripe')->info('Subscriber created/updated successfully', ['customer_id' => $session->customer]);
        } catch (\Exception $e) {
            Log::channel('stripe')->error('Error processing checkout.session.completed', [
                'session_id' => $session->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Handle successful payment intent
     */
    private function handlePaymentIntentSucceeded($paymentIntent): void
    {
        try {
            Log::channel('stripe')->info('Processing payment_intent.succeeded', [
                'payment_intent_id' => $paymentIntent->id,
                'customer' => $paymentIntent->customer,
                'amount' => $paymentIntent->amount,
                'currency' => $paymentIntent->currency
            ]);

            // Update or create subscriber with payment intent ID
            if ($paymentIntent->customer) {
                $subscriberData = [
                    'stripe_customer_id' => $paymentIntent->customer,
                    'stripe_payment_intent_id' => $paymentIntent->id,
                    'payment_status' => 'paid',
                    'amount_total' => $paymentIntent->amount,
                    'currency' => $paymentIntent->currency,
                    'last_payment_at' => now(),
                ];

                // Try to get customer details from Stripe
                try {
                    $customer = \Stripe\Customer::retrieve($paymentIntent->customer);
                    if ($customer) {
                        $subscriberData['email'] = $customer->email;
                        $subscriberData['name'] = $customer->name;
                    }
                } catch (\Exception $customerError) {
                    Log::channel('stripe')->warning('Could not retrieve customer details', [
                        'customer_id' => $paymentIntent->customer,
                        'error' => $customerError->getMessage()
                    ]);
                }

                Subscriber::updateOrCreate(
                    ['stripe_customer_id' => $paymentIntent->customer],
                    $subscriberData
                );

                Log::channel('stripe')->info('Subscriber created/updated from payment_intent.succeeded', [
                    'customer_id' => $paymentIntent->customer,
                    'payment_intent_id' => $paymentIntent->id
                ]);
            }
        } catch (\Exception $e) {
            Log::channel('stripe')->error('Error processing payment_intent.succeeded', [
                'payment_intent_id' => $paymentIntent->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Handle subscription creation
     */
    private function handleSubscriptionCreated($subscription): void
    {
        try {
            Log::channel('stripe')->info('Processing customer.subscription.created', ['subscription_id' => $subscription->id]);

            if ($subscription->customer) {
                Subscriber::where('stripe_customer_id', $subscription->customer)
                    ->update([
                        'stripe_subscription_id' => $subscription->id,
                        'status' => $subscription->status,
                        'subscription_started_at' => now(),
                        'subscription_ends_at' => $subscription->current_period_end ? 
                            \Carbon\Carbon::createFromTimestamp($subscription->current_period_end) : null,
                        'next_payment_at' => $subscription->current_period_end ? 
                            \Carbon\Carbon::createFromTimestamp($subscription->current_period_end) : null,
                    ]);
            }
        } catch (\Exception $e) {
            Log::error('Error processing customer.subscription.created', [
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Handle subscription updates
     */
    private function handleSubscriptionUpdated($subscription): void
    {
        try {
            Log::channel('stripe')->info('Processing customer.subscription.updated', ['subscription_id' => $subscription->id]);

            if ($subscription->customer) {
                Subscriber::where('stripe_customer_id', $subscription->customer)
                    ->update([
                        'status' => $subscription->status,
                        'subscription_ends_at' => $subscription->current_period_end ? 
                            \Carbon\Carbon::createFromTimestamp($subscription->current_period_end) : null,
                        'next_payment_at' => $subscription->current_period_end ? 
                            \Carbon\Carbon::createFromTimestamp($subscription->current_period_end) : null,
                    ]);
            }
        } catch (\Exception $e) {
            Log::error('Error processing customer.subscription.updated', [
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Handle subscription deletion/cancellation
     */
    private function handleSubscriptionDeleted($subscription): void
    {
        try {
            Log::channel('stripe')->info('Processing customer.subscription.deleted', ['subscription_id' => $subscription->id]);

            if ($subscription->customer) {
                Subscriber::where('stripe_customer_id', $subscription->customer)
                    ->update([
                        'status' => 'cancelled',
                        'subscription_ends_at' => now(),
                        'next_payment_at' => null,
                    ]);
            }
        } catch (\Exception $e) {
            Log::error('Error processing customer.subscription.deleted', [
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Handle successful invoice payment
     */
    private function handleInvoicePaymentSucceeded($invoice): void
    {
        try {
            Log::channel('stripe')->info('Processing invoice.payment_succeeded', ['invoice_id' => $invoice->id]);

            if ($invoice->customer) {
                Subscriber::where('stripe_customer_id', $invoice->customer)
                    ->update([
                        'payment_status' => 'paid',
                        'last_payment_at' => now(),
                        'amount_total' => $invoice->amount_paid,
                        'currency' => $invoice->currency,
                    ]);
            }
        } catch (\Exception $e) {
            Log::channel('stripe')->error('Error processing invoice.payment_succeeded', [
                'invoice_id' => $invoice->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Handle failed invoice payment
     */
    private function handleInvoicePaymentFailed($invoice): void
    {
        try {
            Log::channel('stripe')->info('Processing invoice.payment_failed', ['invoice_id' => $invoice->id]);

            if ($invoice->customer) {
                Subscriber::where('stripe_customer_id', $invoice->customer)
                    ->update([
                        'payment_status' => 'unpaid',
                        'status' => 'past_due',
                    ]);
            }
        } catch (\Exception $e) {
            Log::channel('stripe')->error('Error processing invoice.payment_failed', [
                'invoice_id' => $invoice->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
