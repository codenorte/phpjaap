<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detallefacturasobrante extends Model
{
    use HasFactory;
    protected $table="detallefacturasobrante";

    protected $primaryKey = 'IDDETALLEFACSOBRANTE';

    protected $fillable = [
        
		'IDTARIFASSOBRANTE',
		'IDAGUASOBRANTE',
		'ANIOMES',
		'SUBTOTAL',
		'TOTAL',
		'OBSERVACION',
		'DETALLE',
		'estado',
		'controlaniomessobrante_id',
		'IDFACTURASOBRANTE',
		'NUMFACTURA',
    ];

    public function aguasobrante()
    {
        return $this->belongsTo('App\Models\Aguasobrante','IDAGUASOBRANTE');
    }
}