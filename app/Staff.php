<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
   public function getRouteKeyName()
    {
        return 'name';
    }
     protected $fillable = [
        'name', 'jobtitle_id', 'address','email','phone'
    ];
    public function jobtitle()
    {
            return $this->belongsTo('App\Jobtitle','jobtitle_id');
            //return $this->hasMany('App\Jobtitle','id','jobtitle_id');            
    }
}
