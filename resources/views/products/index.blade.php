@extends('layout', ['title' => 'Produtos'])
@section('content')
@php($canEdit = auth()->user()->hasRole('fabrica', 'gerencia'))
<header class="head">
    <div>
        <span class="kicker">Catálogo</span>
        <h1>Produtos <em>&amp; estoque</em></h1>
        <p class="lede">O estoque só muda quando a expedição confere uma ordem ou despacha uma entrega.</p>
    </div>
    @if($canEdit)<a class="btn" href="{{ route('products.create') }}">Novo produto</a>@endif
</header>
<div class="products">
    @forelse($products as $p)
        <article class="product">
            <div class="top">
                <span class="code">{{ $p->code }}</span>
                @if($canEdit)<a href="{{ route('products.edit', $p) }}">Editar →</a>@endif
            </div>
            <h3>{{ $p->name }}</h3>
            <p>{{ $p->description }}</p>
            <div class="foot">
                <div><div @class(['n', 'zero' => $p->stock == 0])>{{ $p->stock }}</div><small>un. em estoque</small></div>
                <span class="kg">{{ $p->weight_kg ? number_format($p->weight_kg, 0, ',', '.').' kg' : '—' }}</span>
            </div>
        </article>
    @empty
        <p class="muted">Nenhum produto cadastrado.</p>
    @endforelse
</div>
@endsection
