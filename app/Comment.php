<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
   protected $guard="admin";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'content', 'user_id', 'commendable_id','commendable_type'
    ];
    
    public function commendable()
    {
    	return $this->morphTo();
    }
    public function admin()
    {
        return $this->belongsTo('App\Admin','user_id');
    }
}
