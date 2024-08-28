<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteAccountRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'integer'
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'Id necessario para realizar o delete da conta do ususário',
        ];

    }
}
