<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalletranspaso extends Model
{
    use HasFactory;
    protected $table="detalletranspaso";

    protected $fillable = [

		'cantidad',
		'detalle',
		'precio',
		'subtotal',
		'IDMATERIALES',
		'IDTRANSPASO',
		'estado',
    ];
}

