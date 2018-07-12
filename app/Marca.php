<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    protected $table = 'marca';
   protected $fillable = ['id', 'nombre', 'abreviatura', 'eliminado'];
}
