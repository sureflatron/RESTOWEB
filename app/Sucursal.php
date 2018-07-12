<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
protected $table = 'sucursal';
   protected $fillable = ['id', 'nombre', 'direccion', 'telefono', 'idCiudad', 'eliminado','idEmpresa'];
}
