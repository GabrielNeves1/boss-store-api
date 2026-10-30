# boss-store-api

Backend da Boss Store: Laravel em modo API, sem views. Atende dois clientes:
- `boss-store-web`: catálogo público (Nuxt 4, SSR), sem autenticação.
- `boss-store-admin`: painel do sistema (Quasar), autenticado com Sanctum SPA por cookie.

Produto: sistema de gestão (estoque, vendas com leitura de QR code, comissões, despesas, nota
fiscal via API) e catálogo público de uma loja de iPhones, que mostra unidades reais do estoque.
Sem multi-tenancy, mas nada acoplado a uma loja específica: nome, contato e identidade visual
nunca ficam no código. O nome inicial da loja entra só no seeder.

## Regra principal: zero hardcode
Nenhum dado, texto, configuração, URL, cor, número mágico ou regra de negócio fixo no código.
Esta API é a fonte de verdade dos dados de negócio e do contrato dos dois fronts.

- **Dados de negócio:** saem só daqui, em JSON, via Resources ou Data. Dados de demonstração só
  via factories e seeders.
- **Contrato:** o OpenAPI é gerado do código pelo Scramble e versionado em `openapi.json`. Os
  fronts geram seus tipos a partir dele. Mudou rota, request, resource ou enum: regenere o
  `openapi.json` no mesmo commit. O CI falha se ele estiver desatualizado.
- **Enums e status:** backed enums PHP em `app/Enums`, expostos com valor e rótulo. Os rótulos
  vêm dos arquivos de idioma da API. Os fronts nunca traduzem nem redefinem rótulos de negócio.
- **Identidade da loja** (nome, logos, cores, fontes, raio, WhatsApp, locale, moeda): settings no
  banco (spatie/laravel-settings, `app/Settings`), expostas por endpoint público.
- **Valores padrão:** um único arquivo, `config/branding.php`. É usado como default das settings
  e pelo seeder, nunca lido diretamente por regra de negócio.
- **Configuração e segredos:** `.env`, documentado em `.env.example`. **`env()` só dentro de
  `config/`**; no resto do código use `config()` (de preferência os acessores tipados, como
  `Config::string()`).
- **Se algo exigir valor fixo, pare e pergunte onde esse valor deve morar.**

## Arquitetura
- Rotas sob o prefixo **`/api/v1`**.
- **Controllers finos:** recebem o Form Request, chamam uma Action e devolvem Resource ou Data.
  Nada de regra de negócio, query ou validação no controller.
- **Actions** (`app/Actions`): uma classe por caso de uso, com um único método público. Toda a
  lógica fica aqui.
- **Validação:** Form Requests, sempre. Nunca `$request->validate()` no controller.
- **Saída:** API Resources ou spatie/laravel-data (`app/Data`). Use o formato que o Scramble
  documenta corretamente e confira o `openapi.json` gerado.
- **Tipagem estrita:** `declare(strict_types=1);` em todo arquivo PHP, tipos em parâmetros,
  retornos e propriedades. Prefira classes `final` e propriedades `readonly`.
- **Autenticação do painel:** Sanctum SPA por cookie. Domínios stateful, origens CORS,
  `SESSION_DOMAIN`, cookies seguros e SameSite vêm do `.env`. Nunca CORS `*` com credenciais.
- **Uploads:** SVG enviado pelo painel (ex.: logo) é sanitizado no servidor antes de salvar.
- **Fontes da marca:** self-hosted aqui, com URLs nas settings. Só fontes com licença que permita
  uso web (ex.: SIL OFL). Registre a licença junto do arquivo.
- Identificadores de código em inglês. Textos para usuário em arquivos de idioma (pt-BR).

## Ambiente local
- Tudo roda em containers pelo **Laravel Sail**, com PostgreSQL e Redis. Não há PHP nem Composer
  instalados no host.
- Use `./vendor/bin/sail <comando>` (`sail artisan`, `sail composer`, `sail test`, ...).
- Versões de PHP, PostgreSQL e Redis fixadas na configuração do Sail.
- Porta e URL da API, URLs dos fronts e demais endereços vêm do `.env`. Padrão local: API em
  `localhost:8000`, web em `localhost:3000`, admin em `localhost:9000`.
- Antes de instalar ou atualizar pacotes, confira a versão atual na documentação oficial ou no
  Packagist. Não confie em versões de memória.

## Qualidade
Tudo precisa passar antes de cada commit:
- **Pint** (estilo), **Larastan** (análise estática) e **Pest** (testes).
- Não baixe o nível do Larastan, não crie baseline e não adicione `ignoreErrors` sem pedir.
- Todo endpoint tem teste de feature em Pest, cobrindo sucesso, validação e autorização.
- `openapi.json` atualizado.

## Código limpo
- Nomes claros, sem abreviação obscura, sem código morto e sem comentário óbvio.
- Comentário só para explicar o porquê de algo não evidente.

## Git
- Conventional commits. Um commit por etapa pequena.
- Na fundação: commits direto na `main`, com push ao fim de cada etapa. A partir da primeira
  funcionalidade de negócio: branches, PR e CI obrigatório.
- Ao fim de cada etapa: rode lint, análise e testes e reporte o que foi feito, o que ficou
  pendente e as decisões tomadas sem consulta.
