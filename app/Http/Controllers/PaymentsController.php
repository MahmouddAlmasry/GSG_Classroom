<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Services\Payments\StripePayment;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Stripe\Charge;
use Stripe\StripeClient;

class PaymentsController extends Controller
{
    public function create(StripePayment $stripe, Subscription $subscription)
    {
        return $stripe->createCheckoutSession($subscription);
    }

    public function store(Request $request)
    {
        $subscription = Subscription::findOrFail($request->subscription_id);

        $stripe = new StripeClient(config('services.stripe.secret_key'));

        try {
            // Create a PaymentIntent with amount and currency
            $paymentIntent = $stripe->paymentIntents->create([
                'amount' => $subscription->price * 100,
                'currency' => 'usd',
                // In the latest version of the API, specifying the `automatic_payment_methods` parameter is optional because Stripe enables its functionality by default.
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            return [
                'clientSecret' => $paymentIntent->client_secret,
            ];
        } catch (Error $e) {
            return Response::json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function success(Request $request)
    {
        return view('payment.success');
    }

    public function cancel()
    {
        return view('payment.cancelled');
    }
}
