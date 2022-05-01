<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facturatranspaso extends Model
{
    use HasFactory;
    protected $table="facturatranspaso";

    protected $fillable = [

		'NUMFACTURA',
		'FECHAEMISION',
		'SUBTOTAL',
		'IVA',
		'TOTAL',
		'USUARIOACTUAL',
		'IDTRANSPASO',
		'estado',
    ];
}
