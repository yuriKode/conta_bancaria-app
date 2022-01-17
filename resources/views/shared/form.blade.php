@php

if($status == 'cadastro'){

    $titulo = 'Cadastro';
    $metodo = 'POST';
    $acao = '/cliente';
    $img = 'person-plus.svg';
    //Caso ocorra um erro na requisição recupera valor digitado, caso contrário null
    $nome = old('nome');
    $cpf = old('cpf');
    $btn_submissao = 'Cadastrar';
    $btn_voltar = '/';

}else{

    $titulo = 'Edição';
    $metodo = 'PATCH';
    $acao = '/cliente/'.$cliente->id;
    $img = 'person-check-fill.svg';
    $nome = $cliente->nome;
    $cpf = $cliente->cpf;
    $btn_submissao = 'Atualizar';
    $btn_voltar = '/cliente';
    

}

//Formata cpf
$array_cpf = [substr($cpf, 0, 3), substr($cpf, 3, 3), substr($cpf, 6, 3), substr($cpf, 9)];

@endphp



<div class="px-4 py-5 my-5 text-center">

    <img role="img" class="d-block mx-auto mb-4" src="/images/{{ $img }}" alt="" width="72" height="57">
    <h1 class="display-5 fw-bold">{{ $titulo }} de Cliente</h1>
    <div class="col-lg-6 mx-auto">
        <form id="form"  method="POST" action="{{ $acao }}" autocomplete="off">
            @csrf
            @method( $metodo )
            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" value="{{ $nome }}" required>
            </div>
            <label class="form-label">CPF</label>
            <input type="text" id="cpf" name="cpf" value="{{ $cpf }}" hidden>
            <div class="input-group mb-3">
                <input type="text" class="form-control cpf" name="cpf1" value="{{ $array_cpf[0] }}" minlength="3" maxlength="3" required>
                <span class="input-group-text">.</span>
                <input type="text" class="form-control cpf" name="cpf2" value="{{$array_cpf[1] }}" minlength="3" maxlength="3" required>
                <span class="input-group-text">.</span>
                <input type="text" class="form-control cpf" name="cpf3" value="{{ $array_cpf[2] }}" minlength="3" maxlength="3" required>
                <span class="input-group-text">-</span>
                <input type="text" class="form-control cpf" name="cpf4" value="{{ $array_cpf[3] }}" minlength="2" maxlength="2" required>
            </div>

            <div class="col-auto">
                <button type="submit" class="btn btn-primary mb-3">{{ $btn_submissao }}</button>
                <a href="{{ $btn_voltar }}" class="btn btn-secondary mb-3">Voltar</a>
            </div>
        </form>
    </div>
    <div class="col-lg-8 mx-auto">
        @if ($errors->any())
        <div class="alert alert-light" role="alert">
            <ul class="list-group">
                @foreach ($errors->all() as $error)
                <li class="list-group-item"><i class="bi bi-exclamation-circle"></i> &nbsp;{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
    
</div>
<script>
    const form = document.getElementById("form");
    const nome = document.getElementById('nome');
    const cpf_elements = document.getElementsByClassName('cpf');

    //Pré validação
    //ver se tem nome
    function validacaoRequired(){
        if(this.name =='nome'){
            msg = "Digite um nome!";
        }else{
            msg = "Campo necessário!";
        }

        if(this.validity.valueMissing){
            this.setCustomValidity(msg);
        }else{
            if(this.validity.tooShort){
                this.setCustomValidity("Esse campo deve conter " + this.minLength + " dígitos");
            }else{
                this.setCustomValidity('');
            }
            
        }

    }
    //campo (nome)
    
    nome.addEventListener('invalid', validacaoRequired, false);
    nome.addEventListener('input', validacaoRequired, false);
    
    //campos (cpf)
    for (var i = 0; i < cpf_elements.length; i++) {
        //Não permite digitos não numéricos
        cpf_elements[i].addEventListener("keypress", function(e) {
            if ((e.which < 48 || e.which > 57) && e.which !=10) {
                e.preventDefault();
            }
        });
        //Algoritmo para copiar e colar populando a cpf em dois formatos
        //'abcdefghijk' ou 'abc.def.ghi-jk' (com [a, b,..., k] sendo dígitos numéricos )
        cpf_elements[i].addEventListener('paste', function(e){

            e.preventDefault();
            var pastedValue = e.clipboardData.getData('Text');

            const regex1 = /^\d{11}$/;
            const regex2 = /^\d{3}\.\d{3}\.\d{3}-\d{2}$/;
            const found1 = pastedValue.match(regex1);
            const found2 = pastedValue.match(regex2);
            let value_cpf = [];

            if(found1 != null){
                value_cpf.push(pastedValue.substr(0,3));
                value_cpf.push(pastedValue.substr(3,3));
                value_cpf.push(pastedValue.substr(6,3));
                value_cpf.push(pastedValue.substr(9));
            }else{

                if(found2 != null){
                    value_cpf.push(pastedValue.substr(0,3));
                    value_cpf.push(pastedValue.substr(4,3));
                    value_cpf.push(pastedValue.substr(8,3));
                    value_cpf.push(pastedValue.substr(12));
                }

            }

            if(found1 != null || found2 != null){
                document.getElementsByName('cpf1')[0].value = value_cpf[0];
                document.getElementsByName('cpf2')[0].value = value_cpf[1];
                document.getElementsByName('cpf3')[0].value = value_cpf[2];
                document.getElementsByName('cpf4')[0].value = value_cpf[3];
            }
        
        });

        //checa se contém valores em todos os campos
        cpf_elements[i].addEventListener('invalid', validacaoRequired, false);
        cpf_elements[i].addEventListener('input', validacaoRequired, false);
    }

    //Evento submit do form
    form.addEventListener('submit', function(e) {
       
        var cpf = "";        
        for (var i = 0; i < cpf_elements.length; i++) {
            //Emenda cpf digitado
            cpf = cpf + cpf_elements[i].value;
            if(i == cpf_elements.length - 1){
                document.getElementById("cpf").value =  cpf;
                document.querySelectorAll('.cpf').forEach(function(el){
                    el.setAttribute('name','');
                    el.setAttribute('value','');
                })
                
            }
        }

    });
 
</script>