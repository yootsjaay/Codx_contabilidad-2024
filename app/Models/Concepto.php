<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Concepto
 * 
 * @property int $id
 * @property int|null $comprobante_id
 * @property float|null $cantidad
 * @property string|null $clave_prod_serv
 * @property string|null $clave_unidad
 * @property string|null $descripcion
 * @property float|null $importe
 * @property string|null $no_identificacion
 * @property string|null $objeto_imp
 * @property string|null $unidad
 * @property float|null $valor_unitario
 * 
 * @property Comprobante|null $comprobante
 * @property Collection|Traslado[] $traslados
 *
 * @package App\Models
 */
class Concepto extends Model
{
	protected $table = 'conceptos';
	public $timestamps = false;

	protected $casts = [
		'comprobante_id' => 'int',
		'cantidad' => 'float',
		'importe' => 'float',
		'valor_unitario' => 'float'
	];

	protected $fillable = [
		'comprobante_id',
		'cantidad',
		'clave_prod_serv',
		'clave_unidad',
		'descripcion',
		'importe',
		'no_identificacion',
		'objeto_imp',
		'unidad',
		'valor_unitario'
	];

	public function comprobante()
	{
		return $this->belongsTo(Comprobante::class);
	}

	public function traslados()
	{
		return $this->hasMany(Traslado::class);
	}
}
