<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unidadmedida extends Model
{
    	protected $table = 'unidadmedida';
   protected $fillable = ['id','nombre' ,'abreviatura','eliminado'];
}
