<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Stripe;
use Stripe\Card;


class StripePaymentController extends Controller
{
    public function paymentStripe()
    {
        return view('stripe');
    }

    public function postPaymentStripe(Request $request)
{
    $request->validate([
        'stripeToken' => 'required',
    ]);

    $input = $request->except('_token');

    $stripe = \Cartalyst\Stripe\Laravel\Facades\Stripe::setApiKey(config('services.stripe.secret'));

    try{
        $charge = $stripe->charges()->create([
            'source' => $request->get('stripeToken'),
            'currency' => 'mxn',
            'amount' => 100,
            'description' => 'Add in wallet',
        ]);
        
        if($charge['status'] == 'succeeded'){
            return redirect()->route('addmoney.paymentstripe')->with('success', 'Money added to wallet successfully');
        } else {
            return redirect()->route('addmoney.paymentstripe')->with('error', 'The Stripe Token was not generated correctly');
        }
    }catch(Exception $e){
        return redirect()->route('addmoney.paymentstripe')->with('error', $e->getMessage());
    }catch(\Cartalyst\Stripe\Exception\CardErrorException $e){
        return redirect()->route('addmoney.paymentstripe')->with('error', $e->getMessage());
    }catch(\Cartalyst\Stripe\Exception\MissingParameterException $e){
        return redirect()->route('addmoney.paymentstripe')->with('error', $e->getMessage());
    }
}


}
