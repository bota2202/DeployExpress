@extends('layout', ['title' => 'Rastreio'])
@section('content')
<div class="track-wrap">
    <header class="head center">
        <div>
            <span class="kicker">Rastreio público</span>
            <h1>Onde está a <em>sua carga?</em></h1>
            <p class="lede">Digite o código que começa com DX e acompanhe cada parada da entrega.</p>
        </div>
    </header>

    <form class="search paper" method="get" action="{{ route('track') }}">
        <input name="codigo" value="{{ $code }}" placeholder="DXDEMO0001" required aria-label="Código de rastreio">
        <button class="btn">Rastrear</button>
    </form>

    @if($code && ! $delivery)
        <div class="alert error">@include('icons.alert') Nenhuma entrega encontrada com o código <strong>{{ $code }}</strong>.</div>
    @elseif($delivery)
        <div class="card paper">
            <span class="badge {{ $delivery->status }}">{{ \App\Models\Delivery::STATUSES[$delivery->status] }}</span>
            <div class="big-code">{{ $delivery->tracking_code }}</div>
            <strong>{{ $delivery->quantity }}× {{ $delivery->product->name }}</strong>
        </div>
        @include('route', ['status' => $delivery->status])
        <h2>Linha do tempo</h2>
        @include('timeline', ['events' => $delivery->events])
    @endif
</div>
@endsection
