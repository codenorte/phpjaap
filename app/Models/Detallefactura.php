<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detallefactura extends Model
{
    use HasFactory;
    protected $table="detallefactura";
    protected $primaryKey = 'IDDETALLEFAC';

    protected $fillable = [
		'IDTARIFAS',
		'IDMEDIDOR',
		'ANIOMES',
		'MEDIDAANT',
		'MEDIDAACT',
		'CONSUMO',
		'MEDEXCEDIDO',
		'TAREXCEDIDO',
        'APORTEMINGA',
        'ALCANTARILLADO',
		'SUBTOTAL',
		'TOTAL',
		'OBSERVACION',
        'DETALLE',
		'estado',
		'IDFACTURA',
		'controlaniomes_id',
		'NUMFACTURA'
    ];

    public function medidor()
    {
        return $this->belongsTo('App\Models\Medidor','IDMEDIDOR','IDMEDIDOR');
    }
    public function medidorUsuario()
    {
        return $this->belongsTo('App\Models\Medidor','IDMEDIDOR','IDMEDIDOR')
        ->select(['IDMEDIDOR', 'NUMMEDIDOR','CODIGO','users.id as users_id','users.RUCCI','users.NOMBRES','users.APELLIDOS','users.APADOSN'])
        ->join('users','users.id','medidor.IDUSUARIO');
    }
    public function medidoractivo()
    {
        return $this->belongsTo('App\Models\Medidor','IDMEDIDOR','IDMEDIDOR')->where('ESTADO', 'ACTIVO');
    }
    //scopes
    public function scopeDetallefacturaActivos($query)
    {
        return $query->select('estado')->where('estado', 1);
    }
 
    public function scopeDetallefacturaInactivos($query)
    {
        return $query->where('estado', 0);
    }

    public function facturas()
    {
        return $this->belongsTo('App\Models\Facturas','IDFACTURA','IDFACTURA');
    }
}

