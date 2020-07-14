<?php

namespace App\Http\Requests;


use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class ListUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // verificar se possuí a permissão users-list e se é do team admistracao
        // ultimo parâmetro = true verifica se toda a lista de permissões colocada no array é atendida, caso false ou não indicado
        // se uma das permissões for atendida

        Log::info('Tem Permissão users-list ' . Auth::user()->hasPermission('users-list'));
        return Auth::user()->isAbleTo('users-list', 'administracao');
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
