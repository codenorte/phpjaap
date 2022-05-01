<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detallefacturainstalacion extends Model
{
    use HasFactory;
    protected $table="detallefacturainstalacion";

    protected $primaryKey = 'IDDETALLEFAC';

    protected $fillable = [
        
		'IDMEDIDOR',
		'TOTAL',
		'OBSERVACION',
		'estado',
		'IDFACTURA',
		'NUMFACTURA',
    ];
}
