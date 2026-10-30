# boss-store-api

Backend da Boss Store: Laravel em modo API, com PostgreSQL e Redis via Laravel Sail.

## Rodar localmente

Pré-requisito: Docker. PHP e Composer não são necessários no host.

```sh
cp .env.example .env
docker run --rm -u "$(id -u):$(id -g)" -v "$PWD:/app" -w /app composer:2 composer install
./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan storage:link
./vendor/bin/sail artisan db:seed
```

A API responde em `APP_URL` (padrão `http://localhost:8000`). Healthcheck em `/up`.

## Contrato OpenAPI

O Scramble gera o OpenAPI a partir do código. O arquivo `openapi.json` é versionado e os fronts
geram seus tipos a partir dele. Documentação interativa em `/docs/api` (só em `APP_ENV=local`).

Depois de mudar rota, request, resource ou enum:

```sh
./vendor/bin/sail composer openapi:export
```

O teste `tests/Feature/OpenApiContractTest.php` falha se o arquivo estiver desatualizado.

## Qualidade

```sh
./vendor/bin/sail composer check   # pint --test, larastan e pest
```

Documentação completa de instalação, variáveis de ambiente e fluxo de trabalho: em breve.
