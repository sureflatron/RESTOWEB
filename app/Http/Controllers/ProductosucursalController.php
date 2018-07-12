<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input as Input;
use Illuminate\Http\Request;
use App\Producto;
use App\Sucursal;
use App\ProductoSucursal;
use DB;
use Session;
use Redirect;
use App\Http\Requests;

class ProductosucursalController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        
    }

    public function productosucursal() {
        return view('Producto.Productosucursal');
    }

    public function productosucursal2() {
        return view('Producto.productosucursal');
    }

    public function productosucursal3() {
        return view('Producto.productosucursal3');
    }

    public function listadeproductosucursales($valor) {
        if ($valor == 0) {
            $consulta = DB::select('     
                SELECT productosucursal.idproducto AS id,producto.tipoproducto AS tipo,
               (SELECT tipoproducto.nombre FROM tipoproducto WHERE tipoproducto.id = producto.idTipoProducto) AS categoria,
                producto.nombre,                
                producto.modelo,
                producto.estilo,
                producto.corte,
                productosucursal.precioVenta,
                productosucursal.precioVentaCredito,
                producto.descripcion ,
                producto.codigoInterno,
                producto.codigoDeBarra ,
                producto.material,
                producto.usado,
                producto.color,
                producto.tamano,
                producto.peso ,
                producto.unidadesCaja ,
                producto.stockMin,
                producto.stockMax,
                (SELECT marca.nombre FROM marca WHERE marca.id = producto.idMarca) AS marca,
                (SELECT origen.nombre FROM origen WHERE origen.id = producto.idOrigen) AS origen,
                sucursal.nombre AS sucursal,
                sucursal.id AS idsucursal
                FROM producto,sucursal,productosucursal
                WHERE producto.eliminado = 0
                AND producto.id=productosucursal.idproducto AND productosucursal.idsucursal=sucursal.id 0                 
                UNION               
                SELECT    producto.id,
                producto.tipoproducto AS tipo,
                (SELECT tipoproducto.nombre FROM tipoproducto WHERE tipoproducto.id = producto.idTipoProducto) AS categoria,
                producto.nombre,                
                producto.modelo,
                producto.estilo,
                producto.corte,
                producto.precioVenta,
                producto.precioVentaCredito,
                producto.descripcion ,
                producto.codigoInterno,
                producto.codigoDeBarra ,
                producto.material,
                producto.usado,
                producto.color,
                producto.tamano,
                producto.peso ,
                producto.unidadesCaja ,
                producto.stockMin,
                producto.stockMax,
                (SELECT marca.nombre FROM marca WHERE marca.id = producto.idMarca) AS marca,
                (SELECT origen.nombre FROM origen WHERE origen.id = producto.idOrigen) AS origen,
                "NO ASIGNADO" AS sucursal,
                "0" AS idsucursal
                FROM producto 
                WHERE   producto.id NOT IN(SELECT productosucursal.idproducto FROM productosucursal)                
                ORDER BY idsucursal DESC ');
            return response()->json($consulta);
        } else {
            $consulta = DB::select('SELECT  productosucursal.idproducto AS id,
		 producto.tipoproducto AS tipo,
                (SELECT tipoproducto.nombre FROM tipoproducto WHERE tipoproducto.id = producto.idTipoProducto) AS categoria,
                producto.nombre,                
                producto.modelo,
                producto.estilo,
                producto.corte,
                productosucursal.precioVenta ,
                producto.descripcion ,
                producto.codigoInterno,
                producto.codigoDeBarra ,
                producto.material,
                producto.usado,
                producto.color,
                producto.tamano,
                producto.peso ,
                producto.unidadesCaja ,
                producto.stockMin,
                producto.stockMax,
                (SELECT marca.nombre FROM marca WHERE marca.id = producto.idMarca) AS marca,
                (SELECT origen.nombre FROM origen WHERE origen.id = producto.idOrigen) AS origen,
                productosucursal.idsucursal,
                sucursal.nombre AS sucursal
                FROM producto,productosucursal,sucursal 
                WHERE producto.id=productosucursal.idproducto AND sucursal.id=productosucursal.idsucursal AND sucursal.id=? AND  producto.eliminado = 0
                UNION 
                SELECT 
                producto.id,
                producto.tipoproducto AS tipo,
                (SELECT tipoproducto.nombre FROM tipoproducto WHERE tipoproducto.id = producto.idTipoProducto) AS categoria,
                producto.nombre,                
                producto.modelo,
                producto.estilo,
                producto.corte,
                producto.precioVenta ,
                producto.descripcion ,
                producto.codigoInterno,
                producto.codigoDeBarra ,
                producto.material,
                producto.usado,
                producto.color,
                producto.tamano,
                producto.peso ,
                producto.unidadesCaja ,
                producto.stockMin,
                producto.stockMax,
                (SELECT marca.nombre FROM marca WHERE marca.id = producto.idMarca) AS marca,
                (SELECT origen.nombre FROM origen WHERE origen.id = producto.idOrigen) AS origen,              
                "0" AS idsucursal,
                "NO ASIGNADO" AS sucursal
                FROM producto
                WHERE producto.id  NOT IN (SELECT productosucursal.idproducto FROM productosucursal where productosucursal.idsucursal=?) AND  producto.eliminado = 0   
                ORDER BY id ASC              
                ', [$valor, $valor]);
            return response()->json($consulta);
        }
    }

    public function listaproductosucursal() {
        $productos = DB::select(' SELECT producto.tipoproducto as tipo,
                (select tipoproducto.nombre from tipoproducto where tipoproducto.id = producto.idTipoProducto) as categoria,
                producto.nombre,
                producto.id,
                producto.modelo,
                producto.estilo,
                producto.corte,
                producto.precioVenta ,
                producto.precioVentaCredito,
                producto.descripcion ,
                producto.codigoInterno,
                producto.codigoDeBarra ,
                producto.material,
                producto.usado,
                producto.color,
                producto.tamano,
                producto.peso ,
                producto.unidadesCaja ,
                producto.stockMin,
                producto.stockMax,
                (select marca.nombre from marca where marca.id = producto.idMarca) as marca,
                (select origen.nombre from origen where origen.id = producto.idOrigen) as origen
                FROM producto WHERE producto.eliminado = 0;');
        return response()->json($productos);
    }

    public function listarproductosucursal() {
        $productos = DB::select('SELECT producto.tipoproducto as tipo,
                (select tipoproducto.nombre from tipoproducto where tipoproducto.id = producto.idTipoProducto) as categoria,
                producto.nombre,
                producto.id,
                producto.modelo,
                producto.estilo,
                producto.corte,
                producto.precioVenta ,
                producto.precioVentaCredito,
                producto.descripcion ,
                producto.codigoInterno,
                producto.codigoDeBarra ,
                producto.material,
                producto.usado,
                producto.color,
                producto.tamano,
                producto.peso ,
                producto.unidadesCaja ,
                producto.stockMin,
                producto.stockMax,
                (select marca.nombre from marca where marca.id = producto.idMarca) as marca,
                (select origen.nombre from origen where origen.id = producto.idOrigen) as origen,
                sucursal.nombre as sucursal
                FROM producto,sucursal,productosucursal
                WHERE producto.eliminado = 0
                AND producto.id=productosucursal.idproducto AND productosucursal.idsucursal=sucursal.id
                ;');
        return response()->json($productos);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $actua = DB::table('productosucursal')
                ->where('idproducto', $request->idproducto)
                ->where('idsucursal', $request->idsucursal)
                ->insert(['idproducto' => $request->idproducto,
            'idsucursal' => $request->idsucursal,
            'precioVenta' => $request->precioVenta,
            'precioVentaCredito' => $request->precioVentaCredito
        ]);
        return response()->json(["mensaje" => "Producto agregado con exito a la Venta"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $actua = DB::table('productosucursal')
                ->where('idproducto', $id)
                ->where('idsucursal', $request->idsucursal)
                ->update(['precioVenta' => $request->precioVenta, 'precioVentaCredito' => $request->precioVentaCredito]);
        return response()->json(["mensaje" => "Producto actualizado con exito a la Venta"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

    //VERSION 2 RONNY 


    public function sucursales() {
        $productos = DB::select(' SELECT producto.tipoproducto as tipo,
                (select tipoproducto.nombre from tipoproducto where tipoproducto.id = producto.idTipoProducto) as categoria,
                producto.nombre,
                producto.id,
                producto.modelo,
                producto.estilo,
                producto.corte,
                producto.precioVenta ,
                producto.precioVentaCredito,
                producto.descripcion ,
                producto.codigoInterno,
                producto.codigoDeBarra ,
                producto.material,
                producto.usado,
                producto.color,
                producto.tamano,
                producto.peso ,
                producto.unidadesCaja ,
                producto.stockMin,
                producto.stockMax,
                (select marca.nombre from marca where marca.id = producto.idMarca) as marca,
                (select origen.nombre from origen where origen.id = producto.idOrigen) as origen
                FROM producto WHERE producto.eliminado = 0;');
        return response()->json($productos);
    }

    public function consultaproductosucursal($valor) {
        $consulta = DB::select('
                SELECT  productosucursal.idproducto AS id,
		 producto.tipoproducto AS tipo,
                (SELECT tipoproducto.nombre FROM tipoproducto WHERE tipoproducto.id = producto.idTipoProducto) AS categoria,
                producto.nombre,                
                producto.modelo,
                producto.estilo,
                producto.corte,
                productosucursal.precioVenta ,
                productosucursal.precioVentaCredito,
                producto.descripcion ,
                producto.codigoInterno,
                producto.codigoDeBarra ,
                producto.material,
                producto.usado,
                producto.color,
                producto.tamano,
                producto.peso ,
                producto.unidadesCaja ,
                producto.stockMin,
                producto.stockMax,
                (SELECT marca.nombre FROM marca WHERE marca.id = producto.idMarca) AS marca,
                (SELECT origen.nombre FROM origen WHERE origen.id = producto.idOrigen) AS origen,
                productosucursal.idsucursal,
                sucursal.nombre AS sucursal
                FROM (producto INNER JOIN productosucursal ON producto.id=productosucursal.idproducto AND producto.eliminado=0) 
                INNER JOIN sucursal ON productosucursal.idsucursal=sucursal.id AND sucursal.id=?
                UNION
                SELECT 
                producto.id,
                producto.tipoproducto AS tipo,
                (SELECT tipoproducto.nombre FROM tipoproducto WHERE tipoproducto.id = producto.idTipoProducto) AS categoria,
                producto.nombre,                
                producto.modelo,
                producto.estilo,
                producto.corte,
                producto.precioVenta ,
                producto.precioVentaCredito,
                producto.descripcion ,
                producto.codigoInterno,
                producto.codigoDeBarra ,
                producto.material,
                producto.usado,
                producto.color,
                producto.tamano,
                producto.peso ,
                producto.unidadesCaja ,
                producto.stockMin,
                producto.stockMax,
                (SELECT marca.nombre FROM marca WHERE marca.id = producto.idMarca) AS marca,
                (SELECT origen.nombre FROM origen WHERE origen.id = producto.idOrigen) AS origen,               
                "0" AS idsucursal,
                "NO ASIGNADO" AS sucursal
                FROM producto
                WHERE producto.id  NOT IN (SELECT DISTINCT(productosucursal.idproducto) FROM productosucursal WHERE productosucursal.idsucursal=?) AND  producto.eliminado = 0   
                ORDER BY idsucursal DESC              
                ', [$valor, $valor]);
        return response()->json($consulta);
    }

}
