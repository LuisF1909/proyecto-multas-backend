<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Importar BelongsTo
use Illuminate\Database\Eloquent\Relations\HasMany;   // Importar HasMany
use App\Notifications\CustomResetPasswordNotification;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'department_id', // Añadir department_id para asignación masiva
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Un usuario pertenece a un departamento
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    // Un usuario puede tener muchas notificaciones
    public function notifications(): HasMany
    {
        // Por defecto, ordena las notificaciones por fecha de creación descendente
        return $this->hasMany(Notification::class)->orderBy('created_at', 'desc');
    }

    public function sendPasswordResetNotification($token) // <-- 2. AÑADE O SOBREESCRIBE ESTE MÉTODO
    {
        $this->notify(new CustomResetPasswordNotification($token));
    }
}
