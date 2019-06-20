<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Members\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

/*
//Called with $user = make/create('App\Models\Members\User', ['address_1'=>null, 'username'=>null], null, 'registerable');
//This returns a registerable set of user data, including password_confirmation and passcode, which can't be persisted to the db, but are necessary for validation
$factory->state('registerable', 'registerable', function (Faker $faker) {
    return [
        'first_name' => $faker->firstName,
		'last_name' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
		'username' => $faker->userName,
		'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
		'password_confirmation' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
		'phone' =>  '8001234567',
		'phone_ext' => '123',
		'address_1' => "123 abc st",
		'address_2' => '',
		'address_3' => '',
		'city' => $faker->city,
		'state' => $faker->state,
		'zip' => $faker->postcode,
        'remember_token' => Str::random(10),
		'passcode' => 'lals'
    ];
});
*/

//Called with $user = make/create('App\Models\Members\User');
$factory->define(App\Models\Members\User::class, function (Faker $faker) {
	return [
		'first_name' => $faker->firstName,
		'last_name' => $faker->lastName,
		'email' => $faker->unique()->safeEmail,
		'username' => $faker->userName,
		'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
		'phone' =>  '8001234567',
		'phone_ext' => '123',
		'address_1' => "123 abc st",
		'address_2' => '',
		'address_3' => '',
		'city' => $faker->city,
		'state' => $faker->state,
		'zip' => $faker->postcode,
		'remember_token' => Str::random(10)
	];
});
