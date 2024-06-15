<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class XmlRecibido
 * 
 * @property int $id
 * @property string $nombreArchivo
 * @property string $rutaArchivo
 * @property int $ruta_xml_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property RutasXml $rutas_xml
 *
 * @package App\Models
 */
class XmlRecibido extends Model
{
	protected $table = 'xml_recibidos';

	protected $casts = [
		'ruta_xml_id' => 'int'
	];

	protected $fillable = [
		'nombreArchivo',
		'rutaArchivo',
		'ruta_xml_id'
	];

	public function rutas_xml()
	{
		return $this->belongsTo(RutasXml::class, 'ruta_xml_id');
	}
}
