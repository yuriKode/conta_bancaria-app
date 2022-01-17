@extends('layouts.app')

@section('title', 'Lista Clientes')

@section('content')

<div class="px-4 py-5 my-5 text-left">
    <div class="row">
        <div class="col-md-7">
            <form method="POST" action="/generate-pdf" id="geraPDF">
                @csrf
                @method('POST')
                <input type="text" id="date" name="date" hidden>
                <button class="btn btn-outline-secondary disabled" type="submit" id="btnGeraRelatorio" >Gerar Relatório</button>
            </form>   
        </div>
        <div class="col-md-5">
            <form method="POST" action="/transacao/showByDate" autocomplete="off">
                @csrf
                @method('GET')
                <div class="input-group">
                    <input class="form-control" type="text" name="datetimes" id="datetimes" />
                    <button class="btn btn-outline-secondary" type="submit" id="filtrar">Filtrar</button>
                </div>
            </form>     
        </div>
    </div>
    <div class="row">
        <table class="table table-hover caption-top">
            <caption>Transações</caption>
            <thead>
                <tr>
                    <th scope="col">Nome do Cliente</th>
                    <th scope="col">Número da Conta</th>
                    <th scope="col">Operação Realizada</th>
                    <th scope="col">Valor</th>
                    <th scope="col" class="text-center">Data da Transação</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transacoes as $transacao)
                    <tr>
                        <th scope="row">{{ $transacao->nome }}</th>
                        <td>{{ substr($transacao->numero_conta,0,-2)."-".substr($transacao->numero_conta, -2, 2) }}</td>
                        <td>
                            @if($transacao->operacao == 'deposito')
                                Depósito
                            @else
                                Retirada
                            @endif
                        </td>
                        <td> R$
                            @if($transacao->operacao == 'retirada') - @endif
                            {{$transacao->valor }}
                        </td>
                        <td class="text-center">{{ $transacao->created_at->format('d/m/Y H:i:s') }}</td>
                    </tr>

                @endforeach
            </tbody>
        </table>
    </div>
    @if(isset($dates))
    <div> Período de: {{ $dates[0] }} até {{ $dates[1] }}</div>
    @else
    <div> {{ $transacoes->links() }}</div>
    @endif

</div>


<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script>
    $(function() {

        
        $('input[name="datetimes"]').daterangepicker({
            showDropdowns: true,
            timePicker: true,
            timePicker24Hour: true,
            autoUpdateInput: false,
            ranges: {
                'Hoje': [moment(), moment()],
                'Desde Ontem': [moment().subtract(1, 'days'), moment()],
                'Ùltimos 7 Dias': [moment().subtract(6, 'days'), moment()],
                'Ùltimos 30 Dias': [moment().subtract(29, 'days'), moment()]
            },
            locale: {
                format: 'DD/MM/YYYY HH:mm:ss',
                applyLabel: "Aplicar",
                cancelLabel: "Cancelar",
                daysOfWeek: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sab"],
                monthNames:['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio',
                            'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro',
                            'Novembro', 'Dezembro'],
                customRangeLabel: "Personalizado"
            }
        });
    });

    $('input[name="datetimes"]').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY HH:mm:ss') + ' - ' + picker.endDate.format('DD/MM/YYYY HH:mm:ss'));
        document.getElementById('btnGeraRelatorio').classList.remove('disabled');
    });

    document.getElementById("geraPDF").addEventListener('submit', function(){
        document.getElementById('date').value = document.getElementById('datetimes').value;
    });
</script>


@endsection