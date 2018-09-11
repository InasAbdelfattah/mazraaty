<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    //use SoftDeletes;

     /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    //protected $dates = ['deleted_at'];


    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function items()
    {
        return $this->morphMany('App\Item', 'itemable');
    }

}
