<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Traslado
 * 
 * @property int $id
 * @property int|null $concepto_id
 * @property float|null $base
 * @property float|null $importe
 * @property string|null $impuesto
 * @property float|null $tasa_o_cuota
 * @property string|null $tipo_factor
 * 
 * @property Concepto|null $concepto
 *
 * @package App\Models
 */
class Traslado extends Model
{
	protected $table = 'traslados';
	public $timestamps = false;

	protected $casts = [
		'concepto_id' => 'int',
		'base' => 'float',
		'importe' => 'float',
		'tasa_o_cuota' => 'float'
	];

	protected $fillable = [
		'concepto_id',
		'base',
		'importe',
		'impuesto',
		'tasa_o_cuota',
		'tipo_factor'
	];

	public function concepto()
	{
		return $this->belongsTo(Concepto::class);
	}
}
