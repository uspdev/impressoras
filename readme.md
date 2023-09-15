# Quotas

Sistema desenvolvido em *Laravel* + *tea4cups* para gestão de impressões no contexto da Universidade de São Paulo.

O fluxo do arquivo impresso é controlado em 4 estágios usando rotas do Laravel e
hooks do tea4cups, na seguinte sequência:

1) O quota_check insere na tabela *printings* um registro com status "Processando"
2) O quota_check conta as páginas e verifica numa rota se o usuário pode imprimir o documento
3) Se pode, o quota_check atualiza o registro em printings para o status "Fila".

Após a impressora responder:
 3.1) o quota_save atualiza o registro em printings para o status "Impresso"
 3.2) o quota_save atualiza o registro em printings para o status "Problema"
4) Se não pode, o quota_check atualiza o registro em printings para o status "Cancelado"

## Deploy básico para desenvolvimento
Pacotes:
  - ghostscript
  - icc-profiles
  - libgs9-common
  - poppler-utils
  - texlive-extra-utils

```sh
    composer install
    php artisan migrate
    php artisan vendor:publish --provider="Uspdev\UspTheme\ServiceProvider" --tag=assets --force
```

Endpoints:

 - http://127.0.0.1:8000/check/fulano/let_samsung_pb_k7500lx_proaluno/3
 - http://127.0.0.1:8000/pages/today/fulano/
 
Para limpar banco:
 
```
     DELETE FROM printings WHERE created_at < '2020-03-08 08:00:00';
```

## Informações sobre os Models


### Model Printer

- name [str]: Nome da impressora
- machine_name [str]: nome de máquina da impressora
- rule_id [foreign_key]: Regra da impressora

### Model Rule

- name [str]: Nome da regra
- queue_control [bool]: controle da fila para autorização de impressões ativado ou não
- quota_period [str]: tipo de quota: diária ou mensal
- quota [int]: quantidade de quota disponível para o período
- categories [str]: categorias autorizadas para a impressão

### Model Statusdelete from status;
delete from printings;

- name [str]: Nome do status
- reason [str]: razão do status

#### Status disponíveis

- "waiting_job_authorization"   : está aguardando autorização
- "cancelled_not_authorized"    : cancelado pelo autorizador
- "cancelled_not_allowed"       : Usuário não tem permissão na impressora
- "cancelled_user_out_of_quota" : usuário não tem quota disponível
- "cancelled_timeout"           : arquivo não foi autorizado no tempo disponível e foi cancelado automaticamente
- "sent_to_printer_queue"       : arquivo foi para impressora
- "print_success"               : a impressora respondeu que imprimiu com sucesso
- "printer_problem"             : quando a impressora não respondeu

### Model Printing

- jobid [int]: ID do job gerado na impressora
- pages [int]: Quantidade de páginas do arquivo
- copies [int]: Quantidade de cópias
- filename [str]: Nome do arquivo
- filesize [int]: Tamanho do arquivo em KB
- user [str]: Número USP
- host [str]: IP ou hostname da máquina que disparou a impressão
- printer_id [foreing_key]: Impressora

# Rotas Api

## api/printings

Rota para verificar se uma pessoa (user) pode imprimir, deve receber os seguintes parâmetros POST obrigatoriamente:

- "jobid"
- "user"
- "pages"
- "copies"
- "printer"
- "host"
- "filename"
- "filesize"

Importante: A impressora passada na variável "printer" será criada dinamincamente caso não exista.

Retorno:

- "yes": se o usuário puder imprimir
- "no": se o usuário não puder imprimir

Exemplo de requisição POST (QUOTA_CHECK) na rota api/printings:

```sh
   curl --header "Authorization: 123"         \
     -H "Content-Type: application/json"      \
     -X POST http://127.0.0.1:8000/api/printings  \
     -d '{
            "user": "5385361",
            "pages": "5",
            "copies": "3",
            "printer": "financeiro_ppd",
            "jobid": "21",                       
            "host": "10.89.9.5",
            "filename": "mamute.pdf",
            "filesize": "192045"
          }'              
```
Exemplo de requisição POST (QUOTA_SAVE) no rota api/printings/PRINTING_ID:

```sh
   curl --header "Authorization: 123"         \
     -H "Content-Type: application/json"      \
     -X POST http://127.0.0.1:8000/api/printings/financeiro_ppd/21  \
     -d '{
            "status": "print_success"
          }'              
```
Exemplo de requisição quando o problema está na IMPRESSORA: 

```sh
   curl --header "Authorization: 123"         \
     -H "Content-Type: application/json"      \
     -X POST http://127.0.0.1:8000/api/printings/financeiro_ppd/21  \
     -d '{
            "status": "printer_problem"
          }'              
```

# Guidelines para o desenvolvimento

- Escrever o código em inglês.

# limpar banco na unha

    delete from status;
    delete from printings;

# Agradecimentos

 - [Will Gnann](https://github.com/wgnann) do IME-USP pela ajuda com a parte do framework *tea4cups*
