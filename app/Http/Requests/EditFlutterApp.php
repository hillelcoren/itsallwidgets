<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditFlutterApp extends FormRequest
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
        return [
            //
        ];
    }
}
