<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;

    protected $table="proveedor";

    protected $fillable = [

		'ciruc',
		'nombres',
		'apellidos',
		'razon_social',
		'direccion',
		'celular',
		'telefono',
		'email',
		'pagina_web',
		'estado',
		
    ];
}

