<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlutterApp extends Model
{
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [

    ];
}
