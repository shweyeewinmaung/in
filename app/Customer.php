<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    public function getRouteKeyName()
  {
        return 'name';
  }
  protected $guarded=['id'];
  
  protected $fillable = [
        'customer_number','customername_id','store_id','user_id','confirm_user_id','staff_id','customer_sign_file','status'
    ];

  public function items()
  {
      return $this->belongsToMany('App\Item')->withTimestamps();
  }
  public function staff()
  {
     return $this->belongsTo('App\Staff','staff_id');
  } 
  public function  user()
  {
     return $this->belongsTo('App\Admin','user_id');        
  }
  public function  confirmuser()
  {
        return $this->belongsTo('App\Admin','confirm_user_id');        
    }
  public function store()
  {
     return $this->belongsTo('App\Store','store_id');            
  }
  public function category()
  {
            return $this->belongsTo('App\Category','category_id');            
  }
  public function itemname()
  {
    	return $this->hasOne('App\Itemname','id','itemname_id');    	
  }
  public function  customername()
  {
     return $this->belongsTo('App\Customername','customername_id');        
  }
}
