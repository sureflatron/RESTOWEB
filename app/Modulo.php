<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
  	protected $table = 'modulo';
   protected $fillable = ['id', 'nombre', 'eliminado'];
}
