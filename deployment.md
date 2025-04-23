# Deployment

## Básico com Docker
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

## Básico sem docker
```sh
    composer install
    php artisan migrate
    php artisan vendor:publish --provider="Uspdev\UspTheme\ServiceProvider" --tag=assets --force
    php artisan key:generate
```

## Produção com docker

Para esta seção, usamos uma máquina com Debian (chamaremos de docker-host). Criaremos um usuário específico para rodar.

São requisitos:
  - IP na Internet e com portas TCP 80 e 443 liberadas para docker-host;
  - Oauth para a senha única;
  - Entradas de DNS para:
    - traefik (TRAEFIK_HOSTNAME): interface do serviço de proxy;
    - impressoras (IMPRESSORAS_HOSTNAME): o sistema propriamente dito;
    - SPDF (SPDF_HOSTNAME): sistema web de edição de PDF.
  - Servidor CUPS com usuário e senha.

```sh
  apt install docker.io docker-compose git
  adduser --disabled-password --ingroup docker impressoras
  su - impressoras
  # baixar o docker.compose.yml
  wget https://raw.githubusercontent.com/uspdev/impressoras/refs/heads/master/docker-compose.yml
  # baixar o .env.example (opcional)
  wget -O .env https://raw.githubusercontent.com/uspdev/impressoras/refs/heads/master/.env.example
  # editar o .env
  docker-compose up -d
  docker-compose run impressoras php artisan migrate
```


## Configurações do CUPS

É necessário um servidor CUPS com:
  - impressoras instaladas preferencialmente usando IPP;
  - ser acessível pela instância onde rodará o docker;
  - ter um usuário com acesso às impressoras.
