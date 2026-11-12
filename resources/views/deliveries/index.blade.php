@extends('layout', ['title' => 'Entregas'])
@section('content')
@php($isClient = auth()->user()->hasRole('cliente'))
<header class="head">
    <div>
        <span class="kicker">Logística</span>
        <h1>{!! $isClient ? 'Minhas <em>entregas</em>' : 'Painel de <em>entregas</em>' !!}</h1>
        <p class="lede">Clique no código para ver a rota e a linha do tempo completa.</p>
    </div>
    @if(auth()->user()->hasRole('expedicao', 'gerencia'))<a class="btn" href="{{ route('deliveries.create') }}">Nova entrega</a>@endif
</header>
<div class="table"><table>
    <tr><th>Rastreio</th><th>Cliente</th><th>Produto</th><th>Qtd.</th><th>Endereço</th><th>Status</th><th>Criada</th></tr>
    @forelse($deliveries as $d)
        <tr>
            <td><a class="code-link" href="{{ route('deliveries.show', $d) }}"><strong>{{ $d->tracking_code }}</strong></a></td>
            <td>{{ $d->customer->name }}</td>
            <td><strong>{{ $d->product->name }}</strong></td>
            <td>{{ $d->quantity }}</td>
            <td>{{ $d->address }}</td>
            <td><span class="badge {{ $d->status }}">{{ \App\Models\Delivery::STATUSES[$d->status] }}</span></td>
            <td class="mono">{{ $d->created_at->format('d/m/Y') }}</td>
        </tr>
    @empty
        <tr><td colspan="7" class="muted">Nenhuma entrega.</td></tr>
    @endforelse
</table></div>
@endsection
