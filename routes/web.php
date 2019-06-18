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

Auth::routes();

Route::get('/setuproles', 'PublicController@setupRoles');

Route::get('/', 'PublicController@showPage');

Route::group(['middleware' => 'auth'], function() {
		Route::get('/members/account', 'MembersController@index');
		Route::get('/members/{member}', 'MembersController@');

	Route::group(['prefix' => 'admin',  'middleware' => ['role:super admin|web admin|blog admin|forum admin|membership admin|financial admin']], function() {
		Route::get('/', 'AdminController@index');
	});

	Route::group(['middleware' => ['role:super admin|web admin']], function() {
		Route::post('/edit/page', 'WebAdminController@storeEditPage');
		Route::get('/edit', 'WebAdminController@showEditPage');
		Route::get('/edit/{path}', 'WebAdminController@showEditPage');

		Route::post('admin/images/new', 'WebAdminController@createImage');
	});
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