<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detallemat extends Model
{
    use HasFactory;

    protected $table="detallemat";

    protected $fillable = [
		
		'nombre',
		'detalle',
		'codigo',
		'serial',
		'estado',
		'materiales_id',
		'tipomat_id',
    ];

}
