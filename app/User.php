<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Silber\Bouncer\Database\HasRolesAndAbilities;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use Notifiable;
    use HasRolesAndAbilities;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function hasAnyRoles()
    {
        if (auth()->check()) {
            return auth()->user()->roles->count();
        } else {
            redirect(route('admin.login'));
        }
    }

    public function setEmailAttribute($value) {
        if ( empty($value) ) { // will check for empty string, null values, see php.net about it
            $this->attributes['email'] = NULL;
        } else {
            $this->attributes['email'] = $value;
        }
    }

    public function setPasswordAttribute($input)
    {
        if ($input)
            $this->attributes['password'] = Hash::needsRehash($input) ? Hash::make($input) : $input;
    }

    public function role()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    public static function actionCode($code)
    {

        $rand = User::where('action_code', $code)->first();
        if ($rand) {
            $digits = 4;
            $randomCode = str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);
            return $randomCode ;
        } else {
            return $code;
        }

    }

    public static function userCode($code)
    {

        $rand = User::where('action_code', $code)->first();
        if ($rand) {
            return $randomCode = rand(1000000000, 9999999999);
        } else {
            return $code;
        }
    }
}
