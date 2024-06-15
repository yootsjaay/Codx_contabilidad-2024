<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpresasController;
use App\Http\Controllers\PeticionesDescargaMasivaXmlController;
use App\Http\Controllers\ArchivosController;
use App\Http\Controllers\PruebasDescargaController;
use App\Http\Controllers\XmlController;

Route::get('/', function () {
    return redirect()->route('empresas.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('empresas', EmpresasController::class);

    Route::post('import-xml', [XmlController::class, 'importXml'])->name('importXml');
    Route::post('/upload-xml', [ProcesadorXml::class, 'procesarXmlGrande'])->name('upload.xml');
    // routes/web.php
Route::get('/factura/upload-xml', [XmlController::class, 'create']);
Route::post('/factura/upload-xml', [XmlController::class, 'store']);


    Route::get('/peticionesdescargamasiva/index', [PeticionesDescargaMasivaXmlController::class, 'index'])->name('peticionesdescargamasiva.index');
    Route::post('/peticionesdescargamasiva/store', [PeticionesDescargaMasivaXmlController::class, 'store'])->name('peticionesdescargamasiva.store');
    Route::delete('peticionesdescargamasiva/{id}', [PeticionesDescargaMasivaXmlController::class,'destroy'])->name('peticionesdescargamasiva.destroy');
    Route::post('/verificar-consulta',  [PeticionesDescargaMasivaXmlController::class,'verificarConsulta'])->name('verificarConsulta');
    Route::get('/descargar-paquetes', [PeticionesDescargaMasivaXmlController::class,'descargarPaquetes'])->name('descargarPaquetes');
   
    Route::get('/factura/index', [ArchivosController::class, 'index'])->name('factura.index');
    // Ruta para importar facturas desde un archivo Excel
    Route::post('/facturas/importar', [ArchivosController::class, 'importar'])->name('facturas.importar');
});


require __DIR__.'/auth.php';
