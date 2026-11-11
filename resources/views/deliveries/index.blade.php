@extends('layout', ['title' => 'Entregas'])
@section('content')
<h1>{{ auth()->user()->hasRole('cliente') ? 'Minhas entregas' : 'Entregas' }}</h1>
@if(auth()->user()->hasRole('expedicao', 'gerencia'))
    <div class="actions"><a class="btn" href="{{ route('deliveries.create') }}">+ Nova entrega</a></div>
@endif
<table>
    <tr><th>Rastreio</th><th>Cliente</th><th>Produto</th><th>Qtd.</th><th>Endereço</th><th>Status</th><th>Criada</th></tr>
    @forelse($deliveries as $d)
        <tr>
            <td><a href="{{ route('deliveries.show', $d) }}"><strong>{{ $d->tracking_code }}</strong></a></td>
            <td>{{ $d->customer->name }}</td>
            <td>{{ $d->product->name }}</td>
            <td>{{ $d->quantity }}</td>
            <td>{{ $d->address }}</td>
            <td><span class="badge {{ $d->status }}">{{ \App\Models\Delivery::STATUSES[$d->status] }}</span></td>
            <td>{{ $d->created_at->format('d/m/Y') }}</td>
        </tr>
    @empty
        <tr><td colspan="7" class="muted">Nenhuma entrega.</td></tr>
    @endforelse
</table>
@endsection
