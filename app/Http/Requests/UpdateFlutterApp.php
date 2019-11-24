<?php

namespace App\Http\Requests;

use App\Rules\ExternalLink;
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

        if (! $app) {
            return false;
        }

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
            'gif' => 'mimes:gif|dimensions:width=1080,height=1920|max:10000',
            'flutter_web_url' => 'required_if:is_web,1|nullable|unique:flutter_apps,flutter_web_url,' . $app->id . ',id',
            'screenshot' => 'image||nullable|mimes:png|dimensions:width=1080,height=1920|max:2500',
            'screenshot_1' => 'image|mimes:png|dimensions:width=1080,height=1920|max:2500',
            'screenshot_2' => 'image|mimes:png|dimensions:width=1080,height=1920|max:2500',
            'screenshot_3' => 'image|mimes:png|dimensions:width=1080,height=1920|max:2500',
            'short_description' => 'required|max:250',
            'long_description' => 'required',
            'repo_url' => [new ExternalLink('https://github.com/', 'https://bitbucket.org/')],
            'google_url' => [new ExternalLink('https://play.google.com/')],
            'apple_url' => [new ExternalLink('https://itunes.apple.com/', 'https://apps.apple.com/')],
            'facebook_url' => [new ExternalLink('https://www.facebook.com/')],
            'twitter_url' => [new ExternalLink('https://twitter.com/')],
            'youtube_url' => [new ExternalLink('https://www.youtube.com/embed/')],
            'instagram_url' => [new ExternalLink('https://www.instagram.com/')],
        ];

        if (request()->is_template) {
            $rules['repo_url'][] = 'required';
        }

        if (request()->apple_url) {
            $rules['apple_url'][] = 'unique:flutter_apps,apple_url,' . $app->id . ',id';
        }

        if (request()->google_url) {
            $rules['google_url'][] = 'unique:flutter_apps,google_url,' . $app->id . ',id';
        }

        return $rules;
    }
}
