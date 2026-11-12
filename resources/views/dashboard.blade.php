@extends('layout', ['title' => 'Painel'])
@section('content')
@php($dTotal = max(1, $deliveriesByStatus->sum()))
@php($tick = "{$stockTotal} unidades em estoque ✦ {$ordersInProduction} em produção ✦ {$ordersReady} prontas na fábrica ✦ {$lossTotal} perdidas no caminho ✦ ".($deliveriesByStatus['em_transito'] ?? 0)." entregas em trânsito ✦ ")
<header class="head">
    <div>
        <span class="kicker">Painel · {{ now()->format('d.m.Y') }}</span>
        <h1>Visão da <em>operação</em></h1>
        <p class="lede">Da linha de produção até a porta do cliente, sem perder nada no caminho.</p>
    </div>
</header>

<div class="ticker" aria-hidden="true"><div class="track"><span>{{ $tick }}{{ $tick }}</span><span>{{ $tick }}{{ $tick }}</span></div></div>

<div class="bento">
    <div class="stat hero"><div class="n">{{ $lossTotal }}</div><div class="l">Unidades perdidas entre a fábrica e a expedição</div></div>
    <div class="stat"><div class="n">{{ $productsCount }}</div><div class="l">Produtos cadastrados</div></div>
    <div class="stat"><div class="n">{{ $stockTotal }}</div><div class="l">Unidades em estoque na expedição</div></div>
    <div class="stat"><div class="n">{{ $ordersInProduction }}</div><div class="l">Ordens em produção</div></div>
    <div class="stat warn"><div class="n">{{ $ordersReady }}</div><div class="l">Prontas aguardando expedição</div></div>
</div>

@if($ordersReady > 0)
    <a class="notice" href="{{ route('orders.index') }}">
        <strong>{{ $ordersReady }} ordem(ns) pronta(s)</strong> esperando conferência da expedição <span>Conferir →</span>
    </a>
@endif

<h2>Entregas por status</h2>
<div class="pipe">
    @foreach(\App\Models\Delivery::STATUSES as $key => $label)
        @php($n = $deliveriesByStatus[$key] ?? 0)
        <div class="seg {{ $key }}">
            <div class="n">{{ $n }}</div>
            <div class="l">{{ $label }}</div>
            <div class="bar"><i style="--w: {{ round($n / $dTotal * 100) }}%"></i></div>
        </div>
    @endforeach
</div>

<h2>Últimas perdas registradas</h2>
@if($recentLosses->isEmpty())
    <p class="muted">Nenhuma perda registrada.</p>
@else
<div class="table"><table>
    <tr><th>Ordem</th><th>Produto</th><th>Produzido</th><th>Recebido</th><th>Perda</th><th>Motivo</th><th>Quando</th></tr>
    @foreach($recentLosses as $o)
        <tr>
            <td class="mono">#{{ $o->id }}</td><td><strong>{{ $o->product->name }}</strong></td><td>{{ $o->quantity }}</td><td>{{ $o->received_quantity }}</td>
            <td class="loss">−{{ $o->loss }}</td><td>{{ $o->loss_note }}</td><td class="mono">{{ $o->received_at->format('d/m/Y H:i') }}</td>
        </tr>
    @endforeach
</table></div>
@endif
@endsection
