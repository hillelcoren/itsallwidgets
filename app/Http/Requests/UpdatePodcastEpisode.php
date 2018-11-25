<?php

namespace App\Http\Requests;

use App\Rules\ExternalLink;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePodcastEpisode extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check() && auth()->user()->is_admin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'title' => 'required',
            'short_description' => 'required|max:250',
            'app_url' => [new ExternalLink('https://itsallwidgets.com/flutter-app/')],
            'twitter_url' => [new ExternalLink('https://twitter.com/')],
            'github_url' => [new ExternalLink('https://github.com/')],
            'reddit_url' => [new ExternalLink('https://www.reddit.com/r/FlutterDev/')],
        ];

        return $rules;
    }
}
