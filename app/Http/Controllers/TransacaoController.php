<?php

namespace App\Http\Controllers;

use App\Models\Transacao;
use App\Models\Cliente;
use App\Http\Requests\TransacaoRequest;
use \Illuminate\Http\Request;



class TransacaoController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transacoes = Transacao::join('clientes', 'transacaos.cliente_id', '=', 'clientes.id')
                                ->select('clientes.nome', 
                                    'clientes.numero_conta', 
                                    'transacaos.operacao', 
                                    'transacaos.valor', 
                                    'transacaos.created_at')->paginate(5);
        
        if($transacoes){
            return view('transacao.index', ['transacoes' => $transacoes]);
        }else{
            abort(500);
        }

    }

    /**
     * Display a listing of the resource.
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function showByDate(Request $request)
    {
        //Transforma data para timestamp
        $datetimes = explode('-', $request->input()["datetimes"]);
        $datetimes[0] = trim($datetimes[0]);
        $datetimes[1] = trim($datetimes[1]);

        $date_ini = date_create_from_format('d/m/Y H:i:s', $datetimes[0]);
        $date_end = date_create_from_format('d/m/Y H:i:s', $datetimes[1]);

        $tm_ini = date_format($date_ini, 'Y-m-d H:i:s');
        $tm_end = date_format($date_end, 'Y-m-d H:i:s');


        $transacoes = Transacao::join('clientes', 'transacaos.cliente_id', '=', 'clientes.id')
                                    ->whereBetween('transacaos.created_at', [$tm_ini, $tm_end])
                                    ->select('clientes.nome', 
                                        'clientes.numero_conta', 
                                        'transacaos.operacao', 
                                        'transacaos.valor', 
                                        'transacaos.created_at')->get();

        if($transacoes){
            return view('transacao.index', ['transacoes' => $transacoes,
                                        'dates' => [$datetimes[0], $datetimes[1]]]);
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
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\TransacaoRequest  $request
     * @return \Illuminate\Http\Response
     */
    

    public function store(TransacaoRequest $request)
    {
        
        $validated = $request->validated();

        //Cria instância de transação
        $transacao= new Transacao;
        
        $transacao->cliente_id = $validated['cliente_id'];
        $transacao->operacao = $validated['operacao'];
        $transacao->valor = $validated['valor'];

        if($validated['operacao'] == 'deposito'){
            Cliente::where('id', $validated['cliente_id'])->increment('saldo', $validated['valor']); 
        }else{
            Cliente::where('id', $validated['cliente_id'])->decrement('saldo', $validated['valor']);
        }

        $cliente = Cliente::where('id', $validated['cliente_id'])->first();
        
        if($transacao->save()){
            return view('transacao.report', ['cliente' => $cliente,
                                            'transacao' => $transacao]);
        }else{
            abort(500);
        }    

        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transacao  $transacao
     * @return \Illuminate\Http\Response
     */
    public function show(Transacao $transacao)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transacao $transacao
     * @return \Illuminate\Http\Response
     */
    public function edit(Transacao $transacao)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\TransacaoRequest  $request
     * @param  \App\Models\Transacao $transacao
     * @return \Illuminate\Http\Response
     */
    public function update(TransacaoRequest $request, Transacao $transacao)
    {
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transacao $transacao
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transacao $transacao)
    {

    }
}
