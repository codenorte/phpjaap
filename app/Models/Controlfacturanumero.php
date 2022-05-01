<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Controlfacturanumero extends Model
{
    use HasFactory;
    protected $table="controlfacturanumeros";
    protected $fillable = [

        'TABLE',
		'TABLE_ID',
		'NUMFACTURA',
		'FECHA',
		'ESTADO',
    ];
}
