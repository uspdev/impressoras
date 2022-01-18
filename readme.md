Sistema quota
=============

Model Impressora: Controle Manual de Fila, 

Se a impressora tem Controle Manual de Fila toda impressão cairá como pendente,
se não não tiver o documento irá para Processando diretamente

Model Status: 

- waiting_job_authorization (IF ENABLED)  : está aguardando autorização
- checking_user_quota (IF ENABLED) 	   : está contanto página e verificando se usuário tem quota disponível
- waiting_job_authorization (IF ENABLED)  : está aguardando autorização
- cancelled_not_authorized             : usuário sem permissão na impressora
- cancelled_user_out_of_quota  		   : usuário não tem quota disponível
- sent_to_printer_queue        		   : arquivo foi para impressora
- print_success               		   : a impressora respondeu que imprimiu com sucesso
- printer_problem             		   : quando a impressora não respondeu

Model Printing:

Fluxo de impressão: Pendente , Processando

1) O quota_check insere na tabela printings um registro com status 'Processando'
2) O quota_check conta as páginas e verifica numa rota se o usuário pode imprimir o documento
3) Se pode, o quota_check atualiza o registro em printings para o status 'Fila'.
Após a impressora responder:
 3.1) o quota_save atualiza o registro em printings para o status 'Impresso'
 3.2) o quota_save atualiza o registro em printings para o status 'Problema'
4) Se não pode, o quota_check atualiza o registro em printings para o status 'Cancelado'


Sistema desenvolvido em *laravel* + *tea4cups* para gestão de impressões no contexto 
da Universidade de São Paulo.

O fluxo do arquivo impresso é controlado em 4 estágios usando rotas do laravel e
hooks do tea4cups, na seguinte sequência:

Deploy básico para desenvolvimento:

    composer install
    php artisan migrate
    php artisan vendor:publish --provider="Uspdev\UspTheme\ServiceProvider" --tag=assets --force

Endpoins:

 - http://127.0.0.1:8000/check/fulano/let_samsung_pb_k7500lx_proaluno/3
 - http://127.0.0.1:8000/pages/today/fulano/
 
 Limpar banco:
 
     DELETE FROM printings WHERE created_at < '2020-03-08 08:00:00';

# Agradecimentos

 - [Will Gnann](https://github.com/wgnann) do IME-USP pela ajuda com a parte do framework *tea4cups*

# Rotas Api

Rota para verificar se uma pessoa (user) pode imprimir numa dada 
impressora (printer) uma certa quantidade de páginas (pages) - 
as páginas devem considerar as cópias:

   curl --header "Authorization: 123" -d '{"user": "5385361", "pages": "5", "copies": "2", "printer": "profcs", "jobid": "1", "host": "03.3094.2", "filename": "mamute.pdf", "filesize": "192045"}' -H "Content-Type: application/json" -X POST http://127.0.0.1:8000/api/check

Registrar uma tentativa de impressão

   curl --header "Authorization: 123" -d '{"jobid":"10", "pages":"5", "copies": "2", "filename": "arquivo.pdf", "filesize": "89876", "user": "5385361", "host": "10.0.25.5", "printer": "profcs"}' -H "Content-Type: application/json" -X POST http://127.0.0.1:8000/api/printings
