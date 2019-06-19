<?php

namespace Tests\Feature;

//use Faker\Factory as Faker;
//use Illuminate\Support\Str;
use Tests\TestCase;

class RegistrationTest extends TestCase {
    public function testRegistration_page_is_accessible() {
        $response = $this->get('/register');
        $response->assertSee('Register');
    }

    public function testRegistration_requires_validation() {
		//$this->withExceptionHandling()->signIn();
		$this->withExceptionHandling();

		//Create fake user with a couple values that should be required are null
		//$user = make('registerable', ['address_1'=>null, 'username'=>null], null, 'registerable');
		$user = $this->generateFormDataArray();
		$user['username']=null;
		$user['address_1']=null;

		$this->post(route('register'), $user)
			->assertSessionHasErrors('username', 'address_1');
    		//->assertStatus(422);
	}

	public function testRegistration_creates_user() {
		//$this->withExceptionHandling()->signIn();
		$this->withExceptionHandling();

		//Create fake user with a couple values that should be required are null
		//$user = make('registerable', [], null, 'registerable');
		$user = $this->generateFormDataArray();

		$this->post(route('register'), $user)
			->assertRedirect('/members/account');
	}

	protected function generateFormDataArray() {
		$user = make('App\Models\Members\User')->toArray();

		//Add extra registration form data (password is required because User->toArray() removes the password because it is $hidden in the model
		$user['password'] = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
		$user['password_confirmation'] = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
		$user['passcode'] = 'lals';

		return $user;
		/*$faker = Faker::create();
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
		];*/
	}
}
