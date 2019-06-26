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

Route::get('/calendar', 'Web\CalendarController@show');
//Route::get('/calendar/add', 'Web\PublicController@addUserToMemberCalendar');


Route::group(['middleware' => 'auth'], function() {
		Route::get('/members/account', 'Members\MembersController@index');
		//Route::get('/members/{member}', 'Members\MembersController@');

		Route::get('/members/invoices', 'Members\MembersController@showInvoices');
		Route::post('/members/invoices/request-email', 'Financials\InvoicesController@requestInvoiceEmail');

		Route::get('/members/volunteer-hours', 'Members\VolunteerHoursController@show');
		Route::post('/members/volunteer-hours', 'Members\VolunteerHoursController@store');
		
		

	Route::group(['prefix' => 'admin',  'middleware' => ['role:super admin|web admin|blog admin|forum admin|membership admin|financial admin']], function() {
		Route::get('/', 'Admins\AdminsController@index');
	});

	/*
	 * Web Admin
	 */
	Route::group(['middleware' => ['role:super admin|web admin']], function() {
		Route::post('/edit/page', 'Admins\WebAdminsController@storeEditPage');
		Route::get('/edit', 'Admins\WebAdminsController@showEditPage');
		Route::get('/edit/{path}', 'Admins\WebAdminsController@showEditPage');

		Route::post('admin/images/new', 'Admins\WebAdminsController@createImage');


		Route::get('admin/calendar/create', 'Web\CalendarEventsController@create');
		Route::post('admin/calendar/new', 'Web\CalendarEventsController@store');
	});
});

/*
 * FORUM
 */
Route::group(['middleware' => 'auth', 'prefix'=>'forum'], function() {
	Route::get('/', 'Forum\ForumController@index');
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