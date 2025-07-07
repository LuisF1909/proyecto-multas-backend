<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('multas', function (Blueprint $table) {
            $table->id();
            $table->text('description'); // Descripción de la multa
            $table->decimal('amount', 10, 2); // Monto de la multa, ej. 10 dígitos en total, 2 decimales
            // Llave foránea para el departamento al que pertenece la multa.
            // onDelete('cascade') significa que si un departamento es eliminado,
            // todas las multas asociadas a ese departamento también serán eliminadas.
            $table->foreignId('department_id')->constrained('departments')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('multas');
    }
};
