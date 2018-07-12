<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
 protected $table = 'factura';
  protected $fillable = ['id', 'idLibroOrden', 'idVenta','idPuntoVenta', 'nroFactura', 'nroActual', 'nroAutorizacion', 'codigoControl', 'fecha', 'NIT', 'razonSocial', 'fechaLimite', 'total', 'totalLiteral', 'eliminado'];
}


