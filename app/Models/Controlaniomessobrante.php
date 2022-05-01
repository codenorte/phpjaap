<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Controlaniomessobrante extends Model
{
    use HasFactory;
    protected $table="controlaniomessobrante";

    protected $fillable = [
        'aniomes',
        'detalle',
        'estado'
    ];
    public function detallefacturasobrante()
    {
        return $this->hasMany('App\Models\Detallefacturasobrante','controlaniomessobrante_id','id');
    }
}
