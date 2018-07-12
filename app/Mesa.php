<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mesa extends Model
{
  	protected $table = 'mesa';
   protected $fillable = ['id','numeromesa', 'ubicacion', 'capacidad', 'estado', 'idSucual','eliminado'];
}
