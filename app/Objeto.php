<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Objeto extends Model
{
    	protected $table = 'objeto';
   protected $fillable = ['id', 'nombre', 'tipoObjeto', 'urlObjeto', 'habilitado', 'visibleEnMenu', 'idModulo', 'eliminado'];
}
