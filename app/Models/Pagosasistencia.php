<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pagosasistencia extends Model
{
    use HasFactory;
    protected $table="pagosasistencia";
    protected $primaryKey = 'IDPAGOASISTENCIA';

    protected $fillable = [

	    'IDASISTENCIA',
		'FECHAPAGO',
		'NUMMINGAS',
		'VALORMINGAS',
		'OBSERVACION',
		'USUARIOACTUAL',
		'NUMFACTURA',
		'estado',
    ];

    public function asistencia()
    {
        return $this->belongsTo('App\Models\Asistencia','IDASISTENCIA');
    }

    public function asistenciaselect()
    {
        return $this->belongsTo('App\Models\Asistencia','IDASISTENCIA')
        ->select(['IDASISTENCIA','IDMEDIDOR','VALORMULTA']);
    }

}
