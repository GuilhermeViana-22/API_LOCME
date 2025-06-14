<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class MeRequest extends FormRequest
{
    public function authorize(){
        return !empty(Auth::user());
    }

    public function rules()
    {
        return [];
    }
}
