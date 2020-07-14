<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CreateUserRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // verificar se possuí a permissão users-create e se é do team admistracao
        // ultimo parâmetro = true verifica se toda a lista de permissões colocada no array é atendida, caso false ou não indicado
        // se uma das permissões for atendida

        Log::info('Tem Permissão users-create ' . Auth::user()->isAbleTo(['users-create'], 'administracao', true));
        return Auth::user()->isAbleTo(['users-create'], 'administracao', true);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
            'team' => 'required',
        ];

        return $rules;
    }
}
