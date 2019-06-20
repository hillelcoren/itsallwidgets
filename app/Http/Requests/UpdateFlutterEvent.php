<?php

namespace App\Http\Requests;

use App\Rules\HasVariable;
use App\Rules\ExternalLink;
use Illuminate\Foundation\Http\FormRequest;

class UpdateFlutterEvent extends FormRequest
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
        $event = request()->flutter_event;

        $rules = [
            'event_name' => 'required|unique:flutter_events,event_name,' . $event->id . ',id',
            'event_date' => 'required|date',
            'address' => 'required',
            'banner' => [
                new HasVariable('$event'),
                'required',
            ],
        ];

        return $rules;
    }
}
