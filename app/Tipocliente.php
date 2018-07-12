<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tipocliente extends Model {

    protected $table = 'tipocliente';
    protected $fillable = ['id', 'nombre', 'abreviatura', 'eliminado'];

}
