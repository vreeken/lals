<?php

namespace App\Http\Controllers\Financials;

use App\Models\Financials\InvalidInvoiceWebhook;
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
			'invoice_number' => $invoice->number,
			'user_id' => $user->id,
			'status' => $invoice->status,
			'due_date' => date("Y-m-d H:i:s", $invoice->due_date),
			'amount_due' => $invoice->amount_due,
			'amount_paid' => $invoice->amount_paid,
			'invoice_pdf' => $invoice->invoice_pdf,
			'failed_attempt_count' => $invoice->attempt_count,
			'raw_stripe_create_data' => json_encode($invoice)
		]);
    }

    public function manuallyMarkAsPaid() {

	}

	//https://stripe.com/docs/billing/invoices/sending

	//https://stripe.com/docs/webhooks/setup
	//https://stripe.com/docs/api/webhook_endpoints/create
	//https://dashboard.stripe.com/test/webhooks

	//https://stripe.com/docs/billing/invoices/subscription

	public function stripeWebhook(Request $request) {
    	//Get stripe data as an array, strip down to just what we need
    	$all = $request->all();
    	$data = $all['data']['object'];
		Log::error(json_encode($all));
    	//Check if the invoice id from stripe matches one of our invoices
    	$invoice = Invoice::where('stripe_invoice_id', $data['invoice'])->first();
    	//if not log and create InvalidInvoiceWebhook
    	if (!$invoice) {
    		$iiw = InvalidInvoiceWebhook::create(['invoice_id' => $data['invoice'], 'raw_stripe_data' => json_encode($all)]);
    		Log::error('Invalid invoice id from stripe webhook. InvalidInvoiceWebhook ID: '.$iiw->id . ' | Attempted Invoice Id: '.$data['invoice']);

    		//Let stripe know we handled the webhook
			return response()->json(['success'=>'success'], 200);
		}

    	//Update invoice's most recent status and webhook raw data regardless of success or failure
		$invoice->status = $data['status'];
		$invoice->raw_stripe_webhook_data = json_encode($all);

		//Check if the stripe payment was not successful, if so increment the failed attempts
		if ($data['status'] !== 'succeeded') {
			$invoice->failed_attempt_count = (int) $invoice->failed_attempt_count + 1;
			$invoice->save();

			//Let stripe know we handled the webhook
			return response()->json(['success'=>'success'], 200);
		}

		//Update the amount paid and due
    	$invoice->amount_due = (int) $invoice->amount_due - (int) $data['amount'];
		$invoice->amount_paid = (int) $invoice->amount_paid + (int) $data['amount'];

		//If the invoice is completely paid off set the paid_at date and receipt url
		if ($data['paid'] == true) {
			$invoice->paid_at = date("Y-m-d H:i:s", $data['created']);
			$invoice->receipt_url = $data['receipt_url'];
		}

		//Save
    	$invoice->save();

		//Let stripe know we handled the webhook
		return response()->json(['success'=>'success'], 200);
	}

	public function createStripeWebhooks() {
    	//This can be done from the stripe dashboard or here programatically
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
