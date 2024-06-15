<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Comprobante
 * 
 * @property int $id
 * @property string|null $certificado
 * @property string|null $exportacion
 * @property Carbon|null $fecha
 * @property string|null $folio
 * @property string|null $forma_pago
 * @property string|null $lugar_expedicion
 * @property string|null $metodo_pago
 * @property string|null $moneda
 * @property string|null $no_certificado
 * @property string|null $sello
 * @property string|null $serie
 * @property float|null $sub_total
 * @property float|null $tipo_cambio
 * @property string|null $tipo_comprobante
 * @property float|null $total
 * @property string|null $version
 * 
 * @property Collection|Concepto[] $conceptos
 * @property Collection|Emisor[] $emisors
 * @property Collection|Receptor[] $receptors
 * @property Collection|TimbreFiscalDigital[] $timbre_fiscal_digitals
 *
 * @package App\Models
 */
class Comprobante extends Model
{
	protected $table = 'comprobantes';
	public $timestamps = false;

	protected $casts = [
		'fecha' => 'datetime',
		'sub_total' => 'float',
		'tipo_cambio' => 'float',
		'total' => 'float'
	];

	protected $fillable = [
		'certificado',
		'exportacion',
		'fecha',
		'folio',
		'forma_pago',
		'lugar_expedicion',
		'metodo_pago',
		'moneda',
		'no_certificado',
		'sello',
		'serie',
		'sub_total',
		'tipo_cambio',
		'tipo_comprobante',
		'total',
		'version'
	];

	public function conceptos()
	{
		return $this->hasMany(Concepto::class);
	}

	public function emisors()
	{
		return $this->hasMany(Emisor::class);
	}

	public function receptors()
	{
		return $this->hasMany(Receptor::class);
	}

	public function timbre_fiscal_digitals()
	{
		return $this->hasMany(TimbreFiscalDigital::class);
	}
}
