<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditFlutterEvent extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $event = request()->flutter_event;

        if (! $event) {
            return false;
        }

        return auth()->check() && auth()->user()->owns($event);
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
