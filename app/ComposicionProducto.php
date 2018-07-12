<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComposicionProducto extends Model
{
    	protected $table = 'composicionproducto';
   protected $fillable = ['id','idProducto' ,'cantidad','eliminado'];
}
