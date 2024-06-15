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
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->binary('logotipo')->nullable();
            $table->string('razonSocial');
            $table->string('rfc');
            $table->string('CURP');
            $table->string('codigoPostal');
            $table->string('direccion');
            $table->string('telefono');
            $table->string('correo');
            $table->binary('archivoKey')->unique();
            $table->binary('certificado')->unique();
            $table->string('contraCertificado')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
