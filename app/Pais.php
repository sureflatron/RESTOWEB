<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pais extends Model
{
protected $table = 'pais';
   protected $fillable = ['id', 'nombre', 'eliminado' ];
}
