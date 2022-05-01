<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transpaso extends Model
{
    use HasFactory;
    protected $table="transpaso";

    protected $fillable = [

		'fecha_transpaso',
		'detalle',
		'estado',
		'IDMEDIDOR',
    ];
}
