<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ingreso extends Model
{
     protected $table = 'ingreso';
   protected $fillable = ['id', 'fecha', 'importe', 'recibidoDe', 'glosa', 'idTipoIngreso', 'idPuntoVenta', 'txnOrigen', 'eliminado'];
}
