<?php
namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Address extends Model {
	protected $collection = 'addresses';

    protected $fillable = ['name', 'address', 'user_id'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];


	public function user() {
		return $this->belongsTo('App\Models\User');
	}
}