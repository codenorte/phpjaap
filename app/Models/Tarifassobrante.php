<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarifassobrante extends Model
{
    use HasFactory;
    protected $table="tarifassobrante";
    protected $primaryKey = 'IDTARIFASSOBRANTE';

    protected $fillable = [
        
		'TARIFAMENSUAL',
		'DESCRIPCION',
		'IVA',
		'estado',
    ];
}
