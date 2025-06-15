<?php

namespace App\Http\Requests\Profiles;

use App\Models\TipoPerfil;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CompletarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return !empty(Auth::user());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user = Auth::user();

        /// retorna as regras de acordo com o tipo de perfil
        return TipoPerfil::RULES[$user->tipo_perfil_id] ?? [];
    }

    public function messages()
    {
        $user = Auth::user();

        /// retorna as mensagens de acordo com o tipo de perfil
        return TipoPerfil::MESSAGES[$user->tipo_perfil_id] ?? [];
    }
}
