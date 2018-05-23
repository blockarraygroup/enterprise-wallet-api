<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Jenssegers\Mongodb\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Support\Facades\Hash;

use App\Helpers\MongoDate;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'addresses', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'access_token', 'access_token_expiry'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $collection = 'users';

    public function addresses () {
        return $this->hasMany('App\Models\Addresses');
    }

    public function applications () {
        return $this->hasMany('App\Models\Applications');
    }

    public function setPasswordAttribute($value) {
        $this->attributes['password'] = Hash::make($value);
    }

    public static function registerUser ($request) {
        $user = User::create($request->all());
        $user->updateAccessToken();
        return $user;
    }

    public static function authenticate ($request) {
        $credentials = $request->only(['email', 'password']);
        $user = User::where('email', $credentials['email'])->first();
        if ($user && Hash::check($credentials['password'], $user->password)) {
            return $user;
        }
        app()->abort('401', 'Email or password incorrect');
    }

    public function updateAccessToken () {
        $this->access_token = md5($this->email . $this->created_at . time());
        $this->access_token_expiry = MongoDate::getFromTimestamp(strtotime('+1 month', time()));
        $this->save();
    }
}

User::saving(function($user) {
    $user->email = strtolower($user->email);
});