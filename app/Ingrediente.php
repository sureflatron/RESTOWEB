<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ingrediente extends Model
{
    	protected $table = 'ingrediente';
   protected $fillable = ['id','nombre' ,'eliminado'];
}

