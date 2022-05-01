<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Controlaniomesdetallefactura extends Model
{
    use HasFactory;
    protected $table="controlaniomesdetallefacturas";

    protected $fillable = [
        'aniomes',
        'detalle',
        'conlectura',
		'sinlectura',
        'estado'
    ];

    public function detallefactura()
    {
        return $this->hasMany('App\Models\Detallefactura','controlaniomes_id');
    }
    /*
    public function med()
    {
        return $this->hasManyThrough(
            'App\Models\Medidor', // Modelo destino
            'App\Models\Detallefactura', // Modelo intermedio
            'controlaniomes_id', // Clave foránea en la tabla intermedia
            'IDMEDIDOR', // Clave primaria en la tabla de origen
            'id', // Clave primaria en la tabla intermedia
            'IDDETALLEFAC', // Clave foránea en la tabla de destino
        );
    }
    */
}
