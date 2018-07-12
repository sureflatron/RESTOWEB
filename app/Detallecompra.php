<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detallecompra extends Model
{
    protected $table = 'detallecompra';
   protected $fillable = ['id', 'idcompra', 'idProducto', 'cantidad', 'costo', 'total','idUnidadMedida'];
}
