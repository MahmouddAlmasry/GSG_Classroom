<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Stripe\StripeClient;

class StripeController extends Controller
{
    public function __invoke(Request $request, StripeClient $stripe)
    {
        // This is your Stripe CLI webhook secret for testing your endpoint locally.
        $endpoint_secret = 'whsec_62715d771f4e0c0f1293cf44e2b83f5a6f9c7fc25b3fd7213e0fd8f5fc6ebafc';

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return response('', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response('', 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                Payment::where('gateway_reference_id', $session->id)->update([
                    'gateway_reference_id' => $session->payment_intent,
                ]);
                break;
            case 'payment_intent.amount_capturable_updated':
                $paymentIntent = $event->data->object;
                break;
            case 'payment_intent.canceled':
                $paymentIntent = $event->data->object;
                //Delete Subscription
                break;
            case 'payment_intent.created':
                $paymentIntent = $event->data->object;
                break;
            case 'payment_intent.partially_funded':
                $paymentIntent = $event->data->object;
                break;
            case 'payment_intent.payment_failed':
                $paymentIntent = $event->data->object;
                break;
            case 'payment_intent.processing':
                $paymentIntent = $event->data->object;
                break;
            case 'payment_intent.requires_action':
                $paymentIntent = $event->data->object;
                break;
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                $payment = Payment::where('gateway_reference_id', $paymentIntent->id)->first();
                $payment->forceFill(['status' => 'completed'])->save();

                $subscription = Subscription::where('id', $payment->subscription->id)->first();
                $subscription->update([
                    'status' => 'actvie',
                    'expires_at' => now()->addMonths(3),
                ]);
                break;
            default:
                echo 'Received unknown event type ' . $event->type;
                break;
        }

        http_response_code(200);
    }
}
