<?php

namespace App\Http\Requests;

use App\Rules\ExternalLink;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProProfile extends FormRequest
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
        $user = auth()->user();

        $rules = [
            'name' => 'required',
            'handle' => 'required|unique:users,handle,' . $user->id . ',id',
            'avatar' => 'image|nullable|dimensions:ratio=1/1',
            'bio' => 'max:1000',
            'profile_url' => 'nullable|url',
            'website_url' => 'nullable|url',
            'github_url' => [new ExternalLink('https://github.com/')],
            'youtube_url' => [new ExternalLink('https://www.youtube.com/')],
            'twitter_url' => [new ExternalLink('https://twitter.com/')],
            'medium_url' => [new ExternalLink('https://medium.com/')],
            'linkedin_url' => [new ExternalLink('https://linkedin.com/')],
            'instagram_url' => [new ExternalLink('https://instagram.com/')],
        ];

        return $rules;
    }
}
