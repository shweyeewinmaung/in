<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
   public function getRouteKeyName()
    {
        return 'name';
    }
    protected $fillable = [
        'name','address'
    ];
}
