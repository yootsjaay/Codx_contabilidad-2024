<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class XmlOrganizado extends Model
{
    protected $table = 'xml_organizado';

    protected $fillable = [
        'nombre_archivo',
        'ruta_archivo',
        'tipo',
        'archivo_zip_id'
    ];

    public function archivoZip()
    {
        return $this->belongsTo(ArchivosZip::class, 'archivo_zip_id');
    }
}
