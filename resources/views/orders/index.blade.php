@extends('layout', ['title' => 'Ordens de produção'])
@section('content')
<header class="head">
    <div>
        <span class="kicker">Fábrica → Expedição</span>
        <h1>Ordens de <em>produção</em></h1>
        <p class="lede">Cada unidade que sai da fábrica precisa chegar na expedição. A diferença vira perda.</p>
    </div>
    @if(auth()->user()->hasRole('fabrica', 'gerencia'))<a class="btn" href="{{ route('orders.create') }}">Nova ordem</a>@endif
</header>
<div class="table"><table>
    <tr><th>#</th><th>Produto</th><th>Qtd.</th><th>Status</th><th>Aberta por</th><th>Pronta em</th><th>Recebido</th><th>Ação</th></tr>
    @forelse($orders as $o)
        <tr>
            <td class="mono">{{ $o->id }}</td>
            <td><strong>{{ $o->product->name }}</strong></td>
            <td>{{ $o->quantity }}</td>
            <td>
                <span class="badge {{ $o->status }}">{{ \App\Models\ProductionOrder::STATUSES[$o->status] }}</span>
                <span class="steps {{ $o->status }}" aria-hidden="true"><i></i><i></i><i></i></span>
            </td>
            <td>{{ $o->creator->name }}<br><span class="muted mono">{{ $o->created_at->format('d/m/Y H:i') }}</span></td>
            <td class="mono">{{ $o->ready_at?->format('d/m/Y H:i') ?? '—' }}</td>
            <td>
                @if($o->status === 'recebida')
                    {{ $o->received_quantity }} un.
                    @if($o->loss > 0)<br><span class="loss">Perda: {{ $o->loss }}</span> <span class="muted">{{ $o->loss_note }}</span>@endif
                    <br><span class="muted">{{ $o->receiver->name }}, {{ $o->received_at->format('d/m/Y H:i') }}</span>
                @else — @endif
            </td>
            <td>
                @if($o->status === 'em_producao' && auth()->user()->hasRole('fabrica', 'gerencia'))
                    <form method="post" action="{{ route('orders.ready', $o) }}" class="inline">@csrf<button class="btn small">Marcar pronta</button></form>
                @elseif($o->status === 'pronta' && auth()->user()->hasRole('expedicao', 'gerencia'))
                    <form method="post" action="{{ route('orders.receive', $o) }}" class="inline">
                        @csrf
                        <input type="number" name="received_quantity" min="0" max="{{ $o->quantity }}" value="{{ old('received_quantity', $o->quantity) }}" required title="Quantidade que chegou" aria-label="Quantidade que chegou">
                        <input name="loss_note" placeholder="Motivo se houver perda" value="{{ old('loss_note') }}" style="width:180px" aria-label="Motivo da perda">
                        <button class="btn small">Confirmar</button>
                    </form>
                @endif
            </td>
        </tr>
    @empty
        <tr><td colspan="8" class="muted">Nenhuma ordem.</td></tr>
    @endforelse
</table></div>
@endsection
