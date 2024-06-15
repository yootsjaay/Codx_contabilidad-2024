<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TimbreFiscalDigital
 * 
 * @property int $id
 * @property int|null $comprobante_id
 * @property Carbon|null $fecha_timbrado
 * @property string|null $no_certificado_sat
 * @property string|null $rfc_prov_certif
 * @property string|null $sello_cfd
 * @property string|null $sello_sat
 * @property string|null $uuid
 * @property string|null $version
 * 
 * @property Comprobante|null $comprobante
 *
 * @package App\Models
 */
class TimbreFiscalDigital extends Model
{
	protected $table = 'timbre_fiscal_digital';
	public $timestamps = false;

	protected $casts = [
		'comprobante_id' => 'int',
		'fecha_timbrado' => 'datetime'
	];

	protected $fillable = [
		'comprobante_id',
		'fecha_timbrado',
		'no_certificado_sat',
		'rfc_prov_certif',
		'sello_cfd',
		'sello_sat',
		'uuid',
		'version'
	];

	public function comprobante()
	{
		return $this->belongsTo(Comprobante::class);
	}
}
