<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Puntoventa extends Model
{
protected $table = 'puntoventa';
   protected $fillable = ['id', 'idSucursal', 'idEmpleado','fechainicio','fechafin'];
}
