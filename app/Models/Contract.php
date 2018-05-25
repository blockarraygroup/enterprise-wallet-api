<?php
namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Contract extends Model {
	protected $collection = 'contracts';

	protected $fillable = ['name', 'address', 'tx_hash', 'user_id', 'deploy_type', 'abi'];
	protected $dates = ['created_at', 'updated_at', 'deleted_at'];

	public function user () {
		return $this->belongsTo('App\Models\User');
	}
}