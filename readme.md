# Quotas

Sistema desenvolvido em *Laravel* + *tea4cups* para gestão de impressões no contexto 
da Universidade de São Paulo.

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
- authorization_control [bool]: controle de fila ativado ou não
- type_of_control [str]: tipo de quota: diária ou mensal
- quota [int]: quantidade de quota disponível para o período
- categories [str]: categorias autorizadas para a impressão

### Model Status

- name [str]: Nome do status
- reason [str]: razão do status

#### Status disponíveis

- "waiting_job_authorization"   : está aguardando autorização
- "checking_user_quota"   	    : está contanto página e verificando se usuário tem quota disponível
- "cancelled_not_authorized"    : usuário sem permissão na impressora
- "cancelled_user_out_of_quota" : usuário não tem quota disponível
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

## api/check

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

Exemplo de requisição POST no rota /api/check:

```sh
   curl --header "Authorization: 123"         \
     -H "Content-Type: application/json"      \
     -X POST http://127.0.0.1:8000/api/check  \
     -d '{
            "user": "5385361",
            "pages": "5",
            "copies": "2",
            "printer": "impressora_proaluno_letras",
            "jobid": "1",                       
            "host": "10.89.9.5",
            "filename": "mamute.pdf",
            "filesize": "192045"
          }'              
```

## api/store

Registrar uma tentativa de impressão

```sh
   curl --header "Authorization: 123"           \
   -H "Content-Type: application/json"          \
   -X POST http://127.0.0.1:8000/api/printings  \
   -d '{
            "jobid":"10",
            "pages":"5",
            "copies": "2",
            "filename": "arquivo.pdf",
            "filesize": "89876",
            "user": "5385361",
            "host": "10.0.25.5",
            "printer": "profcs"
        }'  
```

# Guidelines para o desenvolvimento

- Escrever o código em inglês.

# Agradecimentos

 - [Will Gnann](https://github.com/wgnann) do IME-USP pela ajuda com a parte do framework *tea4cups*
