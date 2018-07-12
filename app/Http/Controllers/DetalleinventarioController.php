<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Redirect;
use DB;
use App\Detalleinventario;
use App\Http\Requests;

class DetalleinventarioController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
//
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
     * @return \Illuminate\Http\Response  idProducto`, `idUnidadMedida`, `IdInventario`, `cantidad`
     */
    public function store(Request $request) {
        $validar = self::validardosveces($request->producto, $request->inventario);
        if ($validar == 0) {
            DB::table('detalleinventario')->insert([
                'idUnidadMedida' => $request->unidad,
                'idProducto' => $request->producto,
                'IdInventario' => $request->inventario,
                'cantidad' => $request->cantidad,
                'costo' => $request->costo,
                'total' => $request->total
            ]);
        } else {
            return response()->json(["mensaje" => "El producto ya esta en el inventario, Seleccione Otro"]);
        }
        return response()->json(["mensaje" => "Producto agregado con exito al Inventario"]);
    }

    public function validardosveces($idProducto, $idInventario) {
        $datos = 0;
        $results = DB::select("SELECT count(detalleinventario.idProducto) as repite
                FROM detalleinventario
                INNER JOIN inventario on inventario.id = detalleinventario.idInventario and inventario.estado = 0
                WHERE detalleinventario.idProducto = ? and inventario.id = ?
                GROUP BY detalleinventario.idProducto
                HAVING count(*) >0", [$idProducto, $idInventario]);
        foreach ($results as $key => $value) {
            $datos = $value->repite;
        }
        if ($datos > 0) {
            $datos = 1;
        } else {
            $datos = 0;
        }
        return $datos;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $dato = DB::table('detalleinventario')->select(
                        'detalleinventario.cantidad', 'detalleinventario.idUnidadMedida', 'detalleinventario.id', 'producto.nombre', 'detalleinventario.total', 'detalleinventario.costo', 'detalleinventario.idProducto')
                ->where('detalleinventario.id', $id)
                ->join('producto', 'producto.id', '=', 'detalleinventario.idProducto')
                ->get();
        return response()->json($dato);
        //    return  $dato;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $dato = DB::table('detalleinventario')->select(
                        'detalleinventario.cantidad', 'detalleinventario.idUnidadMedida', 'detalleinventario.id', 'producto.nombre')
                ->where('detalleinventario.id', $id)
                ->join('producto', 'producto.id', '=', 'detalleinventario.idProducto')
                ->get();
        return response()->json($dato);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $cost = $request->costo;
//        $costo = DB::select("SELECT detalleinventario.costo FROM detalleinventario WHERE detalleinventario.id = ?", [$id]);
//        foreach ($costo as $key => $value) {
//            $cost = $value->costo;
//        }
        $newcost = $request->cantidad * $cost;
        $actua = DB::table('detalleinventario')
                ->where('id', $id)
                ->update([ 'cantidad' => $request->cantidad, 'total' => $newcost, 'costo' => $cost]);
        return response()->json($actua);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        DB::table('detalleinventario')->where('id', $id)->delete();
        return response()->json($id);
    }

    public function listarinventariototal($id) {
        $results = DB::select("SELECT detalleinventario.id,
 detalleinventario.cantidad,
 producto.nombre as producto,
 producto.imagen,
 producto.descripcion, producto.color,
 producto.tamano as talla,
 (select marca.nombre
from marca
where producto.idMarca = marca.id) as marca,
 unidadmedida.nombre as unidad,
 detalleinventario.costo,
 (SELECT sum(detalleinventario.total)
from detalleinventario
WHERE detalleinventario.IdInventario = ?) as total,
 detalleinventario.total as subtotal
from detalleinventario
INNER JOIN unidadmedida
INNER JOIN producto
WHERE detalleinventario.idProducto = producto.id
and detalleinventario.idUnidadMedida = unidadmedida.id
and detalleinventario.IdInventario = ?", [$id, $id]);
        return response()->json($results);
    }

    public function obtenerstock($id, $almacen) {
        $stock = 0;
        $actual = DB::select("SELECT
                `v_stockalmacensucursal`.`stock`
            FROM
                `v_stockalmacensucursal`
            WHERE
                v_stockalmacensucursal.`idproducto` = ? 
            AND 
                `v_stockalmacensucursal`.`idalmacen` = ?", [$id, $almacen]);
        foreach ($actual as $key => $value) {
            $stock = $value->stock;
        }
        if ($stock == "null") {
            $stock = 0;
        }
        return response()->json(["stock" => $stock]);
    }

    public function actualizartipo($id, $tipo) {
        if ($tipo == "Ingreso") {
            $actual = DB::table('inventario')
                    ->where('id', $id)
                    ->update(['idtipoinventario' => $tipo,
                'idAlmacenDestino' => 1,
                'idAlmacen' => 0]);
            return response()->json(["mensaje" => $actual]);
        }
        if ($tipo == "Egreso") {
            $actual = DB::table('inventario')
                    ->where('id', $id)
                    ->update(['idtipoinventario' => $tipo,
                'idAlmacen' => 1,
                'idAlmacenDestino' => 0]);
            return response()->json(["mensaje" => $actual]);
        }
        if ($tipo == "Traspaso") {
            $actual = DB::table('inventario')
                    ->where('id', $id)
                    ->update(['idtipoinventario' => $tipo,
                'idAlmacen' => 1,
                'idAlmacenDestino' => 2]);
            $result = DB::select("select * from inventario where id = ?", [$id]);
            return response()->json($result);
        }
    }

}
