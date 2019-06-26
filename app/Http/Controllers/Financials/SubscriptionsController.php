<?php

namespace App\Http\Controllers\Financials;

use App\Models\Financials\Subscription;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubscriptionsController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index()
    {

    }

    public function create()
    {
    	//HTML Form
		//https://stripe.com/docs/stripe-js/elements/quickstart


    	//Basic
		//$user->newSubscription('main', 'premium')->create($token);

		//Also send email to Stripe
		/*$user->newSubscription('main', 'monthly')->create($token, [
			'email' => $email,
		]);*/

		//With Coupon
		/*$user->newSubscription('main', 'monthly')
			->withCoupon('code')
			->create($token);*/



		//Check sub status
		//if ($user->subscribed('main')) {


		/*
		 * Middleware
		  public function handle($request, Closure $next)
			{
				if ($request->user() && ! $request->user()->subscribed('main')) {
					// This user is not a paying customer...
					return redirect('billing');
				}

				return $next($request);
			}
		 */



		//if ($user->subscribedToPlan('monthly', 'main')) {



		//Currently subscribed, not on a trial
		//if ($user->subscription('main')->recurring()) {



		//if ($user->subscription('main')->cancelled()) {
    }

    public function store()
    {

    }

    public function show(\App\Post $post)
    {

    }
}
