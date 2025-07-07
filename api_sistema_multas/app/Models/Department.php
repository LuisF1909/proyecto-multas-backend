<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // Importar HasMany

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    // Un departamento puede tener muchos usuarios
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    // Un departamento puede tener muchas multas asociadas directamente
    public function multas(): HasMany
    {
        return $this->hasMany(Multa::class);
    }
}
