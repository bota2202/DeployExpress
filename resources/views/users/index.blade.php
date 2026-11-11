@extends('layout', ['title' => 'Usuários'])
@section('content')
<h1>Usuários</h1>
<div class="actions"><a class="btn" href="{{ route('users.create') }}">+ Novo usuário</a></div>
<table>
    <tr><th>Nome</th><th>E-mail</th><th>Perfil</th><th>Criado</th></tr>
    @foreach($users as $u)
        <tr><td>{{ $u->name }}</td><td>{{ $u->email }}</td><td>{{ \App\Models\User::ROLES[$u->role] }}</td><td>{{ $u->created_at->format('d/m/Y') }}</td></tr>
    @endforeach
</table>
@endsection
