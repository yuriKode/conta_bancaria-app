<?php
  
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use App\Models\Transacao;
use PDF;
  
class PDFController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param $data
     * @return \Illuminate\Http\Response
     */
    public function generatePDF(Request $request)
    {

        $datetimes = explode('-', $request->input('date'));
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

        
        $pdf = PDF::loadView('transacao.pdf', ['transacoes' => $transacoes,
                                                'data_ini' => $datetimes[0],
                                                'data_end' => $datetimes[1]]);
    
        return $pdf->download('Relatório de ' .$datetimes[0].' até '.$datetimes[1]. '.pdf');
    }
}