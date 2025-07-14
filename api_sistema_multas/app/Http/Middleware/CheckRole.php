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
     * @param  string ...$roles Los roles permitidos para la ruta, pasados como argumentos.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. El middleware 'auth:sanctum' ya se aseguró de que el usuario está logueado.
        //    Por lo tanto, podemos asumir que Auth::user() existe.
        //    Si por alguna razón se usara este middleware sin 'auth:sanctum' antes,
        //    esta comprobación sería una seguridad extra.
        if (!Auth::check()) {
            // Este caso no debería ocurrir si las rutas están bien configuradas.
            return response()->json(['message' => 'No autenticado.'], 401);
        }

        // 2. Obtenemos el usuario autenticado.
        $user = Auth::user();

        // 3. Iteramos sobre los roles que se definieron en la ruta (ej. 'admin', 'editor').
        foreach ($roles as $role) {
            // 4. Usamos el método hasRole() que creamos en el modelo User.
            //    Si el usuario tiene al menos UNO de los roles requeridos, le damos acceso.
            if ($user->hasRole($role)) {
                return $next($request); // Permite que la petición continúe hacia el controlador.
            }
        }

        // 5. Si el bucle termina y el usuario no tiene ninguno de los roles requeridos,
        //    se le deniega el acceso con un error 403 Forbidden.
        //    403 significa "Sé quién eres, pero no tienes permiso para estar aquí".
        return response()->json(['message' => 'Acceso no autorizado. No tienes el rol requerido.'], 403);
    }
}
