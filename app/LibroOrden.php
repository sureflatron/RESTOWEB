<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LibroOrden extends Model
{
  	protected $table = 'libroorden';
   protected $fillable = ['id', 'idSucursal', 'NIT', 'nroAutorizacion', 'nroInicio', 'nroFin', 'tipo', 'fechaInicio', 'fechaFin', 'estado', 'eliminado','llave'];
}
