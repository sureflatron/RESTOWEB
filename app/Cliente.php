<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{

	protected $table = 'cliente';
   protected $fillable = ['id', 'nombre', 'direccion', 'telefonoFijo', 'celular', 'correo', 
       'razonSocial', 'NIT','idCiudad','Preferencias','idTipoCliente','idDescuento','descuentoporcliente'];
 
}
