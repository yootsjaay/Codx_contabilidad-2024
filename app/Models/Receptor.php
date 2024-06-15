<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Receptor
 * 
 * @property int $id
 * @property int|null $comprobante_id
 * @property string|null $domicilio_fiscal_receptor
 * @property string|null $nombre
 * @property string|null $regimen_fiscal_receptor
 * @property string|null $rfc
 * @property string|null $uso_cfdi
 * 
 * @property Comprobante|null $comprobante
 *
 * @package App\Models
 */
class Receptor extends Model
{
	protected $table = 'receptor';
	public $timestamps = false;

	protected $casts = [
		'comprobante_id' => 'int'
	];

	protected $fillable = [
		'comprobante_id',
		'domicilio_fiscal_receptor',
		'nombre',
		'regimen_fiscal_receptor',
		'rfc',
		'uso_cfdi'
	];

	public function comprobante()
	{
		return $this->belongsTo(Comprobante::class);
	}
}
