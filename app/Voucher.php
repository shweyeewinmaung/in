<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
   protected $guarded=['id'];
   
   protected $fillable = [
        'voucher_file','supplier_id','store_id','admin_id','voucher_code'
    ];

    public function supplier()
    {
            return $this->belongsTo('App\Supplier','supplier_id');            
    }
    public function store()
    {
            return $this->belongsTo('App\Store','store_id');            
    }
    public function  user()
    {
     return $this->belongsTo('App\Admin','admin_id');        
    }
    
    
}
