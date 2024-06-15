<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Emisor
 * 
 * @property int $id
 * @property int|null $comprobante_id
 * @property string|null $nombre
 * @property string|null $regimen_fiscal
 * @property string|null $rfc
 * 
 * @property Comprobante|null $comprobante
 *
 * @package App\Models
 */
class Emisor extends Model
{
	protected $table = 'emisor';
	public $timestamps = false;

	protected $casts = [
		'comprobante_id' => 'int'
	];

	protected $fillable = [
		'comprobante_id',
		'nombre',
		'regimen_fiscal',
		'rfc'
	];

	public function comprobante()
	{
		return $this->belongsTo(Comprobante::class);
	}
}
