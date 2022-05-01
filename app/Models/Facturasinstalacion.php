<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facturasinstalacion extends Model
{
    use HasFactory;
    protected $table="facturasinstalacion";
    protected $primaryKey = 'IDFACTURA';

    protected $fillable = [
        
		'NUMFACTURA',
		'FECHAEMISION',
		'TOTAL',
		'USUARIOACTUAL',
		'estado',
    ];

    public function detallefacturainstalacion()
    {
        return $this->hasMany('App\Models\Detallefacturainstalacion','IDFACTURA','IDFACTURA');
    }
}
