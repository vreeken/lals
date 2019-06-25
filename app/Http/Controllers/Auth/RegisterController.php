<?php

namespace App\Http\Controllers\Auth;

use App\Models\Members\User;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

use Log;


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
    protected $redirectTo = '/members/account';
	protected function redirectTo() {
		return '/members/account';
	}
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function formatPhone($phone) {
    	if (strlen($phone) < 10) {
			return '';
		}
    	$phone = preg_replace("/[^0-9]/", "", $phone);

    	if ($phone[0] == '1') {
			$phone = substr($phone, 1);
		}

    	if (strlen($phone) !== 10) {
			return '';
		}

    	return $phone;
	}

	/**
	 * Handle a registration request for the application.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function register(Request $request) {
		$passcode = 'lals';

		//$validator = $this->validator($request->all())->validate();

		$data = $request->all();
		$data['phone'] = $this->formatPhone($data['phone']);

		$validator = Validator::make($data, [
			'first_name' => ['required', 'string', 'max:255'],
			'last_name' => ['required', 'string', 'max:255'],
			'username' => ['required', 'string', 'max:255', 'unique:users'],
			'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
			'password' => ['required', 'string', 'min:6', 'confirmed'],
			'phone' => ['required', 'string', 'min:10', 'max:20'],
			'phone_ext' => ['max:6'],
			'address_1' => ['required', 'string', 'max:255'],
			'address_2' => ['nullable', 'max:255'],
			'city' => ['required', 'string', 'max:255'],
			'state' => ['required', 'string', 'max:255'],
			'postal_code' => ['required', 'string', 'min:5', 'max:10'],
			'passcode' => ['required', 'string', 'in:'.$passcode]
		]);

		if ($validator->fails()) {
			if ($request->expectsJson()) {
				return response()->json(['errors' => $validator->errors()], 422);
			}
			else {
				return redirect()->back()->withErrors($validator->errors());
			}
		}



		event(new Registered($user = $this->create($request->all())));

		if ($user) {
			$this->guard()->login($user);
			if ($request->expectsJson()) {
				return response()->json(['success' => 'success', 'redirect'=>$this->redirectTo]);
			}
			else {
				return redirect($this->redirectTo);
			}
		}

		if ($request->expectsJson()) {
			return response()->json(['error'=>'failed_to_register_user'], 500);
		}
		else {
			return Redirect::back()->withErrors(['error', 'Failed to register user']);
		}

	}


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    /*protected function validator(array $data) {
    	$passcode = 'lals';

    	$data['phone'] = $this->formatPhone($data['phone']);

        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:255'],
			'last_name' => ['required', 'string', 'max:255'],
			'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
			//'phone' => ['required', 'string', 'min:10', 'max:20'],
			'phone_ext' => ['max:6'],
			'address_1' => ['required', 'string', 'max:255'],
			'address_2' => ['nullable', 'max:255'],
			'city' => ['required', 'string', 'max:255'],
			'state' => ['required', 'string', 'max:255'],
			'postal_code' => ['required', 'string', 'min:5', 'max:10'],
			'passcode' => ['required', 'string', 'in:'.$passcode]
        ]);
    }*/

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
		//$data[)->validate($this->validator());
		/*$validation = $this->validator($request->all());
		if ($validation->fails())  {
			return response()->json($validation->errors()->toArray(), 422);
		}*/


		$data['phone'] = $this->formatPhone($data['phone']);

        return User::create([
            'first_name' => $data['first_name'],
			'last_name' => $data['last_name'],
            'email' => $data['email'],
			'username' => $data['username'],
            'password' => Hash::make($data['password']),
			'phone' => $data['phone'],
			'phone_ext' => $data['phone_ext'],
			'address_1' => $data['address_1'],
			'address_2' => $data['address_2'],
			'city' => $data['city'],
			'state' => $data['state'],
			'postal_code' => $data['postal_code']
        ]);
    }
}
