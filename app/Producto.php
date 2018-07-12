<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model {

    protected $table = 'producto';
    protected $fillable = ['id', 'nombre', 'descripcion', 'precioVenta', 'idTipoProducto','idSubTipoProducto', 'imagen', 'tipoproducto', 'eliminado', 'created_at', 'updated_at', 'codigoInterno', 'codigoDeBarra', 'idOrigen', 'idMarca', 'material', 'usado',
        'color', 'tamano', 'peso', 'unidadesCaja', 'stockMin', 'stockMax', 'modelo', 'estilo', 'corte', 'precioVentaCredito', 'conStock','costo_pedido','costo_inventario'];

}
