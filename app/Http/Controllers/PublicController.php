<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

use App\Page;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

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
        return view('public', ['page'=>$page->content]);
    }

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
		/*
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
		*/
		//$user = User::find(1)->assignRole('super admin');
		return 'Already Done';

	}
}
