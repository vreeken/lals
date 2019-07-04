<?php

namespace App\Models\Members;

use App\Mail\WelcomeToLALSRM;
use App\Models\Financials\Invoice;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Traits\HasRoles;
use Stripe\Customer;
use Stripe\Stripe;
use Log;

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
		'first_name', 'last_name', 'email', 'username', 'password', 'phone', 'phone_ext', 'address_1', 'address_2', 'city', 'state', 'postal_code'
	];

	public static $ADMIN_LINKS = [
		['text'=>'Admin Dashboard', 'href'=>'admin_dashboard', 'role'=>'any', 'icon'=>'<i class="fas fa-tachometer-alt"></i>'],

		['text'=>'How to Edit Web Pages', 'href'=>'admin_edit_pages_help', 'role'=>'web admin', 'icon'=>'<i class="fas fa-edit"></i>'],
		['text'=>'Manage Surveys', 'href'=>'admin_surveys_manage', 'role'=>'web admin', 'icon'=>'<i class="fas fa-poll"></i>'],
		['text'=>'Manage Email Campaigns', 'href'=>'admin_email_campaigns_manage', 'role'=>'web admin', 'icon'=>'<i class="fas fa-envelope"></i>'],
		['text'=>'Manage Newsletters', 'href'=>'admin_newsletters_manage', 'role'=>'web admin', 'icon'=>'<i class="fas fa-newspaper"></i>'],
		['text'=>'Manage Calendar Events', 'href'=>'admin_calendar_manage', 'role'=>'web admin', 'icon'=>'<i class="fas fa-calendar-alt"></i>'],
		['text'=>'Manage Fundraising Campaigns', 'href'=>'admin_fundraising_campaigns_manage', 'role'=>'web admin', 'icon'=>'<i class="fas fa-hand-holding-usd"></i>'],

		['text'=>'Manage LALS Blog', 'href'=>'admin_blog_manage', 'role'=>'blog admin', 'icon'=>'<i class="fas fa-blog"></i>'],

		['text'=>'Manage Forum', 'href'=>'admin_forum_manage', 'role'=>'forum admin', 'icon'=>'<i class="fas fa-th-list"></i>'],

		['text'=>'Manage User Accounts', 'href'=>'admin_user_accounts_manage', 'role'=>'membership admin', 'icon'=>'<i class="fas fa-users-cog"></i>'],
		['text'=>'Manage User Permissions and Roles', 'href'=>'admin_permissions_manage', 'role'=>'membership admin', 'icon'=>'<i class="fas fa-user-tag"></i>'],
		['text'=>'Manage User Profiles', 'href'=>'admin_profiles_manage', 'role'=>'membership admin', 'icon'=>'<i class="fas fa-user-circle"></i>'],
		['text'=>'Manage Member Blog Posts', 'href'=>'admin_blog_manage', 'role'=>'membership admin', 'icon'=>'<i class="fas fa-blog"></i>'],
		['text'=>'Manage Volunteer Hours', 'href'=>'admin_volunteer_hours_manage', 'role'=>'membership admin', 'icon'=>'<i class="fas fa-user-clock"></i>'],
		['text'=>'Approve Pending Probationary Memberships', 'href'=>'admin_probationary_manage', 'role'=>'membership admin', 'icon'=>'<i class="fas fa-user-plus"></i>'],
		['text'=>'Approve Pending Full Memberships', 'href'=>'admin_membership_manage', 'role'=>'membership admin', 'icon'=>'<i class="fas fa-user-check"></i>'],

		['text'=>'Manage Membership Dues', 'href'=>'admin_membership_dues_manage', 'role'=>'financial admin', 'icon'=>'<i class="fas fa-dollar-sign"></i>'],
		['text'=>'Manage Shed Rental Dues', 'href'=>'admin_shed_dues_manage', 'role'=>'financial admin', 'icon'=>'<i class="fas fa-dollar-sign"></i>'],
		['text'=>'Manage User Invoices', 'href'=>'admin_invoices_manage', 'role'=>'financial admin', 'icon'=>'<i class="fas fa-file-invoice-dollar"></i>'],
		['text'=>'Manage Payment Processing', 'href'=>'admin_payment_processing_manage', 'role'=>'financial admin', 'icon'=>'<i class="fas fa-money-check-alt"></i>'],
		['text'=>'Manually Enter Cash or Checks', 'href'=>'admin_manual_cash_check', 'role'=>'financial admin', 'icon'=>'<i class="fas fa-money-bill-wave"></i>']
	];

	public static $MEMBER_LINKS = [
		['text'=>'My Dashboard', 'href'=>'member_dashboard', 'icon'=>'<i class="fas fa-tachometer-alt"></i>'],
		['text'=>'Account Settings', 'href'=>'member_settings', 'icon'=>'<i class="fas fa-cog"></i>'],
		['text'=>'My Invoices', 'href'=>'member_invoices_show_all', 'icon'=>'<i class="fas fa-file-invoice-dollar"></i>'],
		['text'=>'Track Volunteer Hours', 'href'=>'member_volunteer_hours_show', 'icon'=>'<i class="fas fa-clock"></i>'],
		['text'=>'My Member Profile', 'href'=>'my_profile', 'icon'=>'<i class="fas fa-user-circle"></i>'],
		['text'=>'My Blog', 'href'=>'my_blog', 'icon'=>'<i class="fas fa-blog"></i>'],
		['text'=>'Calendar of Events', 'href'=>'calendar', 'icon'=>'<i class="fas fa-calendar-alt"></i>'],
		['text'=>'View Booster', 'href'=>'show_booster', 'icon'=>'<i class="fas fa-newspaper"></i>'],
		['text'=>'Surveys', 'href'=>'member_surveys_show_all', 'icon'=>'<i class="fas fa-poll"></i>'],
		['text'=>'Donations', 'href'=>'show_donations', 'icon'=>'<i class="fas fa-hand-holding-usd"></i>'],
		['text'=>'Member Forum', 'href'=>'forum_home', 'icon'=>'<i class="fas fa-th-list"></i>']
	];

	public static $ROLES = [
		'super admin', 'web admin', 'blog admin', 'forum admin', 'membership admin', 'financial admin'
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

	public function getFullName(): string
	{
		return $this->first_name . ' ' .  $this->last_name;
	}

	public function isAdmin() {
		return $this->hasAnyRole($this->ROLES);
	}

	public function getAdminLinks() {
		$admin_links = [];
		if ($this->hasAnyRole($this->ROLES)) {
			foreach (User::$ADMIN_LINKS as $link) {
				if ($this->hasRole('super admin') || $link['role'] === 'any' || $this->hasRole($link['role'])) {
					$admin_links[] = $link;
				}
			}
		}
		return $admin_links;
	}

	public function getMemberLinks() {
		/*$member_links = [];
		foreach ($this->MEMBER_LINKS as $link) {
			$member_links[] = $link;
		}*/
		return User::$MEMBER_LINKS;
	}

	protected static function boot() :void
	{
		parent::boot();

		static::created(function ($user) {
			/*$user->profile()->create([
				'title' => $user->username,
			]);*/

			//Mail::to($user->email)->send(new WelcomeToLALSRM());



			//$user->createStripeCustomerForUser();




			//$user->addUserToGoogleCalendar();



			/*\Stripe\InvoiceItem::create([
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
			]);*/

			/*
			 * OR create a subscription
			 $subscription = \Stripe\Subscription::create([
				'customer' => 'cus_4fdAW5ftNQow1a',
				'items' => [['plan' => 'plan_CBb6IXqvTLXp3f']],
				'billing' => 'send_invoice',
				'days_until_due' => 30,
			]);
			 */
		});
	}

	private function createStripeCustomerForUser() {
		//Create a new Stripe Customer ID
		Stripe::setApiKey(env('STRIPE_SECRET'));

		$customer = Customer::create([
			'email' => $this->email,
			'source' => null,
			'name' => $this->getFullName(),
			'address' => [
				'line1' => $this->address_1,
				'city' => $this->city,
				'state' => $this->state,
				'postal_code' => $this->zip,
				'line2' => $this->address_2
			],
			'metadata' => [
				'user_id' => $this->id
			],
			'phone' => $this->phone . ' ' . $this->phone_ext
		]);

		$this->stripe_id = $customer->id;
		$this->save();
	}

	private function addUserToGoogleCalendar() {
		//https://cornempire.net/2011/12/31/part-1-setting-up-google-calendar/
		//https://cornempire.net/2012/01/08/part-2-oauth2-and-configuring-your-application-with-google/
		//https://cornempire.net/2012/01/15/part-3-oauth2-and-configuring-your-application-with-google/
		//
		$url = 'https://www.googleapis.com/calendar/v3/calendars/' . env('GOOGLE_CALENDAR_MEMBERS_ONLY') . '/acl?key=' . env('GOOGLE_API_KEY');
		$data = ['role' => 'writer', 'scope' => ['type' => 'user', 'value' => $this->email]];

		//TODO cache $token for an hour
		$token = $this->getOauthAccessToken();

		$session = curl_init($url);

		// Tell curl to use HTTP POST
		curl_setopt ($session, CURLOPT_POST, true);
		// Tell curl that this is the body of the POST
		curl_setopt ($session, CURLOPT_POSTFIELDS, json_encode($data));
		// Tell curl not to return headers, but do return the response
		curl_setopt($session, CURLOPT_HEADER, true);
		curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($session, CURLOPT_VERBOSE, true);
		curl_setopt($session, CURLINFO_HEADER_OUT, true);
		curl_setopt($session, CURLOPT_HTTPHEADER, array('Content-Type:  application/json','Authorization:  Bearer ' . $token,'X-JavaScript-User-Agent:  LALSRM Calendar Add User'));

		$response = curl_exec($session);

		curl_close($session);

		return $response;
	}

	private function getOauthAccessToken(){
		$tokenURL = 'https://accounts.google.com/o/oauth2/token';
		$postData = array(
			'client_secret'=>env('GOOGLE_CLIENT_SECRET'),
			'grant_type'=>'refresh_token',
			'refresh_token'=>env('GOOGLE_REFRESH_TOKEN'),
			'client_id'=>env('GOOGLE_CLIENT_ID')
		);

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $tokenURL);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		$tokenReturn = curl_exec($curl);

		return json_decode($tokenReturn)->access_token;
	}

	public function invoices() {
		return $this->hasMany(Invoice::class)->orderBy('created_at', 'DESC');
	}

	public function volunteerHours() {
		return $this->hasMany(VolunteerHour::class);
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
