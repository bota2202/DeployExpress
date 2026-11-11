@extends('layout', ['title' => 'Novo usuário'])
@section('content')
<h1>Novo usuário</h1>
<div class="card" style="max-width:560px">
    <form method="post" action="{{ route('users.store') }}">
        @csrf
        <label>Nome</label>
        <input name="name" value="{{ old('name') }}" required>
        <label>E-mail</label>
        <input type="email" name="email" value="{{ old('email') }}" required>
        <label>Senha</label>
        <input type="password" name="password" required minlength="6">
        <label>Perfil</label>
        <select name="role" required>
            @foreach(\App\Models\User::ROLES as $k => $v)
                <option value="{{ $k }}" @selected(old('role') === $k)>{{ $v }}</option>
            @endforeach
        </select>
        <p><button class="btn">Criar</button> <a class="btn secondary" href="{{ route('users.index') }}">Cancelar</a></p>
    </form>
</div>
@endsection
