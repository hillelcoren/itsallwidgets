<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\FullTextSearch;

class FlutterChannel extends Model
{
    use FullTextSearch;

    protected $searchable = [
        'name',
        'description',
    ];

    public function scopeVisible($query)
    {
        $query->where('is_visible', '=', true);
    }

    public function scopeEnglish($query)
    {
        $query->where('language_id', '=', 1);
    }
}
