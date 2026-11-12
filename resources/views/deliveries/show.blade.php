@extends('layout', ['title' => 'Entrega '.$delivery->tracking_code])
@section('content')
<header class="head">
    <div>
        <span class="kicker">Entrega</span>
        <h1><em>{{ $delivery->tracking_code }}</em></h1>
        <p class="lede">{{ $delivery->quantity }}× {{ $delivery->product->name }} para {{ $delivery->customer->name }}</p>
    </div>
    <span class="badge {{ $delivery->status }}">{{ \App\Models\Delivery::STATUSES[$delivery->status] }}</span>
</header>

@include('route', ['status' => $delivery->status])

<div class="card">
    <dl class="details">
        <div><dt>Produto</dt><dd>{{ $delivery->quantity }}× {{ $delivery->product->name }} ({{ $delivery->product->code }})</dd></div>
        <div><dt>Cliente</dt><dd>{{ $delivery->customer->name }}</dd></div>
        <div><dt>Endereço</dt><dd>{{ $delivery->address }}</dd></div>
        <div><dt>Link público</dt><dd><a href="{{ route('track', ['codigo' => $delivery->tracking_code]) }}">{{ route('track', ['codigo' => $delivery->tracking_code]) }}</a></dd></div>
    </dl>
</div>

@if(auth()->user()->hasRole('expedicao', 'gerencia') && $delivery->status !== 'entregue')
<h2>Registrar evento</h2>
<div class="card paper">
    <form method="post" action="{{ route('deliveries.event', $delivery) }}">
        @csrf
        <div class="grid">
            <div><label for="status">Status</label>
                <select id="status" name="status" required>
                    @foreach(\App\Models\Delivery::STATUSES as $k => $v)
                        <option value="{{ $k }}" @selected(old('status', $delivery->status) === $k)>{{ $v }}</option>
                    @endforeach
                </select></div>
            <div><label for="location">Local</label><input id="location" name="location" value="{{ old('location') }}" placeholder="Ex.: Registro - SP"></div>
            <div><label for="note">Observação</label><input id="note" name="note" value="{{ old('note') }}"></div>
        </div>
        <p><button class="btn">Registrar</button></p>
    </form>
</div>
@endif

<h2>Linha do tempo</h2>
@include('timeline', ['events' => $delivery->events, 'showUser' => true])
@endsection
