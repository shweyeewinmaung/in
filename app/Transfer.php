<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
	protected $guarded=['id'];
  
    protected $fillable = [
        'transfer_number','user_id','confirm_user_id','staff_id','transfer_sign_file','status','from','to','content','store_id'
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
  public function storefrom()
  {
      return $this->belongsTo('App\Store','from');
      
  }
   
  public function storeto()
  {
     return $this->belongsTo('App\Store','to');            
  }
  public function category()
  {
            return $this->belongsTo('App\Category','category_id');            
  }
  public function itemname()
  {
    	return $this->hasOne('App\Itemname','id','itemname_id');    	
  }
  
    
}
