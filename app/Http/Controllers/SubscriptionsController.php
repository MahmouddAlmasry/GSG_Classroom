<?php

namespace App\Http\Controllers;

use App\Actions\CreateSubscription;
use App\Http\Requests\CreateSubscriptionRequest;
use App\Models\Plan;
use App\Models\Subscription;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class SubscriptionsController extends Controller
{
    public function store(CreateSubscriptionRequest $request, CreateSubscription $create)
    {
        $plan = Plan::findOrFail($request->post('plan_id'));
        $months = $request->post('period');
        try{

            $subscription = $create([
                'plan_id' => $plan->id,
                'user_id' => Auth::id(),
                'price' => $plan->price * $months,
                'expires_at' => now()->addMonth($months),
                'status' => 'pending'
            ]);

            return redirect()->route('checkout', $subscription->id);

        }catch(Throwable $e){
            return back()->with('error', $e->getMessage());
        }
    }
}
