@extends('layouts.app')

@section('title', 'Lista Clientes')

@section('content')
<style>
.dropdown-toggle::after {
    display: none;
}
</style>
<div class="px-4 py-5 my-5 text-center">
    <div>
        <table class="table table-hover caption-top">
            <caption>Lista de Clientes</caption>
            <thead>
                <tr>
                    <th scope="col">#ID</th>
                    <th scope="col">Nome</th>
                    <th scope="col">CPF</th>
                    <th scope="col">Número da Conta</th>
                    <th scope="col" class="text-center">Saldo</th>
                    <th scope="col" class="text-center">Operações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($clientes as $cliente)
                    <tr>
                        <th scope="row">{{ $cliente->id }}</th>
                        <td>{{ $cliente->nome }}</td>
                        <td>{{ substr($cliente->cpf, 0, 3).".".substr($cliente->cpf, 3, 3).".".substr($cliente->cpf,6, 3)."-".substr($cliente->cpf,9) }}</td>
                        <td>{{ substr($cliente->numero_conta,0,-2)."-".substr($cliente->numero_conta, -2, 2) }}</td>
                        <td class="text-center">
                            <button type="button" 
                                    class="btn"
                                    tabindex="0" 
                                    data-bs-toggle="popover"
                                    data-bs-trigger="focus" 
                                    data-bs-html="true"
                                    data-bs-content="<div class='text-center'> $ {{ $cliente->saldo }} </div>">
                                    <i class="bi bi-eye-fill"></i>
                            </button>
                        </td>
                        <td class="text-center">
                            <div class="dropdown">
                                <button class="btn" type="button" id='{{ "dropdownMenuButton".$cliente->id }}' data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-list"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby='{{ "dropdownMenuButton".$cliente->id }}'>
                                    <li><a class="dropdown-item" href='{{"/cliente/".$cliente->id }}'>Informações</a></li>
                                    <li><a class="dropdown-item" href='{{"/cliente/".$cliente->id."/edit" }}'>Editar</a></li>
                                    <li>
                                        <button type="button" 
                                                class="dropdown-item"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalTransacao"
                                                data-bs-funcao="deposito" 
                                                data-bs-nome='{{ $cliente->nome }}' 
                                                data-bs-id='{{ $cliente->id }}'>
                                                Depositar
                                        </button>
                                    </li>
                                    <li>
                                      <button type="button" 
                                              class="dropdown-item"
                                              data-bs-toggle="modal" 
                                              data-bs-target="#modalTransacao"
                                              data-bs-funcao="retirada" 
                                              data-bs-nome='{{ $cliente->nome }}' 
                                              data-bs-id='{{ $cliente->id }}'>
                                              Retirar
                                      </button>
                                    </li>
                                    <li>
                                        <button type="button" 
                                                class="dropdown-item"
                                                data-bs-toggle="modal" 
                                                data-bs-target= "#modalDelete" 
                                                data-bs-nome='{{ $cliente->nome }}' 
                                                data-bs-id='{{ $cliente->id }}'>
                                                Deletar
                                        </button>
                                    </li>    
                                </ul>
                            </div>
                        </td>
                    </tr>

                @endforeach
            </tbody>
        </table>
    </div>
    <div class="align-right">{{ $clientes->links() }}</div>
</div>

<div class="modal fade" id="modalDelete" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ModalLabel">Deleção</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>                                                                                                                                                                                                                    
      <div class="modal-body">
        <p id="msgDelete"></p>
        <form id="formDelete" method="POST" action="">
            @csrf
            @method('DELETE')
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
        <button type="submit" id="btnDel" form="formDelete" class="btn btn-danger">Deletar</a>
      </div>
    </div>
  </div>
</div>

<!-- Modal para depositar e retirar valores-->
<div class="modal fade" id="modalTransacao" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tituloModalTransacao"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p id="msgTransacao"></p>
        <form id="formTransacao" method="POST" action="">
            @csrf
            @method('POST')
            <input type="text" id="cliente_id" name="cliente_id" value="" hidden>
            <input type="text" id="operacao" name="operacao" value="" hidden>
            <div class="input-group mb-3">
              <span class="input-group-text">$</span>
              <input id="valor" 
                      name="valor" 
                      type="text" 
                      class="form-control"
                      pattern="^(?!0*(\.0+)?$)(\d+|\d*\.\d{1,2})$"
                      required>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
        <button type="submit" class="btn" form="formTransacao" id="btnTransacao"></button>
      </div>
    </div>
  </div>
</div>

<script>

    //Modal delete
    var modalDelete = document.getElementById('modalDelete');
    modalDelete.addEventListener('show.bs.modal', function (event) {
            var btn = event.relatedTarget;
            var nome = btn.getAttribute('data-bs-nome');
            var id = btn.getAttribute('data-bs-id');
            var msgDelete = modalDelete.querySelector('#msgDelete');
            var formDelete = modalDelete.querySelector('#formDelete');
            
            msgDelete.innerHTML = 'Tem certeza que quer deletar o cliente <i>' + nome + '</i> ?';
            formDelete.action = "/cliente/" + id;
    });

    //Modal de transações (depositar e retirar)
    var modalTransacao = document.getElementById('modalTransacao');
    modalTransacao.addEventListener('show.bs.modal', function (event) {
            var btn = event.relatedTarget;
            var funcao = btn.getAttribute('data-bs-funcao');
            var nome = btn.getAttribute('data-bs-nome');
            var id = btn.getAttribute('data-bs-id');

            var tituloModalTransacao = modalTransacao.querySelector('#tituloModalTransacao');
            var msgTransacao = modalTransacao.querySelector('#msgTransacao');
            var formTransacao = modalTransacao.querySelector('#formTransacao');
            var operacao = modalTransacao.querySelector("#operacao");
            var btnTransacao = modalTransacao.querySelector("#btnTransacao");
            var cliente_id = modalTransacao.querySelector("#cliente_id");

            cliente_id.value = id;
            formTransacao.action = '/transacao';

            var title = "";
            if(funcao == 'deposito'){
                title = "Depositar";
                tituloModalTransacao.innerHTML = title;
                msgTransacao.innerHTML = 'Depósito a ser feito na conta de <i>' + nome + '</i>';
                btnTransacao.innerHTML = title;
                btnTransacao.classList.remove('btn-danger');
                btnTransacao.classList.add('btn-success');
                operacao.value = funcao;

            }else{

                title = "Retirar";
                tituloModalTransacao.innerHTML = title;
                msgTransacao.innerHTML = 'Retirada a ser feita na conta de <i>' + nome + '</i>';
                btnTransacao.innerHTML = title;
                btnTransacao.classList.remove('btn-success');
                btnTransacao.classList.add('btn-danger');
                operacao.value = funcao;

            }

            
    });

    //Pré-valida input de transacao
    const inputTransacao = document.querySelector('#valor');
    const MONEY_ALLOWED_CHARS_REGEXP = /[0-9\.]+/;
    inputTransacao.addEventListener("keypress", e => {
      if (!MONEY_ALLOWED_CHARS_REGEXP.test(e.key)) {
        e.preventDefault();
      }
    });
    
    function validacaoRequired(){

        if(this.validity.valueMissing){
          this.setCustomValidity('Digite um Valor');
        }else if(this.validity.patternMismatch){
          this.setCustomValidity('Não digite valores nulos\nUse o ponto como separador\nMáximo de duas casas decimais');
        }else{
          this.setCustomValidity('');
        }

    }
    
    inputTransacao.addEventListener('invalid', validacaoRequired, false);
    inputTransacao.addEventListener('input', validacaoRequired, false);
    inputTransacao.addEventListener('paste', function(e){ e.preventDefault(); });

    //Inicializa todos os popovers (saldo)
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    })
</script>

@endsection