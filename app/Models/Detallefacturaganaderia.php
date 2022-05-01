<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detallefacturaganaderia extends Model
{
    use HasFactory;
    protected $table="detallefacturaganaderia";
    protected $primaryKey = 'IDDETALLEFACGANADERIA';

    protected $fillable = [
        
		'IDTARIFASGANADERIA',
		'IDAGUAGANADERIA',
		'ANIOMES',
		'SUBTOTAL',
		'TOTAL',
		'OBSERVACION',
		'DETALLE',
		'estado',
		'controlaniomesganaderia_id',
		'IDFACTURASGANADERIA',
		'NUMFACTURA',
		
    ];

    public function aguaganaderia()
    {
        return $this->belongsTo('App\Models\Aguaganaderia','IDAGUAGANADERIA');
    }


}

