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
        Schema::create('archivos_zip', function (Blueprint $table) {
            $table->id();
            $table->string('nombreArchivo');
            $table->string('rutaArchivo');
            $table->enum('emitidoRecibido', ['emitido', 'recibido'])->notNullable();
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('peticion_id')->nullable();
            $table->timestamps();
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            $table->foreign('peticion_id')->references('id')->on('peticionesdescargamasiva')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archivos_zip');
    }
};
