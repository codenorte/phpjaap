<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;

    protected $table="compras";

    protected $fillable = [
		/*
		'cantidad',
		'precio',
		'total_compra',
		'fecha',
		'estado',
		'proveedor_id',
		'materiales_id',
*/


		'numfactura',
		'fechaemision',
		'subtotal',
		'iva',
		'total',
		'estado',
		'proveedor_id',
		'usuarioactual',
    ];

    public function detallecompras()
    {
        return $this->hasMany('App\Models\Detallecompras','compras_id','id');
    }
    public function proveedor()
    {
        return $this->belongsTo('App\Models\Proveedor','proveedor_id','id');
    }
}
