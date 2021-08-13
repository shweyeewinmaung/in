<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
	public function getRouteKeyName()
    {
        return 'name';
    }
     protected $fillable = [
        'name', 'company_name', 'supplier_code','address','email','phone'
    ];
}
