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
        Schema::create('xml_organizado', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre_archivo');
            $table->string('ruta_archivo');
            $table->enum('tipo', ['emitido', 'recibido']);
            $table->unsignedBigInteger('archivo_zip_id');
            $table->foreign('archivo_zip_id')->references('id')->on('archivos_zip')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('xml_organizado');
    }
};
