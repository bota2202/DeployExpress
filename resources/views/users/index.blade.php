@extends('layout', ['title' => 'Usuários'])
@section('content')
<header class="head">
    <div>
        <span class="kicker">Administração</span>
        <h1>Equipe &amp; <em>clientes</em></h1>
    </div>
    <a class="btn" href="{{ route('users.create') }}">Novo usuário</a>
</header>
<div class="table"><table>
    <tr><th>Nome</th><th>E-mail</th><th>Perfil</th><th>Criado</th></tr>
    @foreach($users as $u)
        <tr><td><strong>{{ $u->name }}</strong></td><td class="mono">{{ $u->email }}</td><td>{{ \App\Models\User::ROLES[$u->role] }}</td><td class="mono">{{ $u->created_at->format('d/m/Y') }}</td></tr>
    @endforeach
</table></div>
@endsection
