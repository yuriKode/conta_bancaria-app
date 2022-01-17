<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Cpf;
use Illuminate\Validation\Rule;

class StoreUpdateRequest extends FormRequest
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
        if($this->isMethod('post')){
            $valida_unique_cpf = 'unique:App\Models\Cliente,cpf';
        }else{
            $valida_unique_cpf = Rule::unique('App\Models\Cliente')->ignore($this->cliente->id);
        }

        
        return [
            'nome' => ['required', 'string'],
            'cpf' => ['required', 'regex:/^\d{11}$/',  new Cpf,  $valida_unique_cpf]
        ];
    }

    public function messages()
    {
        return [
            'nome.required' => 'O campo nome é necessário.',
            'nome.string' => 'O campo nome deve ser uma string.',
            'cpf.required' => 'O campo cpf é necessário.',
            'cpf.regex' => 'O cpf deve conter 11 dígitos numéricos',
            'cpf.required' => 'O cpf já foi cadastrado.',
            'cpf.unique' => 'Este cpf já possui uma conta.',
        ];
    }
}
