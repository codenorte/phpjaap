<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medidor extends Model
{
    use HasFactory;
    protected $table="medidor";
    protected $primaryKey = 'IDMEDIDOR';

     protected $fillable = [

        'IDUSUARIO',
        'SERIE',
        'NUMMEDIDOR',
        'CODIGO',
        'ESTADO',
        'VALORPORCONEXION',
        'PAGADO',
        'SALDO',
        'FECHA',
        'visto',
        'detallemat_id'
    ];


    public function users()
    {
        return $this->belongsTo('App\Models\User','IDUSUARIO');
    }
    public function usersName()
    {
        return $this->belongsTo('App\Models\User','IDUSUARIO')->select(['id','RUCCI','NOMBRES','APELLIDOS','APADOSN','SECTOR']);
    }
}
