<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0b0a08">
    <title>{{ $title ?? 'DeployExpress' }} · DeployExpress</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo:wdth,wght@62..125,400..900&family=Instrument+Serif:ital@0;1&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/app.css">
</head>
<body class="{{ request()->routeIs('login') ? 'page-login' : '' }}">
<div id="nav-progress"></div>
<div class="app">
@auth
@php($u = auth()->user())
@php($ready = $u->hasRole('expedicao', 'gerencia') ? \App\Models\ProductionOrder::where('status', 'pronta')->count() : 0)
<aside>
    <a class="brand" href="{{ route('dashboard') }}">
        <span class="mark">@include('icons.truck')</span>
        <span class="name">DeployExpress<small>Produção &amp; Logística</small></span>
    </a>
    <nav class="menu">
        @if($u->hasRole('fabrica', 'expedicao', 'gerencia'))
            <div class="label">Operação</div>
            <a href="{{ route('dashboard') }}" @class(['active' => request()->routeIs('dashboard')])>@include('icons.dashboard') Painel</a>
            <a href="{{ route('products.index') }}" @class(['active' => request()->routeIs('products.*')])>@include('icons.box') Produtos</a>
            <a href="{{ route('orders.index') }}" @class(['active' => request()->routeIs('orders.*')])>@include('icons.factory') Ordens de produção @if($ready)<span class="count">{{ $ready }}</span>@endif</a>
        @endif
        <div class="label">Logística</div>
        <a href="{{ route('deliveries.index') }}" @class(['active' => request()->routeIs('deliveries.*')])>@include('icons.truck') Entregas</a>
        <a href="{{ route('track') }}" @class(['active' => request()->routeIs('track')])>@include('icons.search') Rastreio público</a>
        @if($u->hasRole('gerencia'))
            <div class="label">Administração</div>
            <a href="{{ route('users.index') }}" @class(['active' => request()->routeIs('users.*')])>@include('icons.users') Usuários</a>
        @endif
    </nav>
    <div class="user">
        <span class="avatar">{{ mb_strtoupper(mb_substr($u->name, 0, 1)) }}</span>
        <span class="who"><strong>{{ $u->name }}</strong><span>{{ \App\Models\User::ROLES[$u->role] }}</span></span>
        <form method="post" action="{{ route('logout') }}">@csrf<button title="Sair" aria-label="Sair">@include('icons.logout')</button></form>
    </div>
</aside>
@endauth
@guest
@unless(request()->routeIs('login'))
<header class="topbar">
    <a class="brand" href="{{ route('track') }}"><span class="mark">@include('icons.truck')</span><span class="name">DeployExpress<small>Rastreio público</small></span></a>
    <a class="btn small" href="{{ route('login') }}">Entrar</a>
</header>
@endunless
@endguest
<div class="content">
    @yield('page')
    @hasSection('content')
    <main>
        @if(session('ok'))<div class="alert ok">@include('icons.check') {{ session('ok') }}</div>@endif
        @if($errors->any())<div class="alert error">@include('icons.alert') {{ $errors->first() }}</div>@endif
        @yield('content')
    </main>
    @endif
</div>
</div>
<script src="/js/app.js"></script>
</body>
</html>
