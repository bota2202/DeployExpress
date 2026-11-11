@extends('layout', ['title' => 'Painel'])
@section('content')
<h1>Painel</h1>
<div class="grid">
    <div class="stat"><div class="n">{{ $productsCount }}</div><div class="l">Produtos cadastrados</div></div>
    <div class="stat"><div class="n">{{ $stockTotal }}</div><div class="l">Unidades em estoque (expedição)</div></div>
    <div class="stat"><div class="n">{{ $ordersInProduction }}</div><div class="l">Ordens em produção</div></div>
    <div class="stat warn"><div class="n">{{ $ordersReady }}</div><div class="l">Prontas aguardando expedição</div></div>
    <div class="stat bad"><div class="n">{{ $lossTotal }}</div><div class="l">Unidades perdidas (fábrica → expedição)</div></div>
    @foreach(\App\Models\Delivery::STATUSES as $key => $label)
        <div class="stat"><div class="n">{{ $deliveriesByStatus[$key] ?? 0 }}</div><div class="l">Entregas: {{ $label }}</div></div>
    @endforeach
</div>

@if($ordersReady > 0)
    <div class="card notice" style="margin-top:18px">
        <strong>Atenção expedição:</strong> há {{ $ordersReady }} ordem(ns) pronta(s) na fábrica esperando conferência.
        <a href="{{ route('orders.index') }}">Ver ordens</a>
    </div>
@endif

<h2>Últimas perdas registradas</h2>
@if($recentLosses->isEmpty())
    <p class="muted">Nenhuma perda registrada.</p>
@else
<table>
    <tr><th>Ordem</th><th>Produto</th><th>Produzido</th><th>Recebido</th><th>Perda</th><th>Motivo</th><th>Quando</th></tr>
    @foreach($recentLosses as $o)
        <tr>
            <td>#{{ $o->id }}</td><td>{{ $o->product->name }}</td><td>{{ $o->quantity }}</td><td>{{ $o->received_quantity }}</td>
            <td class="loss">{{ $o->loss }}</td><td>{{ $o->loss_note }}</td><td>{{ $o->received_at->format('d/m/Y H:i') }}</td>
        </tr>
    @endforeach
</table>
@endif
@endsection
