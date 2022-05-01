<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoriasmat extends Model
{
    use HasFactory;

    protected $table="categoriasmat";

    protected $fillable = [
		'nombre',
		'detalle',
		'estado',
    ];

}
