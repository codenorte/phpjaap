<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aguasobrante extends Model
{
    use HasFactory;
    protected $table="aguasobrante";
    protected $primaryKey = 'IDAGUASOBRANTE';

    protected $fillable = [

		'IDUSUARIO',
		'SECTOR',
		'REFERENCIA',
		'CODIGOAGUASOBRANTE',
		'OBSERVACION',
		'ESTADO',
		'VALORPORCONEXION',
		'PAGADO',
		'SALDO',
		'FECHA',
		
    ];

    public function usersSelect()
    {
        return $this->belongsTo('App\Models\User','IDUSUARIO')->select(['id','RUCCI','NOMBRES','APELLIDOS','APADOSN','SECTOR']);
    }
    public function users()
    {
        return $this->belongsTo('App\Models\User','IDUSUARIO');
    }
}
