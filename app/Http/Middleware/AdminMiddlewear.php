<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddlewear
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var User|null $autheticatedUser */
        $autheticatedUser = Auth::user();    

        if ($autheticatedUser->hasRole('admin'))
        {
            return $next($request);
        }

        return response()->json(['Acceso denegado!' => "No tiene permisos para realizar esta accion."], 403);
    }
}
