<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFlutterApp extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $app = request()->flutter_app;

        return auth()->check() && auth()->user()->owns($app);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $app = request()->flutter_app;

        $rules = [
            'title' => 'required|unique:flutter_apps,title,' . $app->id . ',id',
            'screenshot' => 'image|mimes:png|dimensions:width=1080,height=1920',
            'short_description' => 'required|max:250',
            'long_description' => 'required',
        ];

        if (request()->apple_url) {
            $rules['apple_url'] = 'unique:flutter_apps,apple_url,' . $app->id . ',id';
        }

        if (request()->google_url) {
            $rules['google_url'] = 'unique:flutter_apps,google_url,' . $app->id . ',id';
        }

        return $rules;
    }
}
