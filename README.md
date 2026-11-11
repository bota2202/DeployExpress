# DeployExpress

Sistema de controle de produção, expedição e entregas para uma fábrica de maquinário pesado.
Laravel 13 + MySQL, HTML/CSS puro (sem build de front-end).

## O que resolve

- **Comunicação fábrica → expedição**: a fábrica abre uma ordem de produção e marca como *pronta*; a expedição vê o aviso no painel e confere o recebimento.
- **Perda de produto**: na conferência a expedição informa quantas unidades chegaram. A diferença vira *perda*, com motivo obrigatório, e aparece no painel da gerência.
- **Cadastro de produtos** com estoque (só muda por ordem recebida ou entrega criada).
- **Entregas com rastreamento**: código de rastreio, linha do tempo de eventos (status, local, observação) e página pública `/rastreio`.

## Perfis

| Perfil | Pode |
|---|---|
| Fábrica | ver produtos, cadastrar produtos, abrir ordens e marcar como prontas |
| Expedição / Logística | conferir ordens prontas, criar entregas, registrar eventos |
| Gerência | tudo acima + cadastrar usuários (inclusive clientes) + painel de perdas |
| Cliente | ver só as próprias entregas e o rastreio |

## Rodar

Requer PHP 8.5, Composer e MySQL (Laragon) ligado.

```bash
composer install
php artisan migrate:fresh --seed
php artisan serve
```

Abra http://localhost:8000. Usuários de teste (senha `senha123`):
`gerencia@deployexpress.com`, `fabrica@deployexpress.com`, `expedicao@deployexpress.com`, `cliente@deployexpress.com`.

Testes: `php artisan test`.
