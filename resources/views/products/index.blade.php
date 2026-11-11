@extends('layout', ['title' => 'Produtos'])
@section('content')
<h1>Produtos</h1>
@if(auth()->user()->hasRole('fabrica', 'gerencia'))
    <div class="actions"><a class="btn" href="{{ route('products.create') }}">+ Novo produto</a></div>
@endif
<table>
    <tr><th>Código</th><th>Nome</th><th>Peso (kg)</th><th>Estoque</th><th></th></tr>
    @forelse($products as $p)
        <tr>
            <td>{{ $p->code }}</td>
            <td>{{ $p->name }}<br><span class="muted">{{ $p->description }}</span></td>
            <td>{{ $p->weight_kg ? number_format($p->weight_kg, 0, ',', '.') : '-' }}</td>
            <td><strong>{{ $p->stock }}</strong></td>
            <td>@if(auth()->user()->hasRole('fabrica', 'gerencia'))<a href="{{ route('products.edit', $p) }}">Editar</a>@endif</td>
        </tr>
    @empty
        <tr><td colspan="5" class="muted">Nenhum produto cadastrado.</td></tr>
    @endforelse
</table>
@endsection
