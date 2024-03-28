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

## Dependências
Pacotes do Debian 11:
  - ghostscript
  - icc-profiles
  - libgs9-common
  - poppler-utils
  - texlive-extra-utils

## Como rodar com docker?
```sh
# build
docker build -t impressoras .

# run
docker run --rm --name impressoras --env-file=envfile impressoras

# migrations (rodar pelo menos na primeira vez)
docker exec -it impressoras php artisan migrate

# chave
# se precisar criar a chave, rode abaixo, preencha o .env e reinicie o container
docker exec -it impressoras php artisan key:generate --show
```

## Deploy básico para desenvolvimento
```sh
    composer install
    php artisan migrate
    php artisan vendor:publish --provider="Uspdev\UspTheme\ServiceProvider" --tag=assets --force
    php artisan key:generate
```

## Configurações do CUPS

É necessário um servidor CUPS com:
  - impressoras instaladas preferencialmente usando IPP;
  - ser acessível pela instância onde rodará o docker;
  - ter um usuário com acesso às impressoras.

# Informações sobre os Models
[Informações sobre os Models](models.md)

# Guidelines para o desenvolvimento

- Escrever o código em inglês.

# limpar banco na unha

Para limpar banco:
```
     DELETE FROM printings WHERE created_at < '2020-03-08 08:00:00';
```

    delete from status;
    delete from printings;
