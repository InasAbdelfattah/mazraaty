<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

class Offer extends Model
{
    //use SoftDeletes;

     /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    //protected $dates = ['deleted_at'];

    public function items()
    {
        return $this->morphMany('App\Item', 'itemable');
    }
}
