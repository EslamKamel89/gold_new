<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller {
	use ApiResponse;
	public function login( Request $request ) {
		$request->validate( [ 
			'name' => 'required|exists:users,name|max:255',
			'password' => 'required|max:255',
		] );

		$user = User::where( 'name', $request->name )->first();

		if ( ! $user || ! Hash::check( $request->password, $user->password ) ) {
			throw ValidationException::withMessages( [ 
				'email' => [ 'The provided credentials are incorrect.' ],
			] );
		}

		$token = $user->createToken( $request->email . ' | ' . $request->name )->plainTextToken;
		return $this->success( [ 'token' => $token ], message: 'Login Successful' );
	}
	public function register( Request $request ) {
		$validated = $request->validate( [ 
			'name' => 'required|min:3|max:255',
			'email' => 'required|email|max:255|unique:users,email',
			'password' => 'required|max:255',
		] );

		$user = User::create( $validated );



		$token = $user->createToken( $request->email )->plainTextToken;
		return $this->success( [ 'token' => $token ], message: 'Register Successful' );
	}
	public function forgetPassword( Request $request ) {
	}
}
