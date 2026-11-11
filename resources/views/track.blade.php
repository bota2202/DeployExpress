@extends('layout', ['title' => 'Rastreio'])
@section('content')
<div style="max-width:640px;margin:0 auto">
    <h1>Rastrear entrega</h1>
    <div class="card">
        <form method="get" action="{{ route('track') }}">
            <label>Código de rastreio</label>
            <input name="codigo" value="{{ $code }}" placeholder="Ex.: DXDEMO0001" required>
            <p><button class="btn">Buscar</button></p>
        </form>
    </div>

    @if($code && ! $delivery)
        <div class="alert error">Nenhuma entrega encontrada com o código <strong>{{ $code }}</strong>.</div>
    @elseif($delivery)
        <div class="card">
            <strong>{{ $delivery->tracking_code }}</strong> · {{ $delivery->quantity }}× {{ $delivery->product->name }}<br>
            Status atual: <span class="badge {{ $delivery->status }}">{{ \App\Models\Delivery::STATUSES[$delivery->status] }}</span>
        </div>
        <h2>Linha do tempo</h2>
        @include('timeline', ['events' => $delivery->events])
    @endif
    @guest<p class="muted"><a href="{{ route('login') }}">Entrar no sistema</a></p>@endguest
</div>
@endsection
