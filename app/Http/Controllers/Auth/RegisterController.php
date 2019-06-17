<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/member';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data) {
    	$passcode = 'lals';

        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:255'],
			'last_name' => ['required', 'string', 'max:255'],
			'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
			'phone' => ['required', 'string', 'min:10', 'max:20'],
			'phone_ext' => ['string', 'max:6'],
			'address_1' => ['required', 'string', 'max:255'],
			'address_2' => ['nullable', 'max:255'],
			'address_3' => ['nullable', 'max:255'],
			'city' => ['required', 'string', 'max:255'],
			'state' => ['required', 'string', 'max:255'],
			'zip' => ['required', 'string', 'min:5', 'max:10'],
			'passcode' => ['required', 'string', 'in:'.$passcode]
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data) {
    	/*if ($data['passcode'] !== 'lals') {
    		return ''
		}*/
        return User::create([
            'first_name' => $data['first_name'],
			'last_name' => $data['last_name'],
            'email' => $data['email'],
			'username' => $data['username'],
            'password' => Hash::make($data['password']),
			'phone' => $data['phone'],
			'address_1' => $data['address_1'],
			'address_2' => $data['address_2'],
			'address_3' => $data['address_3'],
			'city' => $data['city'],
			'state' => $data['state'],
			'zip' => $data['zip']
        ]);
    }
}
