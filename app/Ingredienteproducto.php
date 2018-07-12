<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ingredienteproducto extends Model
{
    	protected $table = 'ingredienteproducto';
   protected $fillable = ['id','idIngrediente' ,'idUnidadMedida','cantidad','costo'];
}
