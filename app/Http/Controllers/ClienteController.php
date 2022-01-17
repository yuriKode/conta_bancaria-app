<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Transacao;
use App\Http\Requests\StoreUpdateRequest;
use Facade\FlareClient\Http\Client;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clientes = Cliente::select('id', 'nome', 'cpf', 'numero_conta', 'saldo')->paginate(5);
        
        if($clientes){
            return view('cliente.index', ['clientes' => $clientes]);
        }else{
            abort(500);
        }
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cliente.cadastro.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    

    public function store(StoreUpdateRequest $request)
    {
        //Recebe requisição já validada pela instância StoreUpdateRequest criada
        $validated = $request->validated();

        //Gera número conta 8 dígitos, primeiro dígito diferente de zero
        $numero_conta = '';
        $num = strval(random_int(1,9));
        $numero_conta.= $num;
        for($i = 0; $i < 7; $i++){
            $numero_conta.= strval(random_int(0,9));
        }

        //Cria instância do cliente
        $cliente = new Cliente;
        
        $cliente->nome = $validated['nome'];
        $cliente->cpf = intval($validated['cpf']);
        $cliente->numero_conta = $numero_conta; 
        $cliente->saldo = 0;

        //Salva Cliente
        if($cliente->save()){
            return view('cliente.cadastro.report', ['cliente' => $cliente]);
        }else{
            abort(500);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show(Cliente $cliente)
    {
        
        $transacoes = Transacao::where('cliente_id', $cliente->id)->get();

        //formata dados para adicionar ao gráfico
        $dados[] = [['role' =>'domain', 'string' => 'Datas'], 
                    ['role' =>'data','number' => 'Saldo'], 
                    ['type' => 'string','role' => 'tooltip'],
                    ['type' => 'string','role' => 'annotation']];
        $dados[] = [$cliente->created_at->format('d/m/Y H:i:s'), 0, "Conta Criada", "Saldo: R$ 0"]; //primeira posição
        $saldo = 0;
        foreach($transacoes as $transacao){
            
            if($transacao->operacao == 'deposito'){
                $saldo += $transacao->valor;
                $msg_operacao  = 'Depósito';
            }else{
                $saldo -= $transacao->valor;
                $msg_operacao  = 'Retirada';
            }

            $msg_operacao.= ' : R$ '. $transacao->valor;
            $msg_saldo = 'Saldo : R$ '. $saldo;
            $dados[] = [$transacao->created_at->format('d/m/Y H:i:s'), $saldo, $msg_operacao, $msg_saldo];
        }

        return view('cliente.info.index', ['cliente' => $cliente,
                                    'dados' => $dados]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function edit(Cliente $cliente)
    {
        return view('cliente.edicao.index', ['cliente' => $cliente]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\StoreUpdateRequest  $request
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(StoreUpdateRequest $request, Cliente $cliente)
    {
        $validated = $request->validated();

        $cliente->nome = $validated['nome'];
        $cliente->cpf = $validated['cpf'];

        if($cliente->save()){
            return view('cliente.edicao.report', ['cliente' => $cliente]);
        }else{
            abort(500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cliente $cliente)
    {
        //Na função de deleção aqui também tenho que destruir todas as transações feitas por esse cliente
        
        $del1 = $cliente->delete();
        $del2 = Transacao::where('cliente_id', '=', $cliente->id)->delete();
        if($del1 && $del2){
            return view('cliente.delecao.report', ['cliente' => $cliente]);
        }else{
            abort(500);
        }

    }
    
}
