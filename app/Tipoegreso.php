<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tipoegreso extends Model
{
protected $table = 'tipoegreso';
   protected $fillable = ['id', 'nombre', 'eliminado' ];
}
