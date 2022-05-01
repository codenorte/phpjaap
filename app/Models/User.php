<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table="users";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'link',
        'usuario',
        'password',
        'email',
        'IDINSTITUCION',
        'RUCCI',
        'NOMBRES',
        'APELLIDOS',
        'APADOSN',
        'DIRECCION',
        'TELEFONO',
        'CELULAR',
        'SECTOR',
        'REFERENCIA',
        'OBSERVACION',
        'ESTADO',
        'VISTO',
        'api_token',
        'roles_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        //'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //un pais puede tener muchas provincias
    public function medidor()
    {
        return $this->hasMany('App\Models\Medidor','IDUSUARIO');
    }

    //un pais puede tener muchas provincias
    public function medidorusers()
    {
        return $this->hasMany('App\Models\Medidorusers','IDUSUARIO');
    }

    public function aguaganaderia()
    {
        return $this->hasMany('App\Models\Aguaganaderia','IDUSUARIO','id');
    }
    public function aguasobrante()
    {
        return $this->hasMany('App\Models\Aguasobrante','IDUSUARIO','id');
    }
}

