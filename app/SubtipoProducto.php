<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubtipoProducto extends Model
{
    public $timestamps = false;
    protected $table = 'subtipoproducto';
    protected $fillable = ['id', 'nombre','imagen', 'eliminado','idtipoproducto'];
}
