<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RuleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'                  => 'required',
            'authorization_control' => 'required',
            'type_of_control'       => 'nullable',
            'quota'                 => 'nullable|integer|min:0|required_with:type_of_control',
            'categorias'            => 'nullable'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => "O nome é obrigatório.",
            'authorization_control.required' => "É obrigatório selecionar as opções para controle de autorização.",
            'quota.min' => "A quota não pode ser negativa.",
            'quota.required_with' => "Preencha a quota para o período.",
        ];
    }
}
