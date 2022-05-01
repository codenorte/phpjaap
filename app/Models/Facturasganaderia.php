<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facturasganaderia extends Model
{
    use HasFactory;
    protected $table="facturasganaderia";
    protected $primaryKey = 'IDFACTURASGANADERIA';

    protected $fillable = [
        
		'NUMFACTURA',
		'FECHAEMISION',
		'SUBTOTAL',
		'IVA',
		'TOTAL',
		'USUARIOACTUAL',
		'estado',
    ];

    public function detallefacturaganaderia()
    {
        return $this->hasMany('App\Models\Detallefacturaganaderia','IDFACTURASGANADERIA','IDFACTURASGANADERIA');
    }
}
