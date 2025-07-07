<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MultaController;       // Importa MultaController
use App\Http\Controllers\Api\NotificationController; // Importa NotificationController
use App\Http\Controllers\Api\AuthController;       // Asumiremos que creas este controlador
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\ForgotPasswordController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Rutas públicas (ej. login, register)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword']);


// Rutas protegidas por Sanctum (requieren autenticación)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/user/change-password', [AuthController::class, 'changePassword']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user()->load('department:id,name'); // Devuelve el usuario con su departamento
    });

    // Rutas para Multas
    Route::apiResource('multas', MultaController::class)->only(['index', 'store', 'show']);
    Route::get('/multas', [MultaController::class, 'index']); // Lista todas las multas
    Route::post('/multas', [MultaController::class, 'store'])->middleware('check.role:admin'); // Crea una nueva multa
    Route::get('/multas/{multa}', [MultaController::class, 'show']); // Muestra una multa específica

    // Rutas para Notificaciones
    Route::get('/notifications', [NotificationController::class, 'index']); // Lista notificaciones no leídas del usuario
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']); // Obtiene el contador de no leídas
    Route::post('/notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead']); // Marca una notificación como leída
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy']); // Elimina una notificación

    Route::get('/departments', [DepartmentController::class, 'index']);
});
