<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Necesario para generar UUIDs
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Importar BelongsTo

class Notification extends Model
{
    use HasFactory;

    // Indicar a Eloquent que la clave primaria NO es autoincremental.
    public $incrementing = false;

    // Indicar a Eloquent que el tipo de la clave primaria es 'string' (para el UUID).
    protected $keyType = 'string';

    protected $fillable = [
        'id', // Permitimos la asignación masiva del ID porque lo generaremos nosotros (UUID)
        'user_id',
        'multa_id',
        'message',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime', // Eloquent convertirá read_at a un objeto Carbon/DateTime
    ];

    // Este método 'boot' se ejecuta cuando el modelo se inicializa.
    // Lo usamos para generar automáticamente un UUID para la columna 'id'
    // cada vez que se crea una nueva instancia de Notification.
    protected static function boot(): void
    {
        parent::boot(); // Llama al método boot del padre

        static::creating(function ($model) {
            // Si el ID no se ha establecido (es decir, estamos creando un nuevo registro)
            if (empty($model->{$model->getKeyName()})) {
                // Asigna un nuevo UUID a la clave primaria del modelo
                $model->{$model->getKeyName()} = Str::uuid()->toString();
            }
        });
    }

    // Una notificación pertenece a un usuario
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Una notificación está relacionada con una multa
    public function multa(): BelongsTo
    {
        return $this->belongsTo(Multa::class);
    }
}
