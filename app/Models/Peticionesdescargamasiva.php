<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;


class Peticionesdescargamasiva extends Model
{
	protected $table = 'peticionesdescargamasiva';

	protected $casts = [
		'idEmpresa' => 'int',
		'desdeFecha' => 'datetime',
		'hastaFecha' => 'datetime'
	];

	protected $fillable = [
		'idEmpresa',
		'nombreEmpresa',
		'desdeFecha',
		'hastaFecha',
		'emitidoRecibido',
		'uuidPeticion',
		'nombreArchivo',
		'status'
	];

	public function empresa()
	{
		return $this->belongsTo(Empresa::class, 'idEmpresa');
	}

	public function archivos_zips()
	{
		return $this->hasMany(ArchivosZip::class, 'peticion_id');
	}
}
