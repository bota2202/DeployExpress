<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        abort_unless($request->user()->hasRole(...$roles), 403, 'Seu perfil não tem acesso a esta página.');

        return $next($request);
    }
}
