<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarifasganaderia extends Model
{
    use HasFactory;
    protected $table="tarifasganaderia";
    protected $primaryKey = 'IDTARIFASGANADERIA';

    protected $fillable = [
        
		'TARIFAMENSUAL',
		'DESCRIPCION',
		'IVA',
		'estado',
    ];
}
