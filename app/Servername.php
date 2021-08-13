<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Servername extends Model
{
	public function getRouteKeyName()
    {
        return 'name';
    }
    protected $fillable = [
        'name','township','city', 'address'
    ];
}
