<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otrospagos extends Model
{
    use HasFactory;
    protected $table="otrospagos";
    protected $primaryKey = 'IDOTPAGOS';

    protected $fillable = [

		'IDCORTE',
		'DERCONX',
		'MULRECX',
		'INTERES',
		'TOTAL',
		'NUMFACTURA',
		'USUARIOACTUAL',
		'FECHAPAGO',
		'estado',
		'IDFACTURA',

    ];
}
