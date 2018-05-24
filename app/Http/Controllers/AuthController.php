<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class AuthController extends Controller {
	public function __construct() {

	}

	public function register (Request $request) {
		$this->validate($request, [
			'email' => 'required|unique:users',
			'password' => 'required|min:6'
		]);

		$user = User::registerUser($request);

		$user_data = $user->toArray();
		$user_data['access_token'] = $user['access_token'];
		$user_data['access_token_expiry'] = $user['access_token_expiry'];
		
		return $user_data;
	}

	public function login (Request $request) {
		$this->validate($request, [
			'email' => 'required',
			'password' => 'required'
		]);
		$user = User::authenticate($request);

		$user_data = $user->toArray();
		$user_data['access_token'] = $user['access_token'];
		$user_data['access_token_expiry'] = $user['access_token_expiry'];
		
		return $user_data;
	}

	public function refreshUser (Request $request) {
		$user = $request->user();
		if ($user) {
			$user_data = $user->toArray();
			$user_data['access_token'] = $user['access_token'];
			$user_data['access_token_expiry'] = $user['access_token_expiry'];
			
			return $user_data;
		}
		abort(401, "Unauthorized");
	}

	public function updateUser (Request $request) {
		$response = [
			'success' => false,
			'user' => null
		];

		$user = $request->user();
		if ($user) {
			$user->fill($request->only('name', 'email'));
			if ($request->has('current_password') && $request->has('new_password')
				&& Hash::check($request->get('current_password'), $user->password)) {
				
				$user->password = $request->get('new_password');
				$response['password_change'] = true;
			}
			$user->save();
			
			$user_data = $user->toArray();
			$user_data['access_token'] = $user['access_token'];
			$user_data['access_token_expiry'] = $user['access_token_expiry'];

			$response['success'] = true;
			$response['user'] = $user_data;
		}
		return $response;
	}
}