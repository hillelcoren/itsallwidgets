<?php

namespace App\Http\Requests;

use App\Rules\ExternalLink;
use Illuminate\Foundation\Http\FormRequest;

class StoreFlutterApp extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'title' => 'required|unique:flutter_apps',
            'slug' => 'required|unique:flutter_apps',
            'gif' => 'mimes:gif|dimensions:width=1080,height=1920|max:10000',
            'gif_desktop' => 'mimes:gif|dimensions:width=1280,height=800|max:10000',
            'flutter_web_url' => 'required_if:is_web,1|nullable|url|unique:flutter_apps',
            'screenshot' => 'required_if:is_mobile,1|nullable|image|mimes:png|dimensions:width=1080,height=1920|max:2500',
            'screenshot_1' => 'image|mimes:png|dimensions:width=1080,height=1920|max:2500',
            'screenshot_2' => 'image|mimes:png|dimensions:width=1080,height=1920|max:2500',
            'screenshot_3' => 'image|mimes:png|dimensions:width=1080,height=1920|max:2500',
            'screenshot_desktop' => 'required_if:is_desktop,1|nullable|image|mimes:png|dimensions:width=1280,height=800|max:2500',
            'short_description' => 'required|max:250',
            'long_description' => 'required',
            'repo_url' => [new ExternalLink('https://github.com/', 'https://bitbucket.org/')],
            'google_url' => [new ExternalLink('https://play.google.com/')],
            'apple_url' => [new ExternalLink('https://itunes.apple.com/', 'https://apps.apple.com/')],
            'microsoft_url' => [new ExternalLink('https://microsoft.com/')],
            'snapcraft_url' => [new ExternalLink('https://snapcraft.io/')],
            'facebook_url' => [new ExternalLink('https://www.facebook.com/')],
            'twitter_url' => [new ExternalLink('https://twitter.com/')],
            'youtube_url' => [new ExternalLink('https://www.youtube.com/embed/')],
            'instagram_url' => [new ExternalLink('https://www.instagram.com/')],
            'terms' => 'required',
        ];

        if (request()->apple_url) {
            $rules['apple_url'][] = 'unique:flutter_apps';
        }

        if (request()->google_url) {
            $rules['google_url'][] = 'unique:flutter_apps';
        }

        return $rules;
    }
}
