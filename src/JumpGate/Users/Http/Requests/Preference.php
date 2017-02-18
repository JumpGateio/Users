<?php

namespace JumpGate\Users\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Preference extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->redirect = route('user.profile') . '#preferences';

        return [
            'preference' => 'required|array',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }
}
