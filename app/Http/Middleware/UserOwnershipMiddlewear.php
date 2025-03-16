<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;
use Symfony\Component\HttpFoundation\Response;

class UserOwnershipMiddlewear
{
    use HasRoles;
    public function handle(Request $request, Closure $next)
    {
        $userIdFromUrl = $request->route('user'); // get the {user} of the URL

        $authenticatedUser = Auth::user();

        if ($authenticatedUser->id == $userIdFromUrl->id
            || $authenticatedUser->hasRole('admin')
            )
        {
            return $next($request);
        }

        return response()->json(['Acceso denegado!' => "El usuario de la request {$userIdFromUrl->id} no es el mismo que el usuario autenticado"], 403);
    }
}

