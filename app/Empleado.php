<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
	protected $table = 'empleado';
	   protected $fillable = ['id','nombre' ,'fechaNacimiento',
               'genero','telefonoFijo','celular','docIdentidad','correoElectronico',
               'idCargo','idTurno','eliminado','comision'];
}
