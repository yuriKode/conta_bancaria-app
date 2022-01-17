<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Cpf implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //retira pontuação, deixa só dígitos
        $cpf_digitos = preg_replace('/\D/',"",$value);
        
        //Testa novamente se há 11 dígitos numéricos
        $qtd_digitos_numericos = strlen($cpf_digitos);
        if($qtd_digitos_numericos == 11){
            //separa primeiros dígitos dos últimos
            $primeiros_digitos = substr($cpf_digitos,0, 9);
            $ultimos_digitos = substr($cpf_digitos,9); //Esse são os dígitos de verificação

            //transforma em array
            $array_prim_digitos = str_split($primeiros_digitos);
            $array_ult_digitos = str_split($ultimos_digitos);

            //Cálculo primeiro dígito
            //Itera pelos primeiros digitos para calcular regra de validação
            $V1 = 0;
            foreach($array_prim_digitos as $chave => $digito){
                $multiplicador = 10 - intval($chave);
                $V1 += $multiplicador * intval($digito);
            }
            
            $R1 = $V1 % 11;
            if($R1 < 2){
                $dig_validacao_1 = 0;
            }else{
                $dig_validacao_1 = 11 - $R1;
            }
            
            if(intval($array_ult_digitos[0]) != $dig_validacao_1){
                //Se o dígito de validação calculado não bate com dígito fornecido pelo usuário retorna erro
                return false;
            }

            //Cálculo segundo dígito
            $V2 = 0;
            foreach($array_prim_digitos as $chave => $digito){
                $multiplicador = 11 - intval($chave);
                $V2 += $multiplicador * intval($digito);
            }
            //coloca também valor do último dígito calculado
            $V2 += $dig_validacao_1 * 2;
            $R2 = $V2 % 11;

            if($R2 < 2){
                $dig_validacao_2 = 0;
            }else{
                $dig_validacao_2 = 11 - $R2;
            }

            if(intval($array_ult_digitos[1]) != $dig_validacao_2){
                return false;
            }

        }else{
            //Se não há 11 dígitos numéricos retorna erro
            return false;
        }
        
        //Se tudo ocorreu bem
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'O cpf fornecido é inválido';
    }
}
