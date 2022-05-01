<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Controlmensualdetallefactura extends Model
{
    use HasFactory;
    protected $table="controlmensualdetallefacturas";

    protected $fillable = [
        'IDMEDIDOR',
        'ANIOMES',
        'estado',
        'IDDETALLEFAC'
    ];
}
