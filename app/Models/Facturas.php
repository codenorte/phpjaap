<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facturas extends Model
{
    use HasFactory;
    protected $table="facturas";
    protected $primaryKey = 'IDFACTURA';

    protected $fillable = [
        
		'NUMFACTURA',
		'FECHAEMISION',
		'SUBTOTAL',
		'IVA',
		'TOTAL',
		'USUARIOACTUAL',
		'estado',
    ];
    public function detallefactura()
    {
        return $this->hasMany('App\Models\Detallefactura','IDFACTURA','IDFACTURA');
    }
    public function detallefacturaselect()
    {
        return $this->hasMany('App\Models\Detallefactura','IDFACTURA','IDFACTURA')
        ->select(['IDDETALLEFAC','IDMEDIDOR','CONSUMO','MEDEXCEDIDO','TAREXCEDIDO','APORTEMINGA','ALCANTARILLADO','SUBTOTAL','TOTAL','IDFACTURA']);
    }
    public function otrospagos()
    {
        return $this->hasMany('App\Models\Otrospagos','IDFACTURA','IDFACTURA');
    }
}

