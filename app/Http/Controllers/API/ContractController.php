<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

use App\Http\Controllers\APIController;
use App\Models\Contract;

class ContractController extends APIController {

	public function __construct() {
		parent::__construct();

		$this->model = new Contract();
		$this->middleware('auth');
	}

	public function index (Request $request) {
		$request->merge(['user_id' => $request->user()->_id]);
		return parent::index($request);
	}

	public function store(Request $request) {
		$request->merge(['user_id' => $request->user()->_id]);
		return parent::store($request);
	}
	
	public function update(Request $request, $id) {
		$application = Contract::find($id);

        if ($user && Gate::allows('isSelf', $application)) {
            return parent::update($request, $id);
        }
        abort(401, "Unauthorized");
	}

	public function destroy(Request $request, $id) {
		$user = $request->user();

        if ($user && Gate::allows('isSelf', $user)) {
            return parent::destroy($request, $id);
        }
        abort(401, "Unauthorized");
	}
}