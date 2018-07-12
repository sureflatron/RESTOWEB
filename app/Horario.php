<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    	protected $table = 'horario';
   protected $fillable = ['id', 'horaEntrada','dia', 'horaSalida', 'eliminado', 'idTurno'];
}
