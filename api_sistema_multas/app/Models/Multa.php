<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Importar BelongsTo
use Illuminate\Database\Eloquent\Relations\HasMany;   // Importar HasMany

class Multa extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'amount',
        'department_id',
    ];

    // Una multa pertenece a un departamento
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    // Una multa puede generar (estar asociada con) muchas notificaciones
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }
}
