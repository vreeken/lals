<?php

namespace App\Http\Controllers\Admins;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class AdminsController extends Controller
{

    public function index(Request $request) {
		$user = Auth::user();

		/*$ADMIN_LINKS = [
			['text'=>'Admin Dashboard', 'href'=>route('admin_dashboard'), 'role'=>'any'],

			['text'=>'How to Edit Web Pages', 'href'=>route('admin_edit_pages_help'), 'role'=>'web admin'],
			['text'=>'Manage Surveys', 'href'=>route('admin_surveys_manage'), 'role'=>'web admin'],
			['text'=>'Manage Email Campaigns', 'href'=>route('admin_email_campaigns_manage'), 'role'=>'web admin'],
			['text'=>'Manage Newsletters', 'href'=>route('admin_newsletters_manage'), 'role'=>'web admin'],
			['text'=>'Manage Calendar Events', 'href'=>route('admin_calendar_manage'), 'role'=>'web admin'],
			['text'=>'Manage Fundraising Campaigns', 'href'=>route('admin_fundraising_campaigns_manage'), 'role'=>'web admin'],

			['text'=>'Manage Blog', 'href'=>route('admin_blog_manage'), 'role'=>'blog admin'],

			['text'=>'Manage Forum', 'href'=>route('admin_forum_manage'), 'role'=>'forum admin'],

			['text'=>'Manage User Accounts', 'href'=>route('admin_user_accounts_manage'), 'role'=>'membership admin'],
			['text'=>'Manage User Permissions and Roles', 'href'=>route('admin_permissions_manage'), 'role'=>'membership admin'],
			['text'=>'Manage User Profiles', 'href'=>route('admin_profiles_manage'), 'role'=>'membership admin'],
			['text'=>'Manage Blog Posts', 'href'=>route('admin_blog_manage'), 'role'=>'membership admin'],
			['text'=>'Manage Volunteer Hours', 'href'=>route('admin_volunteer_hours_manage'), 'role'=>'membership admin'],
			['text'=>'Approve Pending Probationary Memberships', 'href'=>route('admin_probationary_manage'), 'role'=>'membership admin'],
			['text'=>'Approve Pending Full Memberships', 'href'=>route('admin_membership_manage'), 'role'=>'membership admin'],

			['text'=>'Manage Membership Dues', 'href'=>route('admin_membership_dues_manage'), 'role'=>'financial admin'],
			['text'=>'Manage Shed Rental Dues', 'href'=>route('admin_shed_dues_manage'), 'role'=>'financial admin'],
			['text'=>'Manage User Invoices', 'href'=>route('admin_invoices_manage'), 'role'=>'financial admin'],
			['text'=>'Manage Payment Processing', 'href'=>route('admin_payment_processing_manage'), 'role'=>'financial admin'],
			['text'=>'Manually Enter Cash or Checks', 'href'=>route('admin_manual_cash_check'), 'role'=>'financial admin'],
		];

		$MEMBER_LINKS = [
				['text'=>'My Dashboard', 'href'=>route('member_dashboard')],
				['text'=>'My Invoices', 'href'=>route('member_invoices_show_all')],
				['text'=>'Track Volunteer Hours', 'href'=>route('member_volunteer_hours_show')],
				['text'=>'My Member Profile', 'href'=>route('my_profile')],
				['text'=>'My Blog', 'href'=>route('my_blog')],
				['text'=>'Calendar of Events', 'href'=>route('calendar')],
				['text'=>'View Booster', 'href'=>route('show_booster')],
				['text'=>'Surveys', 'href'=>route('member_surveys_show_all')],
				['text'=>'Donations', 'href'=>route('show_donations')],
				['text'=>'Member Forum', 'href'=>route('forum_home')],
		];

		$admin_links = [];
		foreach ($ADMIN_LINKS as $link) {
			if ($user->hasRole('super admin') || $link['role'] === 'any' || $user->hasRole($link['role'])) {
				$admin_links[] = $link;
			}
		}

		$member_links = [];
		foreach ($MEMBER_LINKS as $link) {
			$member_links[] = $link;
		}*/
        return view('admin.dashboard', ['user'=>$user, 'admin_links'=>$user->getAdminLinks(), 'member_links'=>$user->getMemberLinks()]);
    }
}
