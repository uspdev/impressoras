Sistema quota
=============

Sistema desenvolvido em *laravel* + *tea4cups* para gestão de impressões no contexto 
da Universidade de São Paulo.

O fluxo do arquivo impresso é controlado em 4 estágios usando rotas do laravel e
hooks do tea4cups, na seguinte sequência:

 1. *Processando*: O servidor cups recebe o arquivo e imediatamente o registra 
    como *Processando*. O script [quota_check](https://raw.githubusercontent.com/fflch/quota/master/tea4cups/quota_check.j2) faz a contagem de páginas e
    verifica se o usuário em questão tem permissão para continuar com a impressão
 2. *Cancelado*: Ainda no *quota_check*, se verificado que o usuário não pode imprimir 
    o referido arquivo, o status será gravado como *Cancelado*
 3. *Fila*: Se o usuário puder imprimir, o *quota_check* envia o arquivo para a impressora
    e registra o status como *Fila*
 4. *Impresso*: Quando a impressora responde ok para a impressão do arquivo, o script 
    [quota_save](https://raw.githubusercontent.com/fflch/quota/master/tea4cups/quota_save.j2) muda o status do mesmo para *Impresso*

Deploy básico para desenvolvimento:

    composer install
    php artisan migrate
    php artisan vendor:publish --provider="Uspdev\UspTheme\ServiceProvider" --tag=assets --force

Exemplo de query para simular ambiente de produção:

    INSERT INTO printings (jobid, user, filename, copies, pages, printer, created_at, updated_at, status) VALUES (100, 'fulano', 'cv.odt', 1, 2, 'gh_samsung_pb_k7500lx_proaluno', '2020-02-18 10:00:00', '2020-02-18 10:00:00', 'Impresso');

    INSERT INTO printings (jobid, user, filename, copies, pages, printer, created_at, updated_at, status) VALUES (101, 'fulano', 'cv.odt', 2, 2, 'let_samsung_pb_k7500lx_proaluno', '2020-02-18 10:00:00', '2020-02-18 10:00:00', 'Impresso');

    INSERT INTO printings (jobid, user, filename, copies, pages, printer, created_at, updated_at, status) VALUES (102, 'fulano', 'cv.odt', 1, 4, 'fcs_samsung_pb_k7500lx_proaluno', '2020-02-18 10:00:00', '2020-02-18 10:00:00', 'Impresso');

    INSERT INTO printings (jobid, user, filename, copies, pages, printer, created_at, updated_at, status) VALUES (100, 'fulano', 'cv.odt', 3, 5, 'fcs_samsung_cor_x7500lx_dcp', '2020-02-18 10:00:00', '2020-02-18 10:00:00', 'Impresso');

    INSERT INTO printings (jobid, user, filename, copies, pages, printer, created_at, updated_at, status) VALUES (100, 'fulano', 'cv.odt', 3, 50, 'fcs_samsung_pb_m4080fx_dflab103', '2020-02-18 10:00:00', '2020-02-18 10:00:00', 'Impresso');

Endpoins:

 - http://127.0.0.1:8000/check/fulano/let_samsung_pb_k7500lx_proaluno/3
 - http://127.0.0.1:8000/pages/today/fulano/
 
 Limpar banco:
 
     DELETE FROM printings WHERE created_at < '2020-03-08 08:00:00';

# Agradecimentos

 - [Will Gnann](https://github.com/wgnann) do IME-USP pela ajuda com a parte do framework *tea4cups*
