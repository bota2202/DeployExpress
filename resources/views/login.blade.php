@extends('layout', ['title' => 'Entrar'])
@section('page')
<div class="intro" aria-hidden="true"><div><span>Deploy</span><span>Express</span></div></div>
<div class="login-side">
    <div class="tags" aria-hidden="true">
        @foreach([
            ['DX7K2M9QPA', 'Escavadeira 320', '72%', '6%', '-8deg', 28, '0', '0s', ''],
            ['DXR4T8LW2C', 'Trator de esteira', '84%', '46%', '7deg', 18, '1.5px', '-2s', 'hot'],
            ['DXB9Q1ZE7N', 'Pá carregadeira', '58%', '78%', '-5deg', 36, '0', '-4s', ''],
            ['DXM3V6HK0S', 'Rolo compactador', '-4%', '62%', '10deg', 14, '3px', '-1s', ''],
            ['DXDEMO0001', 'Motoniveladora', '40%', '-3%', '4deg', 10, '4px', '-3s', ''],
            ['DXP8F2YJ5U', 'Retroescavadeira', '20%', '88%', '-12deg', 22, '2px', '-5s', 'hot'],
        ] as [$code, $name, $x, $y, $r, $d, $b, $t, $cls])
            <div class="tag {{ $cls }}" style="--x:{{ $x }};--y:{{ $y }};--r:{{ $r }};--d:{{ $d }};--b:{{ $b }};--t:{{ $t }};--o:{{ $b === '0' ? .9 : .45 }}"><b>{{ $code }}</b><span>{{ $name }}</span><i></i></div>
        @endforeach
    </div>
    <a class="brand" href="/"><span class="mark">@include('icons.truck')</span><span class="name">DeployExpress<small>Produção &amp; Logística</small></span></a>
    <div>
        <div class="typed"></div>
        <h2 class="mega">Da fábrica<br>ao <em>cliente.</em></h2>
        <div class="sticker">Sem perder nada no caminho</div>
        <p class="lede">Ordens de produção, conferência na expedição, estoque e rastreamento de entregas num lugar só.</p>
        <ul class="feats">
            <li><b>01</b> Fábrica avisa a expedição na hora</li>
            <li><b>02</b> Perdas registradas e visíveis para a gerência</li>
            <li><b>03</b> Rastreio público por código</li>
        </ul>
    </div>
    <small>© {{ date('Y') }} DeployExpress · Maquinário pesado · v1.0</small>
</div>
<div class="login-form">
    <div class="login card paper">
        <span class="kicker">Acesso restrito</span>
        <h1>Entrar</h1>
        <p class="muted" style="margin:0 0 10px">Use o e-mail e a senha fornecidos pela gerência.</p>
        @if($errors->any())<div class="alert error">@include('icons.alert') {{ $errors->first() }}</div>@endif
        <form method="post" action="/login">
            @csrf
            <label for="email">E-mail</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
            <label for="password">Senha</label>
            <input id="password" type="password" name="password" required>
            <p><button class="btn">Entrar</button></p>
        </form>
        <p class="muted hint">Só quer rastrear uma entrega? <a href="{{ route('track') }}">Rastreio público</a></p>
    </div>
</div>
@endsection
