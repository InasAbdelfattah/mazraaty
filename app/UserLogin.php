<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserLogin extends Model
{
    protected $table='user_logins';
    
    public function user()
    {
        return $this->belongsTo(User::class);

    }

}
