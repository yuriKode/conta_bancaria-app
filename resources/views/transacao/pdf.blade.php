<!DOCTYPE html>
<html>

<head>
     <title>Relatório de Transações</title>
</head>

<body>
    <h1>Relatório de Transações</h1>
    <h2>Período: {{ $data_ini }} até {{ $data_end }}</h2>
    <table>
            <caption>Transações</caption>
            <thead>
                <tr>
                    <th >Nome do Cliente</th>
                    <th>Número da Conta</th>
                    <th>Operação Realizada</th>
                    <th>Valor</th>
                    <th>Data da Transação</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transacoes as $transacao)
                    <tr>
                        <th>{{ $transacao->nome }}</th>
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
                        <td>{{ $transacao->created_at->format('d/m/Y H:i:s') }}</td>
                    </tr>

                @endforeach
            </tbody>
        </table>
</body>

</html>