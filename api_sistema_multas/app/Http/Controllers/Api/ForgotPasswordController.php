<?php

    namespace App\Http\Controllers\Api;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Password;
    use Illuminate\Support\Facades\Validator;
    use App\Models\User;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Str;

    class ForgotPasswordController extends Controller
    {
        /**
         * Handle user request to get a password reset link.
         */
        public function sendResetLinkEmail(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:users,email',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            // Usa el broker de contraseñas de Laravel para enviar el enlace de reseteo.
            // Esto se encarga de generar el token, guardarlo y enviar el correo.
            $status = Password::broker()->sendResetLink(
                $request->only('email')
            );

            if ($status == Password::RESET_LINK_SENT) {
                return response()->json(['message' => 'Se ha enviado el enlace para restablecer la contraseña.'], 200);
            }

            // Si el status no es el esperado, algo falló.
            return response()->json(['message' => 'No se pudo enviar el enlace de reseteo.'], 500);
        }

        /**
         * Reset the given user's password.
         */
        public function resetPassword(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'token' => 'required',
                'email' => 'required|email|exists:users,email',
                'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            // Intenta resetear la contraseña usando el broker de Laravel.
            $status = Password::broker()->reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function (User $user, string $password) {
                    // Esta función se ejecuta si el token y el email son válidos.
                    // Actualiza la contraseña del usuario.
                    $user->forceFill([
                        'password' => Hash::make($password)
                    ])->save();

                    // Opcional: Invalidar todos los tokens de Sanctum para cerrar sesión en otros dispositivos.
                    $user->tokens()->delete();
                }
            );

            if ($status == Password::PASSWORD_RESET) {
                return response()->json(['message' => 'La contraseña ha sido restablecida exitosamente.'], 200);
            }

            // Si el status no es el esperado, es porque el token es inválido o ha expirado.
            return response()->json(['message' => 'Token de reseteo inválido o expirado.'], 400);
        }
    }
