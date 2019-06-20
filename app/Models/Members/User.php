<?php

namespace App\Models\Members;

use App\Mail\WelcomeToLALSRM;
use App\Models\Financials\Invoice;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
	use Notifiable;
	use HasRoles;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'first_name', 'last_name', 'email', 'username', 'password', 'phone', 'phone_ext', 'address_1', 'address_2', 'address_3', 'city', 'state', 'zip'
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'stripe_id', 'remember_token',
	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
	];

	public function getFullName() {
		return $this->first_name . ' ' .  $this->last_name;
	}

	protected static function boot()
	{
		parent::boot();

		static::created(function ($user) {
			/*$user->profile()->create([
				'title' => $user->username,
			]);*/

			//Mail::to($user->email)->send(new WelcomeToLALSRM());



			//Create a new Stripe Customer ID

			\Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

			$customer = \Stripe\Customer::create([
				'email' => $user->email,
				'source' => null,
				'name' => $user->getFullName(),
				'address' => [
					'line1' => $user->address_1,
					'city' => $user->city,
					'state' => $user->state,
					'postal_code' => $user->zip,
					'line2' => $user->address_2
				],
				'metadata' => [
					'user_id' => $user->id
				],
				'phone' => $user->phone . ' ' . $user->phone_ext
			]);

			$user->stripe_id = $customer->id;
			$user->save();









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
		});
	}

/*	public function posts()
	{
		return $this->hasMany(Post::class)->orderBy('created_at', 'DESC');
	}

	public function following()
	{
		return $this->belongsToMany(Profile::class);
	}

	public function profile()
	{
		return $this->hasOne(Profile::class);
	}*/
}
