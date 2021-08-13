<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customername extends Model
{
    protected $fillable = [
        'name','code','email', 'phone','lat','lng','township', 'city','address'
    ];
}
