<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detalleventa extends Model
{
    	protected $table = 'detalleventa';
   protected $fillable = ['idVenta','idProducto' ,'cantidad','total', 'id'];
}
