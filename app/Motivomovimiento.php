<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Motivomovimiento extends Model
{
  	protected $table = 'motivomovimiento';
   protected $fillable = ['id', 'nombre', 'eliminado'];
}
