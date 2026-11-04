<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccountIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        abort_if($user?->is_suspended || $user?->owner_status === 'suspendido', 403, 'Tu cuenta está suspendida.');
        abort_if($user?->owner_status === 'invitado', 403, 'Primero definí tu contraseña desde el enlace enviado por email.');

        return $next($request);
    }
}
