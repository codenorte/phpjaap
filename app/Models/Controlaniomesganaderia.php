<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Controlaniomesganaderia extends Model
{
    use HasFactory;
    protected $table="controlaniomesganaderia";

    protected $fillable = [
        'aniomes',
        'detalle',
        'estado'
    ];

    public function detallefacturaganaderia()
    {
        return $this->hasMany('App\Models\Detallefacturaganaderia','controlaniomesganaderia_id','id');
    }

}
