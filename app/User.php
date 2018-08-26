<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Silber\Bouncer\Database\HasRolesAndAbilities;

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
}
