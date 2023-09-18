# Quotas

Sistema desenvolvido em *Laravel* para gestão de impressões no contexto da Universidade de São Paulo.

O fluxo do arquivo impresso é controlado em 4 estágios usando rotas do Laravel, na seguinte sequência:

1) status "Processando";
2) verifica numa rota se o usuário pode imprimir o documento;
3) status "Fila".

Após a impressora responder:
 3.1) status "Impresso"
 3.2) status "Problema"
4) Se não pode, status "Cancelado"

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
    php artisan key:generate
```

Para limpar banco:
```
     DELETE FROM printings WHERE created_at < '2020-03-08 08:00:00';
```

## Informações sobre os Models
Algumas informações incompletas sobre nossos models. Falta, por exemplo, informações sobre métodos.


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


# Guidelines para o desenvolvimento

- Escrever o código em inglês.

# limpar banco na unha

    delete from status;
    delete from printings;
