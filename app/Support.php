<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function children()
    {
        return $this->hasMany(Support::class, 'parent_id');
    }
}