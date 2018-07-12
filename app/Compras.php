<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Compras extends Model
{
    protected $table = 'compra';
   protected $fillable = ['id', 'fecha', 'total', 'estado','eliminado','idPuntoventa', 'idProveedor'];
}
