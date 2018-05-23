<?php
namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Application extends Model {
	protected $collection = 'applications';

    protected $fillable = ['name', 'url', 'image', 'user_id'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];


	public function user() {
		return $this->belongsTo('App\Models\User');
	}
}