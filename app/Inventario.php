<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
   	protected $table = 'inventario';
   protected $fillable = ['id', 'fecha', 'idAlmacen', 'idMotivomovimiento', 'idPuntoventa', 'glosa', 'idtipoingreso'];
}
