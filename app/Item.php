<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
   protected $guarded=['id'];
   
   protected $fillable = [
        'itemname_id','model','mac','serial_number','voucher_id','store_id','unit_price','amount','qty','used_qty','transfer_qty','damage_qty','damage_reason','category_id'
    ];
    public function supplier()
    {
            return $this->belongsTo('App\Supplier','supplier_id');            
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
    public function itemname1()
    {
        //return $this->belongsTo('App\Itemname','itemname_id');  
        return $this->hasMany('App\Itemname','id','itemname_id');    
    }
    public function vouchers()
    {
        //return $this->hasMany('App\Voucher','id','voucher_id');
         return $this->belongsTo('App\Voucher','voucher_id');     
    }
 
}
