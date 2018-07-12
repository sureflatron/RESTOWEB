<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Egreso extends Model
{
  protected $table = 'egreso';
   protected $fillable = ['id', 'fecha', 'importe', 'pagadoA', 'glosa', 'idTipoEgreso', 'idPuntoVenta', 'idProveedor', 'txnOrigen', 'eliminado' ];
}
