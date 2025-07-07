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
        Schema::table('users', function (Blueprint $table) {
            // Columna para la llave foránea, puede ser nula si un usuario no pertenece a un depto.
            // Se enlaza a la columna 'id' de la tabla 'departments'.
            // onDelete('set null') significa que si un departamento es eliminado,
            // el department_id en esta tabla de usuarios se establecerá a NULL.
            $table->foreignId('department_id')->nullable()->after('email')->constrained('departments')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['department_id']); // Primero eliminar la restricción de llave foránea
            $table->dropColumn('department_id');    // Luego eliminar la columna
        });
    }
};
