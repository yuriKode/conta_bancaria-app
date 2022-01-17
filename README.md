Preparação para rodar aplicação
    Migrações banco de dados
        Caso ocorra algum erro de permissão na hora de rodar a migração, alterar DB_HOST no arquivo .env: de DB_HOST=mysql para DB_HOST=127.0.0.1, depois retornar para DB_HOST=mysql para rodar a aplicação.
    Inicialização do servidor
        Utilizei o sail como ambiente de desenvolvimento, uso linux então aqui o comando para inicializar o server pelo terminal é (de dentro da pasta da aplicação): ./vendor/bin/sail up
        Dockerizei a aplicação mais tive erros de permissão, sei com resolver localmente, então se necessário falar comigo.


Sketch da aplicação

Estilo das Views
    Bootstrap 5.1

Scripts
    Javascript e um pouco de Jquery

Estrutura do Banco de Dados (mysql)
    Tabela de clientes
        colunas
            id => primary key
            nome => string
            cpf => inteiro
            numero_conta => inteiro
            saldo => decimal com precisão de 2 dígitos
            created_at => timestamp
            updated_at[não usado] => timestamp
    Tabela de transacoes (nomeada 'transacaos' por causa do padrão laravel)
        colunas
            id => primary_key
            cliente_id => foreign Key (referência para model de clientes)
            operacao => enum ('deposito' ou 'retirada')
            valor => decimal com precisão de 2 dígitos
            created_at => timestamp
            updated_at[não usado] => timestamp
    
Funções da Aplicação
    Cadastro de Cliente
        Conteúdo da Tela
            Formulário com 2 campos: Nome e CPF.
        Validação
            Cliente-side
                Pré-validação (apenas javascript e html5)
                    Campo nome necessário
                    Campo cpf necessário, formato padrão do cpf, com prevenção de dígito não-numéricos.
                Server-side
                    Campo nome necessário
                    Campo cpf necessário, formtação correta necessária, cpf fantasia não aceito (apenas cpf's existentes), cpf deve ser único no banco de dados.

    Edição de Cliente
        Conteúdo da Tela
            Mesmos da tela de cadastro
        Validação
            Os mesmos critérios da tela de cadastro são aplicados
    Informação do Cliente
        Conteúdo da Tela
            Dados de um determinado cliente: Id, nome, número da conta, saldo em conta e data de criação da conta
            Gráfico de área contendo todas a movimentações (depósito ou retirada), respectivos saldos após as movimentações, e as datas em que acontenceram. Foi usado a api Google Charts.
    Deleção
        Não possui tela, apenas tela de report
        Função
            Deleta o cliente, e também todas as transações realizadas com seu Id
    Listagem de Clientes
        Função
            Lista todos os clientes e alguns dos seus dados em uma tabela com paginação. Esta tela possui a maioria dos caminhos para acessar as funções da aplicação (ver informações de um cliente, edição, depósito, retirada, deleção)
    Depósito
        Conteúdo Tela
            Modal adjacente a tela de listagem.
            Formulário com uma campo para dígito do valor
        Validação
            Client-side
                Não permite dígitos não numéricos, nem aceita submissões fora do padrão estabelicido. O padraõ requer valores não-núlos e que possuam no máximo dois dígitos decimais, com o ponto (.) como separador
            Server-side
                Checa se é um número decimal válido e no padrão estabelecido.
        Função
            Incrementa saldo do cliente e registra o acontecimento de uma nova transação
    Retirada
        Mesmo padrão da função de depósito, com a diferença de o valor é decrementado do saldo do cliente.
        Obs: não implementei função para bloquear possibilidade de saldo negativo.
    Listagem de transações
        Conteúdo da Tela
            Lista todas as transações ocorridas com paginação. A tela possui um seletor de datas que permite a filtragem por um determinado período (sem paginação)(Foi utilizado a api javascript date-range-picker)
            Há também um botão para gerar relatórios pdf.
    Gera relatórios
        Função
            Na tela de listagem de transaçãoes é possível escolher um determinado período e gerar um relatório. (Há um certo bug nessa tela, só é possível gerar o relatório antes de pressionar o botão de filtrar do seletor de datas, basta apenas selecionar a data e clicar no botão de gerar relatório, o tempo curto não permitiu a correção do bug.)
        
        
        

    


