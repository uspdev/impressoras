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
  - parallel
  - pdftk
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


################
## Utilização ##
################

Com a implantação do sistema "impressoras", o fluxo de impressão deixa de ser o usuário imprimindo algo direto da aplicação Windows/Linux e passa a ser:
- "imprimir" pelo Windows/Linux escolhendo a opção de gerar PDF;
- acessar o sistema "impressoras";
- subir o PDF para que o sistema "impressoras" realize a impressão.


#####################
## Funcionalidades ##
#####################

- menu "Enviar impressão": permite que usuários enviem documentos para impressão;
- menu "Minhas impressões": permite que o usuário consulte histórico de suas impressões;
- menu "Todas as impressões": permite que admins e monitores consultem histórico de impressões;
                              permite que admins e monitores consultem histórico de impressões de quaisquer usuários e de quaisquer arquivos;
- menu "Impressoras": permite que admins cadastrem, alterem e excluam impressoras, especificando quais regras de quotas serão utilizadas;
                      permite que admins e monitores consultem filas de impressão, autorizem ou desautorizem impressões, e consultem histórico de autorizações de impressões;
- menu "Regras": permite que admins cadastrem, alterem e excluam regras de quotas para grupos de usuários;
- menu "Usuários locais": permite que admins cadastrem, alterem e excluam usuários na base de dados local do "impressoras".


#################
## Observações ##
#################

- os monitores são definidos pelas tabelas BENEFICIOALUCONCEDIDO e BENEFICIOALUNO do Replicado, ou em variável de configuração no .env;
- tudo a que um monitor tem acesso, os admins também têm acesso;
- no cadastro da regra, pode-se ligar ou desligar a obrigatoriedade de ter autorização manual por parte dos monitores para cada solicitação de impressão.
