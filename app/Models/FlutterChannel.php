<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlutterChannel extends Model
{
    public function scopeVisible($query)
    {
        $query->where('is_visible', '=', true);
    }
}
