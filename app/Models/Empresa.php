<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Empresa
 * 
 * @property int $id
 * @property string $nombre
 * @property string|null $logotipo
 * @property string $razonSocial
 * @property string $rfc
 * @property string $CURP
 * @property string $codigoPostal
 * @property string $direccion
 * @property string $telefono
 * @property string $correo
 * @property string $archivoKey
 * @property string $certificado
 * @property string $contraCertificado
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|ArchivosZip[] $archivos_zips
 * @property Collection|Peticionesdescargamasiva[] $peticionesdescargamasivas
 *
 * @package App\Models
 */
class Empresa extends Model
{
	protected $table = 'empresas';

	protected $fillable = [
		'nombre',
		'logotipo',
		'razonSocial',
		'rfc',
		'CURP',
		'codigoPostal',
		'direccion',
		'telefono',
		'correo',
		'archivoKey',
		'certificado',
		'contraCertificado'
	];

	public function archivos_zips()
	{
		return $this->hasMany(ArchivosZip::class);
	}

	public function peticionesdescargamasivas()
	{
		return $this->hasMany(Peticionesdescargamasiva::class, 'idEmpresa');
	}
}
