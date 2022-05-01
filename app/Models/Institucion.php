<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institucion extends Model
{
    use HasFactory;
    protected $table="institucion";
    protected $primaryKey = 'IDINSTITUCION';

    protected $fillable = [
		
		'NOMBREINST',
		'DIRECCION',
		'TELEFONO',
		'EMAIL',
		'RUC',
		'CELULAR',
		'LOGO',
		'ESTADO',
		'PAGINAWEB',
    ];
}

