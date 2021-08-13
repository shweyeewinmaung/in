<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Itemname extends Model
{
    public function getRouteKeyName()
    {
        return 'name';
    }
   protected $fillable = [
        'name','account_code','category_id','itemname_file'
    ];
     public function category()
    {
    	return $this->belongsTo('App\Category','category_id');    	
    }
}
