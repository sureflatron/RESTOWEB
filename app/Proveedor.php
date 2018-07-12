<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
protected $table = 'proveedor';
   protected $fillable = ['id', 'nombre', 'direccion', 'telefono', 'paginaWeb', 'contactoRef', 'telefonoContacto', 'correoContato', 'idCiudad', 'eliminado' ];
}
