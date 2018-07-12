<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detalleinventario extends Model {

    protected $table = 'detalleinventario';
    protected $fillable = ['id', 'idProducto', 'idUnidadMedida', 'cantidad', 'IdInventario', 'costo', 'total'];

}
