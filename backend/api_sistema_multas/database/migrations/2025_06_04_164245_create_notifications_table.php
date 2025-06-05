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
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Clave primaria UUID
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Usuario que recibe la notificación
            $table->foreignId('multa_id')->constrained('multas')->onDelete('cascade'); // Multa que originó la notificación
            $table->string('message'); // Mensaje de la notificación
            $table->timestamp('read_at')->nullable(); // Fecha y hora en que se leyó la notificación (null si no se ha leído)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
