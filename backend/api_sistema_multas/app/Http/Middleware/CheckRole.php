<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string ...$roles Los roles permitidos para la ruta.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Primero, verifica si el usuario está autenticado.
        if (!Auth::check()) {
            return response()->json(['message' => 'No autenticado.'], 401);
        }

        $user = Auth::user();

        // Itera sobre los roles pasados al middleware.
        // Si el usuario tiene al menos uno de los roles permitidos, continúa.
        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return $next($request); // Permite el acceso
            }
        }

        // Si el bucle termina y el usuario no tiene ninguno de los roles requeridos, deniega el acceso.
        return response()->json(['message' => 'No autorizado para realizar esta acción.'], 403); // 403 Forbidden
    }
}
