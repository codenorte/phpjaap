<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aguaganaderia extends Model
{
    use HasFactory;

    protected $table="aguaganaderia";
    protected $primaryKey = 'IDAGUAGANADERIA';

    protected $fillable = [

		'IDUSUARIO',
		'SECTOR',
		'REFERENCIA',
		'CODIGOAGUAGANADERIA',
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
