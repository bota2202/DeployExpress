@extends('layout', ['title' => 'Entrega '.$delivery->tracking_code])
@section('content')
<h1>Entrega {{ $delivery->tracking_code }} <span class="badge {{ $delivery->status }}">{{ \App\Models\Delivery::STATUSES[$delivery->status] }}</span></h1>
<div class="card">
    <strong>{{ $delivery->quantity }}× {{ $delivery->product->name }}</strong> ({{ $delivery->product->code }})<br>
    Cliente: {{ $delivery->customer->name }}<br>
    Endereço: {{ $delivery->address }}<br>
    <span class="muted">Link público: <a href="{{ route('track', ['codigo' => $delivery->tracking_code]) }}">{{ route('track', ['codigo' => $delivery->tracking_code]) }}</a></span>
</div>

@if(auth()->user()->hasRole('expedicao', 'gerencia') && $delivery->status !== 'entregue')
<div class="card">
    <h2 style="margin-top:0">Registrar evento</h2>
    <form method="post" action="{{ route('deliveries.event', $delivery) }}">
        @csrf
        <div class="grid">
            <div><label>Status</label>
                <select name="status" required>
                    @foreach(\App\Models\Delivery::STATUSES as $k => $v)
                        <option value="{{ $k }}" @selected(old('status', $delivery->status) === $k)>{{ $v }}</option>
                    @endforeach
                </select></div>
            <div><label>Local</label><input name="location" value="{{ old('location') }}" placeholder="Ex.: Registro - SP"></div>
            <div><label>Observação</label><input name="note" value="{{ old('note') }}"></div>
        </div>
        <p><button class="btn">Registrar</button></p>
    </form>
</div>
@endif

<h2>Linha do tempo</h2>
@include('timeline', ['events' => $delivery->events, 'showUser' => true])
@endsection
