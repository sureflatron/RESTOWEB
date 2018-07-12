<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TipoProducto extends Model {

    public $timestamps = false;
    protected $table = 'tipoproducto';
    protected $fillable = ['id', 'nombre', 'imagen', 'eliminado'];

}
