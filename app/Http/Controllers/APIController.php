<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class APIController extends Controller {
	protected $model;
	protected $blacklisted_fields = ['access_token'];

	public function __construct() {

	}

	public function index(Request $request) {
		$params = $request->except($this->blacklisted_fields);
		
		$query = $this->model;

		foreach ($params as $key => $value) {
			switch ($key) {
				case 'q':
				case 'p':
				case 'with':
				case 'limit':
					break;
				default:
					$query = $query->where($key, $value);
			}
		}

		$query = $this->attachData($query, $request);
		$count = $query->count();

		$page_size = $request->has('limit') ? intval($request->get('limit')) : intval(env('PAGE_SIZE'));
		if ($request->has('p')) {
			$query = $query->skip(($request->get('p')-1) * $page_size);
		}
		$query = $query->take($page_size);

		return [
			'data' => $query->get(),
			'count' => $count
		];
	}

	public function show(Request $request, $id) {
		$query = $this->model;
		$query = $this->attachData($query, $request);
		$response = $query->find($id);

		if (!$response || !$response->id) {
			abort('404', 'Resourse not found');
		}

		return $response;
	}

	public function store(Request $request) {
		//TODO: check validation rules first

		$params = $request->except($this->blacklisted_fields);
		return $this->model->create($params);
	}

	public function update(Request $request, $id) {
		//TODO: check validation rules first

		$params = $request->except($this->blacklisted_fields);
		$model = $this->model->find($id);

		if (!$model || !$model->id) {
			abort('404', 'Resource not found');
		}

		$model->fill($params);
		$model->save();
		return $model;
	}

	public function destroy (Request $request, $id) {
		$model = $this->model->find($id);

		$response = array('success' => false);
		if($model) {
			$response['success'] = $model->delete();
		}

		return $response;
	}

	private function attachData($query, $request) {
		if ($request->has('with')) {
			$with = $request->get('with');
			$with = explode(',', $with);
			$query = $query->with[$with];
		}
		return $query;
	}
}