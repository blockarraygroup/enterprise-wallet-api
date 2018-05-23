<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\APIController;
use App\Models\User;

class UserController extends APIController {
	public function __construct() {
		parent::__construct()

		$this->model = new User();
	}
}