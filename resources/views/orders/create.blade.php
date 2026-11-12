@extends('layout', ['title' => 'Nova ordem de produção'])
@section('content')
<header class="head">
    <div>
        <span class="kicker">Fábrica</span>
        <h1>Nova <em>ordem</em></h1>
    </div>
</header>
<div class="card paper form-card">
    <form method="post" action="{{ route('orders.store') }}">
        @csrf
        <label for="product_id">Produto</label>
        <select id="product_id" name="product_id" required>
            <option value="">Selecione…</option>
            @foreach($products as $p)
                <option value="{{ $p->id }}" @selected(old('product_id') == $p->id)>{{ $p->code }} — {{ $p->name }}</option>
            @endforeach
        </select>
        <label for="quantity">Quantidade</label>
        <input id="quantity" type="number" name="quantity" min="1" value="{{ old('quantity', 1) }}" required>
        <p><button class="btn">Abrir ordem</button> <a class="btn secondary" href="{{ route('orders.index') }}">Cancelar</a></p>
    </form>
</div>
@endsection
