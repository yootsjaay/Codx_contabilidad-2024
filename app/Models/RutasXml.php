<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RutasXml
 * 
 * @property int $id
 * @property string $nombreArchivo
 * @property string $rutaArchivo
 * @property string $tipo
 * @property int $zip_id
 * @property int $empresa_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Empresa $empresa
 * @property ArchivosZip $archivos_zip
 * @property Collection|XmlEmitido[] $xml_emitidos
 * @property Collection|XmlRecibido[] $xml_recibidos
 *
 * @package App\Models
 */
class RutasXml extends Model
{
	protected $table = 'rutas_xml';

	protected $casts = [
		'zip_id' => 'int',
		'empresa_id' => 'int'
	];

	protected $fillable = [
		'nombreArchivo',
		'rutaArchivo',
		'tipo',
		'zip_id',
		'empresa_id'
	];

	public function empresa()
	{
		return $this->belongsTo(Empresa::class);
	}

	public function archivos_zip()
	{
		return $this->belongsTo(ArchivosZip::class, 'zip_id');
	}

	public function xml_emitidos()
	{
		return $this->hasMany(XmlEmitido::class, 'ruta_xml_id');
	}

	public function xml_recibidos()
	{
		return $this->hasMany(XmlRecibido::class, 'ruta_xml_id');
	}
}
