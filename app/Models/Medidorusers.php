<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medidorusers extends Model
{
    use HasFactory;
    protected $table="medidorusers";

    protected $fillable = [
        'FECHA',
        'IDUSUARIO',
        'IDUSUARIO_HIJO',
        'IDMEDIDOR',
        'ESTADO',
        'NIVEL',
    ];

    public function users()
    {
        return $this->belongsTo('App\Models\User','IDUSUARIO');
    }

    public function medidor()
    {
        return $this->belongsTo('App\Models\Medidor','IDMEDIDOR','IDMEDIDOR');
    }
}
