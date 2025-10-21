# Deploy do Impressoras com Dokku

## No servidor Dokku

### Instalação do Docker e do Dokku

```bash=
wget -NP . https://dokku.com/install/v0.36.7/bootstrap.sh
sudo DOKKU_TAG=v0.36.7 bash bootstrap.sh
```

Adicione sua chave SSH pública ao dokku:

```bash=
echo 'conteudo-da-sua-chave-publica' | sudo dokku ssh-keys:add admin
```

### Criação e configuração do app

Variáveis de ambiente:

```bash=
export ADMIN_EMAIL="leandro@if.usp.br"
export APP_NAME="impressoras"
export APP_DOMAIN="impressoras.if.usp.br"
export MARIADB_NAME="mariadb_$APP_NAME"
```

Instalação dos plugins necessários:

```bash=
sudo dokku plugin:install https://github.com/dokku/dokku-mariadb.git --name mariadb
sudo dokku plugin:install https://github.com/dokku/dokku-maintenance.git
sudo dokku plugin:install https://github.com/dokku/dokku-letsencrypt.git
```

Criação do app:

```bash=
dokku apps:create $APP_NAME
dokku checks:disable $APP_NAME
dokku domains:set $APP_NAME $APP_DOMAIN
dokku letsencrypt:set $APP_NAME email $ADMIN_EMAIL
```

O Dokku faz o link do _service_ MariaDB com a aplicação através da variável de ambiente **DATABASE_URL**. O Laravel já tem a variável no arquivo `config/database.php`. Sendo assim, só precisamos criar o banco de dados e fazer o link com a aplicação. No ".env" basta setar **DB_CONNECTION="mysql"**, os parâmetros da conexão já estarão na **DATABASE_URL**.

```bash=
dokku mariadb:create $MARIADB_NAME
dokku mariadb:link $MARIADB_NAME $APP_NAME
```

![image](https://hackmd.io/_uploads/rJwOCO22gx.png)

Criação das variáveis de ambiente para reproduzir o `.env`:

```bash=
dokku config:set --no-restart $APP_NAME \
	APP_DEBUG="false" \
	APP_ENV="production" \
	APP_KEY="base64:$(openssl rand -base64 32)" \
	APP_NAME="Impressoras" \
	APP_URL="https://impressoras.if.usp.br" \
	DB_CONNECTION="mysql" \
	TRAEFIK_ADMIN="traefik:$2y$05$5Tv/53HWaZj3XMRdlzlkGe3OXbCjHtvqIEQBVxfkEtfvrk5yHowdG" \
	TRAEFIK_HOSTNAME="traefik.usp.br" \
	IMPRESSORAS_HOSTNAME="pdf-printers.usp.br" \
	SPDF_HOSTNAME="pdf.usp.br" \
	PHP_MEMORY_LIMIT="512M" \
	PHP_UPLOAD_LIMIT="512M" \
	USAR_REPLICADO="true" \
	REPLICADO_DATABASE="meu-replicado" \
	REPLICADO_HOST="meu-host-do-replicado" \
	REPLICADO_PASSWORD="minha-senha-do-replicado" \
	REPLICADO_PORT="5000" \
	REPLICADO_SYBASE="1" \
	REPLICADO_USERNAME="meu-user-replicado" \
	REPLICADO_MONITORES="true" \
	SENHAUNICA_ADMINS="5248392,2374422" \
	SENHAUNICA_CALLBACK_ID="19" \
	SENHAUNICA_CODIGO_UNIDADE="43,66" \
	SENHAUNICA_KEY="key" \
	SENHAUNICA_SECRET="secret" \
	USP_THEME_SKIN="if" \
	CUPS_SERVER_IP="ip-do-cups" \
	PRINTING_DRIVER="cups" \
	CUPS_SERVER_USERNAME="user-do-cups" \
	CUPS_SERVER_PASSWORD="senha-do-user" \
	IMPRESSORAS_CODSLAMON="37" \
	WSFOTO_USER="IF" \
	WSFOTO_PASS="senha" \
	MOSTRAR_FOTO="1" \
	WSFOTO_DISABLE="0" \
	GS_TIMEOUT="45" \
	MAIL_DRIVER="smtp" \
	MAIL_HOST="host-do-smtp" \
	MAIL_PORT="587" \
	MAIL_USERNAME="usuario-smtp" \
	MAIL_PASSWORD="senha-smtp" \
	MAIL_ENCRYPTION="null"
```

### Volumes da aplicação

```bash=
dokku storage:ensure-directory $APP_NAME
dokku storage:mount $APP_NAME /var/lib/dokku/data/storage/$APP_NAME/storage:/var/www/html/storage
dokku storage:mount $APP_NAME /var/lib/dokku/data/storage/$APP_NAME/bootstrap-cache:/var/www/html/bootstrap/cache
```

## Na máquina de desenvolvimento

### Criação do git remote e deploy

Configuração do _git remote_ para o deploy:

```bash=
git remote add dokku dokku@<ip-ou-hostname-do-servidor-dokku>:impressoras
```

Deploy:

```bash=
# O branch padrão de deploy do dokku é o main
# Então use, por exemplo - git push dokku master:main
# Ou git push issue_xxx:main
git push dokku <algum-branch>:main
```

## Pós-deploy

Depois de subir a aplicação, entre no container e execute:

```bash=
# Se souber o nome do container e quiser rodar só uma linha de comando:
docker container exec impressoras.web.1 bash -c 'composer install --no-dev && php artisan migrate --force'

# Outra opção, entrando no container
dokku enter $APP_NAME
composer install --no-dev
php artisan migrate
```
