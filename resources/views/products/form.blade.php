@extends('layout', ['title' => $product->exists ? 'Editar produto' : 'Novo produto'])
@section('content')
<h1>{{ $product->exists ? 'Editar produto' : 'Novo produto' }}</h1>
<div class="card" style="max-width:560px">
    <form method="post" action="{{ $product->exists ? route('products.update', $product) : route('products.store') }}">
        @csrf
        @if($product->exists) @method('PUT') @endif
        <label>Código</label>
        <input name="code" value="{{ old('code', $product->code) }}" required maxlength="30">
        <label>Nome</label>
        <input name="name" value="{{ old('name', $product->name) }}" required>
        <label>Descrição</label>
        <textarea name="description" rows="3">{{ old('description', $product->description) }}</textarea>
        <label>Peso (kg)</label>
        <input type="number" step="0.01" min="0" name="weight_kg" value="{{ old('weight_kg', $product->weight_kg) }}">
        <p><button class="btn">Salvar</button> <a class="btn secondary" href="{{ route('products.index') }}">Cancelar</a></p>
    </form>
    @if($product->exists)<p class="muted">Estoque atual: {{ $product->stock }} — o estoque só muda por ordens de produção recebidas e entregas.</p>@endif
</div>
@endsection
