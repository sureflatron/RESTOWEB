<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{

    	protected $table = 'venta';
   protected $fillable = ['id','fecha' ,'hora','idPuntoVenta','formaPago','eliminado','idMesa','estado','idCliente'];
}
