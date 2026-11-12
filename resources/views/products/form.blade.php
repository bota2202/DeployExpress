@extends('layout', ['title' => $product->exists ? 'Editar produto' : 'Novo produto'])
@section('content')
<header class="head">
    <div>
        <span class="kicker">Catálogo</span>
        <h1>{{ $product->exists ? 'Editar' : 'Novo' }} <em>produto</em></h1>
    </div>
</header>
<div class="card paper form-card">
    <form method="post" action="{{ $product->exists ? route('products.update', $product) : route('products.store') }}">
        @csrf
        @if($product->exists) @method('PUT') @endif
        <div class="grid">
            <div><label for="code">Código</label><input id="code" name="code" value="{{ old('code', $product->code) }}" required maxlength="30"></div>
            <div><label for="weight_kg">Peso (kg)</label><input id="weight_kg" type="number" step="0.01" min="0" name="weight_kg" value="{{ old('weight_kg', $product->weight_kg) }}"></div>
        </div>
        <label for="name">Nome</label>
        <input id="name" name="name" value="{{ old('name', $product->name) }}" required>
        <label for="description">Descrição</label>
        <textarea id="description" name="description" rows="3">{{ old('description', $product->description) }}</textarea>
        <p><button class="btn">Salvar</button> <a class="btn secondary" href="{{ route('products.index') }}">Cancelar</a></p>
    </form>
    @if($product->exists)<p class="muted">Estoque atual: {{ $product->stock }} — o estoque só muda por ordens de produção recebidas e entregas.</p>@endif
</div>
@endsection
