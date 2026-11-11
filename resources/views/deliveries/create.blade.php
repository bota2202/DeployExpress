@extends('layout', ['title' => 'Nova entrega'])
@section('content')
<h1>Nova entrega</h1>
<div class="card" style="max-width:560px">
    <form method="post" action="{{ route('deliveries.store') }}">
        @csrf
        <label>Cliente</label>
        <select name="customer_id" required>
            <option value="">Selecione…</option>
            @foreach($customers as $c)
                <option value="{{ $c->id }}" @selected(old('customer_id') == $c->id)>{{ $c->name }} ({{ $c->email }})</option>
            @endforeach
        </select>
        <label>Produto (só com estoque)</label>
        <select name="product_id" required>
            <option value="">Selecione…</option>
            @foreach($products as $p)
                <option value="{{ $p->id }}" @selected(old('product_id') == $p->id)>{{ $p->code }} — {{ $p->name }} (estoque: {{ $p->stock }})</option>
            @endforeach
        </select>
        <label>Quantidade</label>
        <input type="number" name="quantity" min="1" value="{{ old('quantity', 1) }}" required>
        <label>Endereço de entrega</label>
        <input name="address" value="{{ old('address') }}" required>
        <p><button class="btn">Criar entrega</button> <a class="btn secondary" href="{{ route('deliveries.index') }}">Cancelar</a></p>
    </form>
    @if($customers->isEmpty())<p class="muted">Nenhum cliente cadastrado. A gerência cadastra clientes em Usuários.</p>@endif
</div>
@endsection
