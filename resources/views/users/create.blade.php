@extends('layout', ['title' => 'Novo usuário'])
@section('content')
<header class="head">
    <div>
        <span class="kicker">Administração</span>
        <h1>Novo <em>usuário</em></h1>
    </div>
</header>
<div class="card paper form-card">
    <form method="post" action="{{ route('users.store') }}">
        @csrf
        <label for="name">Nome</label>
        <input id="name" name="name" value="{{ old('name') }}" required>
        <label for="email">E-mail</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required>
        <div class="grid">
            <div><label for="password">Senha</label><input id="password" type="password" name="password" required minlength="6"></div>
            <div><label for="role">Perfil</label>
                <select id="role" name="role" required>
                    @foreach(\App\Models\User::ROLES as $k => $v)
                        <option value="{{ $k }}" @selected(old('role') === $k)>{{ $v }}</option>
                    @endforeach
                </select></div>
        </div>
        <p><button class="btn">Criar</button> <a class="btn secondary" href="{{ route('users.index') }}">Cancelar</a></p>
    </form>
</div>
@endsection
