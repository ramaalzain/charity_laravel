<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Charge;

class PaymentController extends Controller
{
    public function showPaymentForm()
    {
        return view('payment');
    }

    public function processPayment(Request $request)
    {
        // Debugging line
       
        // $stripeSecret = env('STRIPE_KEY');
        // if (!$stripeSecret) {
        //     return response()->json(['error' => 'Stripe secret key not set'], 500);
        // }
        // else return response()->json(['key' => $stripeSecret], 200);
        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $charge = Charge::create([
                'amount' => 1000, // Amount in cents
                'currency' => 'usd',
                'source' => $request->stripeToken,
                'description' => 'Test payment from Laravel Stripe Integration'
            ]);

            return response()->json(['message' => 'Payment successful!','charge'=>$charge], 200);
        } catch (\Exception $ex) {
            return response()->json(['error' => 'Error! ' . $ex->getMessage()], 500);
        }
    }
}