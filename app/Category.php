<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function getRouteKeyName()
    {
        return 'title';
    }

    protected $fillable = [
        'title', 'file','mac','serial'
    ];
}
