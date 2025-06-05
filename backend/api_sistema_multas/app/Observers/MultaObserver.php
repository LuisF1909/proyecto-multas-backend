<?php

namespace App\Observers;

use App\Models\Multa;
use App\Models\Notification;
use App\Models\User; // Asegúrate de importar el modelo User
use Illuminate\Support\Str; // Para limitar la longitud del mensaje

class MultaObserver
{
    /**
     * Handle the Multa "created" event.
     */
    public function created(Multa $multa): void
    {
        // Verificar si la multa tiene un departamento asociado
        if ($multa->department_id) {
            // Encontrar todos los usuarios que pertenecen a ese departamento
            $usersToNotify = User::where('department_id', $multa->department_id)->get();

            foreach ($usersToNotify as $user) {
                Notification::create([
                    // El ID de la notificación se generará automáticamente por el boot method del modelo Notification
                    'user_id' => $user->id,
                    'multa_id' => $multa->id,
                    'message' => "Nueva multa asignada a tu departamento: " . Str::limit($multa->description, 100), // Mensaje conciso
                ]);
            }
        }
    }

    // Puedes dejar los otros métodos (updated, deleted, etc.) vacíos si no los necesitas por ahora.
    // public function updated(Multa $multa): void { }
    // public function deleted(Multa $multa): void { }
    // public function restored(Multa $multa): void { }
    // public function forceDeleted(Multa $multa): void { }
}
