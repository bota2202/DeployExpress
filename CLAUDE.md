# DeployExpress — contexto do projeto

Sistema web para uma fábrica fictícia de maquinário pesado. Resolve: falta de comunicação entre fábrica e expedição (perda de produto), falta de cadastro de produtos e rastreamento de entregas. Projeto acadêmico, deve impressionar visualmente.

## Stack

- Laravel 13 (PHP 8.5), MySQL 8 (Laragon), Blade + CSS/JS puro em `public/` — **sem Vite, sem Tailwind, sem npm**.
- Idioma da interface: português (pt-BR). `APP_TIMEZONE=America/Sao_Paulo`.
- Fontes: Inter + JetBrains Mono via Google Fonts (cai na fonte do sistema sem internet).
- Modo de trabalho: ponytail (menor mudança que resolve, sem abstração extra, sem dependência nova).

## Rodar em outro PC

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan serve
```

`.env.example` já aponta para MySQL `127.0.0.1:3306`, banco `deployexpress`, usuário `root`, senha vazia. Crie o banco antes (`CREATE DATABASE deployexpress`). Testes: `php artisan test` (usam SQLite em memória, não tocam o MySQL).

## Logins de teste (senha `senha123`)

| Perfil | E-mail |
|---|---|
| Gerência | gerencia@deployexpress.com |
| Fábrica | fabrica@deployexpress.com |
| Expedição | expedicao@deployexpress.com |
| Cliente | cliente@deployexpress.com |

## Regras de negócio

- **Perfis** (`users.role`): `fabrica`, `expedicao`, `gerencia`, `cliente`. Checagem por `User::hasRole(...)` e middleware `role:a,b` (`app/Http/Middleware/EnsureRole.php`). Não usar `is()` — colide com `Model::is()`.
- **Fluxo**: fábrica abre ordem de produção (`em_producao`) → marca `pronta` → expedição confere o recebimento informando `received_quantity` → vira `recebida`. **Perda = quantity − received_quantity**; se houver perda, `loss_note` é obrigatório. Estoque do produto só sobe aqui.
- **Entrega**: expedição cria para um cliente; desconta estoque dentro de transação com `lockForUpdate` (nunca deixa estoque negativo). Gera `tracking_code` (`DX` + 8 chars). Eventos (`delivery_events`) formam a linha do tempo e o último define `deliveries.status`.
- **Rastreio público**: `/rastreio?codigo=XXX`, sem login.
- **Cliente** só vê as próprias entregas; outras telas retornam 403.
- Gerência cadastra usuários (inclusive clientes) em `/usuarios`.

## Estrutura

- Models: `User`, `Product`, `ProductionOrder` (accessor `loss`), `Delivery` (`newTrackingCode()`), `DeliveryEvent`.
- Controllers: `Auth`, `Dashboard`, `Product`, `ProductionOrder`, `Delivery`, `User` em `app/Http/Controllers`.
- Rotas em `routes/web.php` (URLs em português: `/produtos`, `/ordens`, `/entregas`, `/usuarios`, `/rastreio`).
- Views em `resources/views`: `layout.blade.php` (sidebar + ícones), `login`, `dashboard`, `products/`, `orders/`, `deliveries/`, `users/`, `track`, `timeline` (parcial), `icons/` (SVGs inline).
- Migration única: `database/migrations/2026_11_11_000001_create_app_tables.php`. Seeder: `DatabaseSeeder` (usuários, 3 produtos, 3 ordens, 1 entrega com eventos).
- Teste: `tests/Feature/FlowTest.php` (perda + estoque, estoque insuficiente, cliente vê só o seu, 403, rastreio público).

## Visual (4ª iteração: "industrial brutalista")

Inspirado em estheet.com (cinema escuro, holofote, desfoque→foco), simplyraffle.com (neo-brutalismo: bordas grossas, sombra dura, selos inclinados) e izaeats.com (fonte larga em caixa alta, título em duas cores, números gigantes, cartões creme).

- Cores: fundo preto quente `#0b0a08`, papel creme `#f2ebdd`, laranja `#ff6a00` como destaque; amarelo `#ffd23f` só em avisos/status.
- Fontes: Archivo largo (`font-stretch:125%`, peso 900, caixa alta) nos títulos/números, Instrument Serif itálico nos subtítulos (`.lede`), Inter no corpo, JetBrains Mono nos rótulos.
- Padrão de página: `<header class="head">` com `.kicker` (selo), `h1` com a palavra de destaque em `<em>` e `.lede` opcional. Formulários em `.card.paper`. Tabelas dentro de `<div class="table">`.
- Peças: bento de números no painel (`.stat.hero` com faixa zebrada), faixa rolando (`.ticker`), barras por status (`.pipe`), cartões de produto, rota com caminhão (`route.blade.php`), linha do tempo numerada, etiquetas de envio flutuando no login.
- Animações (`public/js/app.js` + `public/css/app.css`): holofote + grade de planta seguindo o mouse, grão, títulos entram palavra a palavra com desfoque, contadores, View Transitions entre páginas, abertura "DeployExpress" no login (1x por sessão), paralaxe das etiquetas. Tudo desliga com `prefers-reduced-motion`.

Tentativas descartadas pelo usuário: neon azul/ciano com fundo aurora (muito "gamer"); claro corporativo (achou feio); editorial dark com partículas (achou básico). Não voltar para esses.

## Pendências / não feito

- Commits: não incluir o Claude como coautor (sem linha `Co-Authored-By`).
- Sem: editar/excluir usuários, trocar senha, excluir produto, paginação, tema claro.
- O `CLAUDE.md` do template Laravel pedia `laravel/boost`; não instalado de propósito.
