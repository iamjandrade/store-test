<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IdentifyUserByEmail
{
    /**
     * Gestiona una solicitud entrante.
     *
     * Espera un encabezado HTTP `X-User-Email` que contenga el correo electrónico del usuario.
     * Busca al usuario por su correo electrónico y establece el resolutor de usuarios de la solicitud para que
     * `$request->user()` y `Auth::user()` devuelvan el usuario encontrado.
     * Si falta el encabezado o no se encuentra al usuario, devuelve 401.
     */

    public function handle(Request $request, Closure $next)
    {
        $email = $request->header('X-User-Email');

        if (! $email) {
            return response()->json(['message' => 'Missing X-User-Email header'], 401);
        }

        $user = User::where('email', $email)->first();

        if (! $user) {
            return response()->json(['message' => 'User not found'], 401);
        }

        // Proporcionar el usuario para las llamadas $request->user()
        $request->setUserResolver(fn () => $user);

        // También configura el usuario Auth global para mayor comodidad.
        Auth::setUser($user);

        return $next($request);
    }
}
