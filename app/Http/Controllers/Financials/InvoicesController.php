<?php

namespace App\Http\Controllers\Financials;

use App\Models\Financials\Invoice;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Log;

class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		//https://stripe.com/docs/billing/invoices/sending
    	$user = User::find(1);

		\Stripe\InvoiceItem::create([
			'amount' => 100,
			'currency' => 'usd',
			'customer' => $user->stripe_id,
			'description' => 'One-time setup fee',
		]);

		$invoice = \Stripe\Invoice::create([
			'customer' => $user->stripe_id,
			'billing' => 'send_invoice',
			'days_until_due' => 30,
		]);

		/*
		 * OR create a subscription
		 $subscription = \Stripe\Subscription::create([
			'customer' => 'cus_4fdAW5ftNQow1a',
			'items' => [['plan' => 'plan_CBb6IXqvTLXp3f']],
			'billing' => 'send_invoice',
			'days_until_due' => 30,
		]);
		 */
		$invoice->sendInvoice();

		Invoice::create([
			'stripe_invoice_id' => $invoice->id,
			'user_id' => $user->id,
			'status' => $invoice->status,
			'due_date' => date("Y-m-d H:i:s", $invoice->due_date),
			'amount_due' => $invoice->amount_due,
			'amount_paid' => $invoice->amount_paid,
			'invoice_pdf' => $invoice->invoice_pdf,
			'attempt_count' => $invoice->attempt_count,
			'raw_stripe_data' => json_encode($invoice)
		]);
    }

    public function manuallyMarkAsPaid() {

	}

	//https://stripe.com/docs/billing/invoices/sending

	//https://stripe.com/docs/webhooks/setup
	//https://stripe.com/docs/api/webhook_endpoints/create
	//https://dashboard.stripe.com/test/webhooks

	//https://stripe.com/docs/billing/invoices/subscription

	//https://dashboard.stripe.com/test/invoices/in_1EnCRxIN5jVBPtPxOQmQ9adI

	//TODO Test this updates our invoice
	//Use laragon->www->share -> ngrok
	public function stripeWebhook(Request $request) {
    	Log::error(json_encode($request->all()));
		$data = $request->input('data')->object;
    	$invoice = Invoice::where('stripe_invoice_id', $data->id)->first();
    	if (!$invoice) {
    		Log::error('invalid invoice id from stripe webhook');
		}

    	$invoice->status = $data->status;
    	//TODO what else do we update here
    	$invoice->save();

		return response()->json(['success'=>'success'], 200);
	}

	public function createStripeWebhooks() {
		\Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

		\Stripe\WebhookEndpoint::create([
			"url" => url('\stripe-webhooks'),
			"enabled_events" => ["charge.failed", "charge.succeeded"]
		]);
	}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $invoice)
    {
        //
    }
}
