@extends('layout', ['title' => 'Nova entrega'])
@section('content')
<header class="head">
    <div>
        <span class="kicker">Expedição</span>
        <h1>Nova <em>entrega</em></h1>
        <p class="lede">O estoque é descontado na hora e o cliente recebe um código de rastreio.</p>
    </div>
</header>
<div class="card paper form-card">
    <form method="post" action="{{ route('deliveries.store') }}">
        @csrf
        <label for="customer_id">Cliente</label>
        <select id="customer_id" name="customer_id" required>
            <option value="">Selecione…</option>
            @foreach($customers as $c)
                <option value="{{ $c->id }}" @selected(old('customer_id') == $c->id)>{{ $c->name }} ({{ $c->email }})</option>
            @endforeach
        </select>
        <label for="product_id">Produto (só com estoque)</label>
        <select id="product_id" name="product_id" required>
            <option value="">Selecione…</option>
            @foreach($products as $p)
                <option value="{{ $p->id }}" @selected(old('product_id') == $p->id)>{{ $p->code }} — {{ $p->name }} (estoque: {{ $p->stock }})</option>
            @endforeach
        </select>
        <div class="grid">
            <div><label for="quantity">Quantidade</label><input id="quantity" type="number" name="quantity" min="1" value="{{ old('quantity', 1) }}" required></div>
            <div><label for="address">Endereço de entrega</label><input id="address" name="address" value="{{ old('address') }}" required></div>
        </div>
        <p><button class="btn">Criar entrega</button> <a class="btn secondary" href="{{ route('deliveries.index') }}">Cancelar</a></p>
    </form>
    @if($customers->isEmpty())<p class="muted">Nenhum cliente cadastrado. A gerência cadastra clientes em Usuários.</p>@endif
</div>
@endsection
