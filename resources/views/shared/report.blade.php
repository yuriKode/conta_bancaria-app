@php

$cpf_formatado = substr($cliente->cpf, 0, 3).".".substr($cliente->cpf, 3, 3).".".substr($cliente->cpf,6, 3)."-".substr($cliente->cpf,9);
$numero_conta_formatado = substr($cliente->numero_conta,0,-2)."-".substr($cliente->numero_conta, -2, 2);

if($status == 'cadastro'){
  $operacao = 'Cadastrado';
  $href_btn_voltar = '/';
  $msg_btn_voltar = 'Voltar para Tela Inicial';
}elseif($status == 'edicao'){
  $operacao = 'Editado';
  $href_btn_voltar = '/cliente';
  $msg_btn_voltar = 'Voltar';
}else{
  $operacao = 'Deletado';
  $href_btn_voltar = '/cliente';
  $msg_btn_voltar = 'Voltar';
}

@endphp


<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">

  <symbol id="check2-circle" viewBox="0 0 16 16">
    <path d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0z"></path>
    <path d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l7-7z"></path>
  </symbol>

  <symbol id="bookmark-star" viewBox="0 0 16 16">
    <path d="M7.84 4.1a.178.178 0 0 1 .32 0l.634 1.285a.178.178 0 0 0 .134.098l1.42.206c.145.021.204.2.098.303L9.42 6.993a.178.178 0 0 0-.051.158l.242 1.414a.178.178 0 0 1-.258.187l-1.27-.668a.178.178 0 0 0-.165 0l-1.27.668a.178.178 0 0 1-.257-.187l.242-1.414a.178.178 0 0 0-.05-.158l-1.03-1.001a.178.178 0 0 1 .098-.303l1.42-.206a.178.178 0 0 0 .134-.098L7.84 4.1z"></path>
    <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.777.416L8 13.101l-5.223 2.815A.5.5 0 0 1 2 15.5V2zm2-1a1 1 0 0 0-1 1v12.566l4.723-2.482a.5.5 0 0 1 .554 0L13 14.566V2a1 1 0 0 0-1-1H4z"></path>
  </symbol>

  <symbol id="grid-fill" viewBox="0 0 16 16">
    <path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5v-3zm8 0A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5v-3zm-8 8A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5v-3zm8 0A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5v-3z"></path>
  </symbol>

</svg>
<div class="modal modal-tour position-static d-block bg-secondary py-2" tabindex="-1" role="dialog" id="modalTour">
  <div class="modal-dialog" role="document">
    <div class="modal-content rounded-6 shadow">
      <div class="modal-body p-5">
        <h2 class="fw-bold mb-0">Cliente {{ $operacao }}</h2>

        <ul class="d-grid gap-4 my-5 list-unstyled">
          <li class="d-flex gap-4">
            <svg class="bi text-muted flex-shrink-0" width="48" height="48"><use xlink:href="#bookmark-star"></use></svg>
            <div>
              <h5 class="mb-0">Cliente</h5>
              {{ $cliente->nome }}
            </div>
          </li>
          <li class="d-flex gap-4">
            <svg class="bi text-warning flex-shrink-0" width="48" height="48"><use xlink:href="#check2-circle"></use></svg>
            <div>
              <h5 class="mb-0">CPF</h5>
              {{ $cpf_formatado }}
            </div>
          </li>
          <li class="d-flex gap-4">
            <svg class="bi text-primary flex-shrink-0" width="48" height="48"><use xlink:href="#grid-fill"></use></svg>
            <div>
              <h5 class="mb-0">Número da conta</h5>
              {{ $numero_conta_formatado }}
            </div>
          </li>
        </ul>
        <a href='{{ $href_btn_voltar }}' class="btn btn-lg btn-primary mt-5 w-100" >{{ $msg_btn_voltar }}</a>
      </div>
    </div>
  </div>
</div>