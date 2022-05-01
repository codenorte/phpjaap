<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facturassobrante extends Model
{
    use HasFactory;
    protected $table="facturassobrante";
    protected $primaryKey = 'IDFACTURASOBRANTE';

    protected $fillable = [
        
		'NUMFACTURA',
		'FECHAEMISION',
		'SUBTOTAL',
		'IVA',
		'TOTAL',
		'USUARIOACTUAL',
		'estado',
    ];
    public function detallefacturasobrante()
    {
        return $this->hasMany('App\Models\Detallefacturasobrante','IDFACTURASOBRANTE','IDFACTURASOBRANTE');
    }
}
