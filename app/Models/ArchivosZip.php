<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ArchivosZip
 * 
 * @property int $id
 * @property string $nombreArchivo
 * @property string $rutaArchivo
 * @property string $emitidoRecibido
 * @property int $empresa_id
 * @property int|null $peticion_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Empresa $empresa
 * @property Peticionesdescargamasiva|null $peticionesdescargamasiva
 *
 * @package App\Models
 */
class ArchivosZip extends Model
{
	protected $table = 'archivos_zip';

	protected $casts = [
		'empresa_id' => 'int',
		'peticion_id' => 'int'
	];

	protected $fillable = [
		'nombreArchivo',
		'rutaArchivo',
		'empresa_id',
		'peticion_id'
	];

	public function empresa()
	{
		return $this->belongsTo(Empresa::class);
	}

	public function peticionesdescargamasiva()
	{
		return $this->belongsTo(Peticionesdescargamasiva::class, 'peticion_id');
	}
	public function xmlOrganizados()
    {
        return $this->hasMany(XmlOrganizado::class, 'archivo_zip_id');
    }
}
