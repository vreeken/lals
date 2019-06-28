<?php

namespace App\Http\Controllers\Web;

use App\Models\Forum\Channel;
use App\Models\Members\User;
use Illuminate\Http\Request;

use App\Models\Web\Page;

use Illuminate\Support\Facades\Hash;
use Storage;
use Log;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\Controller;

class PublicController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showPage(Request $request)
    {
    	$path = $request->path();


    	$page = Page::wherePath($path)->first();
    	if (!$page) {
			$default_page = str_replace("/","_",$path);
			$page = new \stdClass();
			$page->content = Storage::get('default_pages/'.$default_page.'.html');
			$page->title = '';
		}
        return view('public', ['page'=>$page]);
    }



	/*public function addUserToMemberCalendar(Request $request) {
		$this->addMember();

		return 'done';
	}

	private function addMember() {
		//Tutorial setting up oauth api and calendar keys
		//https://cornempire.net/2011/12/31/part-1-setting-up-google-calendar/

		$url = 'https://www.googleapis.com/calendar/v3/calendars/pmr28okb56p0ht55a4qhu93tfs@group.calendar.google.com/acl?key=AIzaSyA8iEZLEBOG1S-o_41h-k10oZ2sjsxy-XA';
		$data = ["role" => "writer", "scope" => ["type" => "user", "value" => "svenjoypro@gmail.com"]];

		$token = $this->getAccessToken();

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
		curl_setopt($session, CURLOPT_HTTPHEADER, array('Content-Type:  application/json','Authorization:  Bearer ' . $token,'X-JavaScript-User-Agent:  Mount Pearl Tennis Club Bookings'));

		$response = curl_exec($session);

		curl_close($session);

		return $response;
	}

	private function getAccessToken(){
		$tokenURL = 'https://accounts.google.com/o/oauth2/token';
		$postData = array(
			'client_secret'=>env('GOOGLE_CLIENT_SECRET'),
			'grant_type'=>'refresh_token',
			'refresh_token'=>env('GOOGLE_REFRESH_TOKEN'),
			'client_id'=>env('GOOGLE_CLIENT_ID')
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $tokenURL);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$tokenReturn = curl_exec($ch);
		$token = json_decode($tokenReturn);
		//var_dump($tokenReturn);
		$accessToken = $token->access_token;
		return $accessToken;
	}*/


    public function setupRoles(Request $request) {
		/*
		 ****************************************************************
		 *						 ROLES & PERMISSIONS
		 ****************************************************************
		 *
		 * UNASSIGNED PERMISSIONS
		 * 		* ? Document Management ?
		 * 		? Data Import/Export ?
		 * 		? Customizable Fields ?
		 *
		 *
		 *
		 * Super Admin ?
		 * 		ALL PERMISSIONS
		 *
		 * Web Admin
		 * 		CRUD Static Site Pages, Uploads, Images
		 * 		CRUD Surveys
		 * 		CRUD Email Campaigns and Newsletters
		 * 		CRUD Calendar Events
		 * 		CRUD Fundraising/Donation Campaigns
		 *
		 * Blog Admin
		 * 		CRUD Blog Pages, Uploads, Images
		 *
		 * Forum Admin
		 * 		CRUD Forum Posts
		 *
		 * Membership Admin
		 * 		CRUD Users
		 * 		Assign Roles/Permissions to Users
		 * 		CRUD User Profiles
		 * 		CRUD User Blog Posts
		 * 		Volunteer Hours Tracking
		 *
		 * Finance Admin
		 * 		Financial Dues Tracking (shed and membership)
		 * 		Stripe/Payment Administration
		 * 		Invoices (view private outstanding, paid, upcoming)
		 *
		 * Member
		 * 		Dues Payment (shed and membership)
		 * 		Invoice (view personal)
		 * 		CRUD Self Profile
		 * 		CRUD Personal Blog Pages
		 * 		Volunteer Hours Submissions
		 * 		Forum Posting
		 */

		$superAdmin = Role::create(['name' => 'super admin']);
		$webAdmin = Role::create(['name' => 'web admin']);
		$blogAdmin = Role::create(['name' => 'blog admin']);
		$forumAdmin = Role::create(['name' => 'forum admin']);
		$membershipAdmin = Role::create(['name' => 'membership admin']);
		$financialAdmin = Role::create(['name' => 'financial admin']);
		$member = Role::create(['name' => 'member']);


		$adminPages = Permission::create(['name' => 'admin pages']);
		$adminSurveys = Permission::create(['name' => 'admin surveys']);
		$adminEmails = Permission::create(['name' => 'admin emails']);
		$adminEvents = Permission::create(['name' => 'admin events']);
		$adminFundraisers = Permission::create(['name' => 'admin fundraisers']);
		$adminSiteBlog = Permission::create(['name' => 'admin site blog']);
		$adminUsers = Permission::create(['name' => 'admin users']);
		$adminProfiles = Permission::create(['name' => 'admin profiles']);
		$adminPersonalBlog = Permission::create(['name' => 'admin personal blog']);
		$adminForum = Permission::create(['name' => 'admin forum']);

		$adminPermissions = Permission::create(['name' => 'admin permissions']);
		$adminVolunteerHours = Permission::create(['name' => 'admin volunteer hours']);
		$adminDues = Permission::create(['name' => 'admin dues']);
		$adminPayments = Permission::create(['name' => 'admin payments']);
		$adminInvoices = Permission::create(['name' => 'admin invoices']);

		$memberDues = Permission::create(['name' => 'member dues']);
		$memberInvoices = Permission::create(['name' => 'member invoices']);
		$memberProfile = Permission::create(['name' => 'member profile']);
		$memberVolunteerHours = Permission::create(['name' => 'member volunteer hours']);
		$memberPersonalBlog = Permission::create(['name' => 'member personal blog']);
		$memberForum = Permission::create(['name' => 'member forum']);


		$superAdmin->syncPermissions([$adminPages, $adminSurveys, $adminEmails, $adminEvents, $adminFundraisers,
				$adminSiteBlog, $adminUsers, $adminProfiles, $adminPersonalBlog, $adminForum,
				$adminPermissions, $adminVolunteerHours, $adminDues, $adminPayments, $adminInvoices,
				$memberDues, $memberInvoices, $memberProfile, $memberVolunteerHours, $memberPersonalBlog, $memberForum]);

		$webAdmin->syncPermissions([$adminPages, $adminSurveys, $adminEmails, $adminEvents, $adminFundraisers]);
		$blogAdmin->syncPermissions([$adminSiteBlog]);
		$forumAdmin->syncPermissions([$adminForum]);
		$membershipAdmin->syncPermissions([$adminUsers, $adminPermissions, $adminProfiles, $adminPersonalBlog, $adminVolunteerHours]);
		$financialAdmin->syncPermissions([$adminDues, $adminPayments, $adminInvoices]);
		$member->syncPermissions([$memberDues, $memberInvoices, $memberProfile, $memberVolunteerHours, $memberPersonalBlog, $memberForum]);

		//https://github.com/spatie/laravel-permission

		$user = User::create([
			'first_name' => 'Mike',
			'last_name' => 'Vreeken',
			'email' => 'svenjoypro@gmail.com',
			'username' => 'svenjoypro',
			'password' => Hash::make('123456'),
			'phone' =>  '8052467001',
			'phone_ext' => '',
			'address_1' => "123 abc st",
			'address_2' => '',
			'city' => 'Simi Valley',
			'state' => 'CA',
			'postal_code' => '93063'
		]);

		$user->assignRole('super admin');

		$channels = [
			['name' => 'For Sale', 'slug' => 'for-sale'],
			['name' => 'Looking to Buy', 'slug' => 'looking-to-buy'],
			['name' => 'Upcoming Events', 'slug' => 'events'],
			['name' => 'General Questions', 'slug' => 'questions'],
			['name' => 'General Chat', 'slug' => 'chat']
		];

		for ($i=0; $i<count($channels); $i++) {
			Channel::create($channels[$i]);
		}

		return 'Done';

	}
}
