<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materiales extends Model
{
    use HasFactory;

    protected $table="materiales";

    protected $fillable = [
		'nombre',
		'detalle',
		'codigo',
		'stock',
		'total',
		'estado',
		'categoriasmat_id',
		'compras_id'
    ];

    public function categoriasmat()
    {
        return $this->belongsTo('App\Models\Categoriasmat');
    }
}

