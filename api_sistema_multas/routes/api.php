<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MultaController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\ForgotPasswordController;


// --- Rutas Públicas ---
// No requieren ningún tipo de sesión o rol.
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword']);


// --- Rutas Protegidas ---
// Todas las rutas en este grupo requieren una sesión activa (Guardia #1: auth:sanctum).
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user()->load('department:id,name');
    });
    Route::post('/user/change-password', [AuthController::class, 'changePassword']);

    // --- Rutas de Multas con Protección Adicional de Rol ---

    // Guardia #1 (auth:sanctum) + Guardia #2 (check.role:admin)
    // Solo un 'admin' con sesión activa puede crear una multa.
    Route::post('/multas', [MultaController::class, 'store'])->middleware('check.role:admin');

    // Solo se necesita sesión activa para ver las multas.
    Route::get('/multas', [MultaController::class, 'index']);
    Route::get('/multas/{multa}', [MultaController::class, 'show']);


    // --- Otras Rutas que solo requieren sesión activa ---
    Route::get('/departments', [DepartmentController::class, 'index']);

    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
    Route::post('/notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead']);
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy']);
});
