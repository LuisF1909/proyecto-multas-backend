<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification; // Importa el modelo Notification
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Para obtener el usuario autenticado

class NotificationController extends Controller
{
    /**
     * Display a listing of the user's unread notifications.
     */
    public function index(Request $request)
    {
        $user = Auth::user(); // Obtiene el usuario autenticado
        if (!$user) {
            return response()->json(['message' => 'No autenticado.'], 401);
        }

        // Obtiene las notificaciones no leídas del usuario, con detalles de la multa asociada.
        // La paginación es buena práctica.
        $notifications = $user->notifications()
                            ->whereNull('read_at') // Solo las no leídas
                            ->with('multa:id,description,amount') // Carga selectiva de datos de la multa
                            ->latest() // Ordena por fecha de creación
                            ->paginate(10); // Muestra 10 por página

        return response()->json($notifications);
    }

    /**
     * Get the count of unread notifications for the authenticated user.
     */
    public function unreadCount(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'No autenticado.'], 401);
        }

        $count = $user->notifications()->whereNull('read_at')->count();
        return response()->json(['unread_count' => $count]);
    }

    /**
     * Mark a specific notification as read.
     */
    public function markAsRead(Notification $notification) // Route Model Binding
    {
        $user = Auth::user();
        // Asegurarse que la notificación pertenezca al usuario autenticado
        if (!$user || $notification->user_id !== $user->id) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        if (is_null($notification->read_at)) { // Solo marcar si no ha sido leída
            $notification->read_at = now();
            $notification->save();
        }

        return response()->json(['message' => 'Notificación marcada como leída.', 'notification' => $notification]);
    }

    /**
     * Delete a specific notification (not the fine itself).
     */
    public function destroy(Notification $notification) // Route Model Binding
    {
        $user = Auth::user();
        // Asegurarse que la notificación pertenezca al usuario autenticado
        if (!$user || $notification->user_id !== $user->id) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        $notification->delete(); // Esto elimina la notificación, no la multa.

        return response()->json(['message' => 'Notificación eliminada exitosamente.']);
    }
}
