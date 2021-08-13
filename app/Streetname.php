<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Streetname extends Model
{
   public function getRouteKeyName()
    {
        return 'name';
    }
    protected $fillable = [
         'name','lat','lng','street','township', 'city','address'
    ];
}
