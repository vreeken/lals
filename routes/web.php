<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('/stripe-webhook', 'Financials\InvoicesController@stripeWebhook');


Auth::routes();


Route::get('/setuproles', 'Web\PublicController@setupRoles');


/* Public Facing Static Pages*/
Route::get('/', 'Web\PublicController@showPage');
Route::get('/contact-us', 'Web\PublicController@showPage');
Route::get('/historical-exhibits', 'Web\PublicController@showPage');
Route::get('/membership', 'Web\PublicController@showPage');
Route::get('/public-rides', 'Web\PublicController@showPage');
Route::get('/the-railroad', 'Web\PublicController@showPage');
Route::get('/the-trains', 'Web\PublicController@showPage');
Route::get('/visiting-railroaders', 'Web\PublicController@showPage');

Route::get('/calendar', 'Web\CalendarController@show')->name('calendar');
//Route::get('/calendar/add', 'Web\PublicController@addUserToMemberCalendar');


Route::group(['middleware' => 'auth'], function() {
	/*
	 * Any Admin
	 */
	Route::group(['prefix' => 'admins',  'middleware' => ['role:super admin|web admin|blog admin|forum admin|membership admin|financial admin']], function() {
		Route::get('/account', 'Admins\AdminsController@index')->name('admin_dashboard');
	});

	/*
	 * Web Admin
	 */
	Route::group(['middleware' => ['role:super admin|web admin']], function() {
		Route::post('/edit-pages-help', 'Admins\WebAdminsController@showEditPagesHelp')->name('admin_edit_pages_help');
		Route::post('/edit/page', 'Admins\WebAdminsController@storeEditPage')->name('admin_edit_page_store');
		Route::get('/edit', 'Admins\WebAdminsController@showEditPage')->name('admin_edit_page');
		Route::get('/edit/{path}', 'Admins\WebAdminsController@showEditPage')->name('admin_edit_page');

		Route::post('admin/images/new', 'Admins\WebAdminsController@createImage')->name('admin_store_image');


		Route::get('admin/calendar/manage', 'Web\CalendarEventsController@showManageCalendar')->name('admin_calendar_manage');
		Route::get('admin/calendar/create', 'Web\CalendarEventsController@create')->name('admin_calendar_create');
		Route::post('admin/calendar/new', 'Web\CalendarEventsController@store')->name('admin_calendar_store');

		//Route::get('members/surveys/a/{survey_id}', 'Web\SurveysController@retrieveResultsBySurvey');
		Route::get('admins/surveys/manage', 'Web\SurveysController@showManageSurveys')->name('admin_surveys_manage');
		Route::get('admins/surveys/create', 'Web\SurveysController@showCreateSurvey')->name('admin_surveys_create');
		Route::post('admins/surveys/create', 'Web\SurveysController@storeSurvey')->name('admin_surveys_store');

		//TODO
		Route::get('admins/emails/manage', 'Web\EmailCampaignsController@showManageCampaigns')->name('admin_email_campaigns_manage');
		Route::get('admins/newsletters/manage', 'Web\NewslettersController@showManageNewsletters')->name('admin_newsletters_manage');
		Route::get('admins/fundraisers/manage', 'Web\FundraisersController@showManageCampaigns')->name('admin_fundraising_campaigns_manage');
		Route::get('admins/blog/manage', 'Web\BlogController@showManageBlog')->name('admin_blog_manage');
		Route::get('admins/forum/manage', 'Web\ForumController@showManageForum')->name('admin_forum_manage');
		Route::get('admins/users/manage', 'Web\UserController@showManageUserAccounts')->name('admin_user_accounts_manage');
		Route::get('admins/permissions/manage', 'Web\UserController@showManagePermissions')->name('admin_permissions_manage');
		Route::get('admins/profiles/manage', 'Web\UserController@showManageUserProfiles')->name('admin_profiles_manage');
		Route::get('admins/volunteer-hours/manage', 'Web\UserController@showManageVolunteerHours')->name('admin_volunteer_hours_manage');
		Route::get('admins/probationary/manage', 'Web\UserController@showManageProbationary')->name('admin_probationary_manage');
		Route::get('admins/membership/manage', 'Web\UserController@showManageMembership')->name('admin_membership_manage');
		Route::get('admins/membership-dues/manage', 'Web\UserController@showManageMembershipDues')->name('admin_membership_dues_manage');
		Route::get('admins/shed-dues/manage', 'Web\UserController@showManageShedDues')->name('admin_shed_dues_manage');
		Route::get('admins/invoices/manage', 'Web\UserController@showManageInvoices')->name('admin_invoices_manage');
		Route::get('admins/payments/manage', 'Web\UserController@showManagePaymentProcessing')->name('admin_payment_processing_manage');
		Route::get('admins/cash-check/manage', 'Web\UserController@showManageCashCheck')->name('admin_manual_cash_check');
	});

	/*
	 * All Members
	 */
	Route::get('/members/account', 'Members\MembersController@index')->name('member_dashboard');
	Route::get('/members/settings', 'Members\MembersController@showSettings')->name('member_settings');
	//Route::get('/members/{member}', 'Members\MembersController@');

	Route::get('/members/invoices', 'Members\MembersController@showInvoices')->name('member_invoices_show_all');
	Route::post('/members/invoices/request-email', 'Financials\InvoicesController@requestInvoiceEmail')->name('member_invoices_request_email');

	Route::get('/members/volunteer-hours', 'Members\VolunteerHoursController@show')->name('member_volunteer_hours_show');
	Route::post('/members/volunteer-hours', 'Members\VolunteerHoursController@store')->name('member_volunteer_hours_store');


	Route::get('/members/surveys', 'Web\SurveysController@showSurveys')->name('member_surveys_show_all');
	Route::get('/members/surveys/{survey}', 'Web\SurveysController@showSurvey')->name('member_surveys_show');
	Route::post('/members/surveys/{survey}', 'Web\SurveysController@storeAnswer')->name('member_surveys_store');


	//TODO
	Route::get('/members/my-profile', 'Web\UserController@showMyProfile')->name('my_profile');
	Route::get('/members/my-blog', 'Web\UserController@showMyBlog')->name('my_blog');
	Route::get('/members/booster', 'Web\UserController@showBooster')->name('show_booster');
	Route::get('/members/donations', 'Web\UserController@showBooster')->name('show_donations');

});

/*
 * FORUM
 */
Route::group(['middleware' => 'auth', 'prefix'=>'forum'], function() {
	Route::get('/', 'Forum\ForumController@index')->name('forum_home');
	Route::get('threads', 'Forum\ThreadsController@index')->name('threads');
	Route::get('threads/create', 'Forum\ThreadsController@create');
	Route::get('threads/search', 'Forum\SearchController@show');
	Route::get('threads/{channel}/{thread}', 'Forum\ThreadsController@show');
	Route::patch('threads/{channel}/{thread}', 'Forum\ThreadsController@update');
	Route::delete('threads/{channel}/{thread}', 'Forum\ThreadsController@destroy');
	Route::post('threads', 'Forum\ThreadsController@store');
	Route::get('threads/{channel}', 'Forum\ThreadsController@index');

	Route::post('locked-threads/{thread}', 'Forum\LockedThreadsController@store')->name('locked-threads.store')->middleware('role:super admin|forum admin');
	Route::delete('locked-threads/{thread}', 'Forum\LockedThreadsController@destroy')->name('locked-threads.destroy')->middleware('role:super admin|forum admin');

	Route::get('/threads/{channel}/{thread}/replies', 'Forum\RepliesController@index');
	Route::post('/threads/{channel}/{thread}/replies', 'Forum\RepliesController@store');
	Route::patch('/replies/{reply}', 'Forum\RepliesController@update');
	Route::delete('/replies/{reply}', 'Forum\RepliesController@destroy')->name('replies.destroy');

	Route::post('/replies/{reply}/best', 'Forum\BestRepliesController@store')->name('best-replies.store');

	Route::post('/threads/{channel}/{thread}/subscriptions', 'Forum\ThreadSubscriptionsController@store');
	Route::delete('/threads/{channel}/{thread}/subscriptions', 'Forum\ThreadSubscriptionsController@destroy');

	Route::post('/replies/{reply}/favorites', 'Forum\FavoritesController@store');
	Route::delete('/replies/{reply}/favorites', 'Forum\FavoritesController@destroy');

	Route::get('/profiles/{user}', 'Forum\ProfilesController@show')->name('profile');
	Route::get('/profiles/{user}/notifications', 'Forum\UserNotificationsController@index');
	Route::delete('/profiles/{user}/notifications/{notification}', 'Forum\UserNotificationsController@destroy');

	//Route::get('/register/confirm', 'Auth\RegisterConfirmationController@index')->name('register.confirm');

	//Route::get('api/users', 'Api\UsersController@index');
	//Route::post('api/users/{user}/avatar', 'Api\UserAvatarController@store')->name('avatar');
});



/*use App\Mail\NewUserWelcomeMail;

Auth::routes();

Route::get('/email', function () {
	return new NewUserWelcomeMail();
});

Route::post('follow/{user}', 'FollowsController@store');

Route::get('/', 'PostsController@index');
Route::get('/p/create', 'PostsController@create');
Route::post('/p', 'PostsController@store');
Route::get('/p/{post}', 'PostsController@show');

Route::get('/profile/{user}', 'ProfilesController@index')->name('profile.show');
Route::get('/profile/{user}/edit', 'ProfilesController@edit')->name('profile.edit');
Route::patch('/profile/{user}', 'ProfilesController@update')->name('profile.update');*/