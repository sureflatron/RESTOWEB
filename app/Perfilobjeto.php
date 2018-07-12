<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Perfilobjeto extends Model
{
 	protected $table = 'perfilobjeto';
   protected $fillable = ['idPerfil', 'idObjeto', 'puedeGuardar', 'puedeModificar', 'puedeEliminar', 'puedeListar', 'puedeVerReporte', 'puedeImprimir'];
}
