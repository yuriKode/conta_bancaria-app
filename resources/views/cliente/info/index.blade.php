@extends('layouts.app')

@section('title', 'Informações')

@section('content')

<div class="px-1 py-2 my-5 text-center">
    <div class="row px-5">
        <div class="col">
            <table class="table table-light table-borderless table-striped caption-top">
                <caption><b></b></caption>
                <thead>
                    <tr>
                    <th scope="col"><h1 class="display-6">{{ $cliente->nome }}</h1></th>
                    <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">CPF</th>
                        <td>{{ substr($cliente->cpf, 0, 3).".".substr($cliente->cpf, 3, 3).".".substr($cliente->cpf,6, 3)."-".substr($cliente->cpf,9) }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Número da Conta</th>
                        <td>{{ substr($cliente->numero_conta,0,-2)."-".substr($cliente->numero_conta, -2, 2)  }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Saldo</th>
                        <td>R$ {{ $cliente->saldo }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Criação da Conta</th>
                        <td>{{ $cliente->created_at->format('d/m/Y H:i:s') }}</td>
                    </tr>
                    
                </tbody>
            </table>
        </div>
    </div>
    <div class="row py-4 text-center">
        <div id="grafico"></div>
    </div>
    <div class="row py-3">
        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                <a type="button" href="/cliente" class="btn btn-light btn-lg px-4 gap-3">
                    Voltar
                </a>
        </div>
    </div>
    

</div>


<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>

    var dados = {{ Js::from($dados) }};

    console.log(dados);
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable(
          dados
        );

        var options = {
          title: 'Movimentações',
          titleTextStyle: { color: 'Gray',
                            fontName: 'Palatino',
                            fontSize: '28',
                            bold: true,
                            italic: false },
          vAxis: {title: 'Saldo'},
          hAxis: {title: 'Data da Operação'},
          animation: {"startup": true, duration: 1000,  easing: 'in'},
          focusTarget: 'category',
          fontName: 'Papyro',
          legend: {position: 'none'}
        };  


        var chart = new google.visualization.SteppedAreaChart(document.getElementById('grafico'));
        chart.draw(data, options);
    }

</script>

@endsection