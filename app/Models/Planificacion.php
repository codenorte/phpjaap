<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Planificacion extends Model
{
    use HasFactory;
    protected $table="planificacion";

    protected $primaryKey = 'IDPLANIFICACION';

    protected $fillable = [

    	'TIPOPLANIFICACION',
		'LUGAR',
		'FECHA',
		'VALORMULTA',
		'DESCRIPCION',
		'estado',
		
    ];
    public function asistencia()
    {
        return $this->hasMany('App\Models\Asistencia','IDPLANIFICACION','IDPLANIFICACION');
    }
}
