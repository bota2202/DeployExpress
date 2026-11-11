@extends('layout', ['title' => 'Nova ordem de produção'])
@section('content')
<h1>Nova ordem de produção</h1>
<div class="card" style="max-width:560px">
    <form method="post" action="{{ route('orders.store') }}">
        @csrf
        <label>Produto</label>
        <select name="product_id" required>
            <option value="">Selecione…</option>
            @foreach($products as $p)
                <option value="{{ $p->id }}" @selected(old('product_id') == $p->id)>{{ $p->code }} — {{ $p->name }}</option>
            @endforeach
        </select>
        <label>Quantidade</label>
        <input type="number" name="quantity" min="1" value="{{ old('quantity', 1) }}" required>
        <p><button class="btn">Abrir ordem</button> <a class="btn secondary" href="{{ route('orders.index') }}">Cancelar</a></p>
    </form>
</div>
@endsection
