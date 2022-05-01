<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipomat extends Model
{
    use HasFactory;

    protected $table="tipomat";

    protected $fillable = [
		'nombre',
		'detalle',
		'estado',
    ];
}
