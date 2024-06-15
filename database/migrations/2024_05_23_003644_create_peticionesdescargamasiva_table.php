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
        Schema::create('peticionesdescargamasiva', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idEmpresa')->constrained('empresas')->onDelete('cascade');
            $table->string('nombreEmpresa')->nullable();
            $table->date('desdeFecha')->notNullable();
            $table->date('hastaFecha')->notNullable();
            $table->enum('emitidoRecibido', ['emitido', 'recibido'])->notNullable();
            $table->string('uuidPeticion')->notNullable();
            $table->string('nombreArchivo')->notNullable();
            $table->enum('status', ['pendiente', 'procesando', 'completado', 'fallido'])->default('pendiente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peticionesdescargamasiva');
    }
};
