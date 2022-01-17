<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransacaoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    protected $redirect = '/error';

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
       
        return ['cliente_id' => ['required'],
                'valor' => ['required', 'numeric'],
                'operacao' => ['required', 'string']
        ];
    }

    public function messages()
    {
        return [
            'valor.required' => 'Um valor é necessário.',
            'valor.numeric' => 'O valor deve ser numérico.',
        ];
    }
}
