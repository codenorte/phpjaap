<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    use HasFactory;
    protected $table="asistencia";

    protected $primaryKey = 'IDASISTENCIA';

    protected $fillable = [

		'IDPLANIFICACION',
		'IDMEDIDOR',
		'ASISTENCIA',
		'VALORMULTA',
		'DESCRIPCION',
		'OBSEVACION',
		'estado',
		
    ];

    public function medidor()
    {
        return $this->belongsTo('App\Models\Medidor','IDMEDIDOR');
    }

    public function medidorSelect()
    {
        return $this->belongsTo('App\Models\Medidor','IDMEDIDOR')->select(['IDMEDIDOR','IDUSUARIO','NUMMEDIDOR','CODIGO','ESTADO']);
    }

    public function planificacion()
    {
        return $this->belongsTo('App\Models\Planificacion','IDPLANIFICACION');
    }
}