<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jobtitle extends Model
{
    public function getRouteKeyName()
    {
        return 'name';
    }
     protected $fillable = [
        'name'
    ];
}
