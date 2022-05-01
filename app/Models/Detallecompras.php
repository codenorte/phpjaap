<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detallecompras extends Model
{
    use HasFactory;

    protected $table="detallecompras";

    protected $fillable = [
		'nombre',
		'detalle',
		'codigo',
		'cantidad',
		'precio',
		'total',
		'estado',
		'compras_id',
		'materiales_id',
    ];
}
