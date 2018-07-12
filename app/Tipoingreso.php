<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tipoingreso extends Model
{
    protected $table = 'tipoingreso';
   protected $fillable = ['id', 'nombre', 'eliminado' ];
}
