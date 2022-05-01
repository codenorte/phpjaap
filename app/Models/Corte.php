<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Corte extends Model
{
    use HasFactory;
    protected $table="corte";
    protected $primaryKey = 'IDCORTE';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

		'IDMEDIDOR',
		'CORTE',
		'FECHA',
		'OBSERVACION',
		'MULTA',
		'MORA',
		'PAGADO',
		'estado',
    ];

    public function medidor()
    {
        return $this->belongsTo('App\Models\Medidor','IDMEDIDOR','IDMEDIDOR');
    }

    public function medidorSelect()
    {
        return $this->belongsTo('App\Models\Medidor','IDMEDIDOR')->select(['IDMEDIDOR','IDUSUARIO','NUMMEDIDOR','ESTADO','VALORPORCONEXION','PAGADO']);
    }
}
