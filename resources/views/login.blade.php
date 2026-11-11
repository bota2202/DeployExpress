@extends('layout', ['title' => 'Entrar'])
@section('page')
<div class="login-side">
    <a class="brand" href="/"><span class="mark">@include('icons.truck')</span><span>DeployExpress</span></a>
    <div>
        <div class="typed"></div>
        <h2>Da linha de produção até o cliente.<br><span class="hl">Sem perder nada no caminho.</span></h2>
        <p>Controle de ordens de produção, conferência na expedição, estoque e rastreamento de entregas em um só lugar.</p>
        <ul>
            <li>@include('icons.check') Fábrica avisa a expedição na hora</li>
            <li>@include('icons.check') Perdas registradas e visíveis para a gerência</li>
            <li>@include('icons.check') Rastreio público por código</li>
        </ul>
    </div>
    <small>© {{ date('Y') }} DeployExpress · Maquinário pesado · v1.0</small>
</div>
<div class="login-form">
    <div class="login">
        <h1>Entrar</h1>
        <p class="muted" style="margin:0 0 10px">Use o e-mail e a senha fornecidos pela gerência.</p>
        @if($errors->any())<div class="alert error">@include('icons.alert') {{ $errors->first() }}</div>@endif
        <form method="post" action="/login">
            @csrf
            <label>E-mail</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus>
            <label>Senha</label>
            <input type="password" name="password" required>
            <p><button class="btn">Entrar</button></p>
        </form>
        <p class="muted hint">Só quer rastrear uma entrega? <a href="{{ route('track') }}">Rastreio público</a></p>
    </div>
</div>
@endsection
