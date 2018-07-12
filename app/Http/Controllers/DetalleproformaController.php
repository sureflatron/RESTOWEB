<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Detalleventa;
use DB;
use Session;
use Redirect;
use App\Http\Requests;

class DetalleproformaController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        
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
        $venderconstocks = DB::Select("select * from empresa");
        $venderstock = $venderconstocks[0]->venderSinStock;
        $validar = self::validardosveces($request->idProducto, $request->idVenta);
        $almacen = $request->idAlmacen;
        $precioventanuevo = $request->precioventa;
        $precioventas = self::precioventaproducto($request->idProducto);
        $precioventa = $precioventas[0]->precioVenta;
        $stocks = self::obtenerstock($request->idProducto, $request->idAlmacen);
        if ($stocks == 0) {
            $stock = 0;
        } else {
            $stock = $stocks[0]->stock;
        }
//        if ($request->cantidad <= 0) {
//            return response()->json(["mensaje" => "La Cantidad debe ser mayor a 0"]);
//        }
        if ($validar == 0) {
//            if ($stock < $request->cantidad && $venderstock == 0) {
//                $mensaje = "Cantidad No Disponible. Solo hay " . $stock . " unidades";
//                return response()->json(["mensaje" => $mensaje]);
//            } 
//            else 
//                {
                $precioventanuevo = $request->precioventa;
                if ($precioventanuevo == "null") {
                    $precioventanuevo = $precioventa;
                }
                if ($precioventanuevo > $precioventa) {
                    return response()->json(["mensaje" => "El precio que inserto excede al precio actual del producto"]);
                }
                if ($precioventa == $precioventanuevo) {
                    
                } else if ($precioventanuevo <= 0) {
                    return response()->json(["mensaje" => "El precio de proforma del producto debe ser mayor a 0"]);
                }
                $totalimporte = $precioventa * $request->cantidad;
                $importedescuento = 0;
                $porcentajedescuento = 0;
                if ($precioventa !== $precioventanuevo) {
                    $importedescuento = $precioventa - $precioventanuevo;
                    $porcentajedescuento = ($importedescuento * 100) / $totalimporte;
                }

                $totalneto = $totalimporte - $importedescuento;
                $actua = DB::table('detalleproforma')
                        ->where('idProforma', $request->idVenta)
                        ->insert(['idProforma' => $request->idVenta,
                    'idProducto' => $request->idProducto,
                    'cantidad' => $request->cantidad,
                    'precio' => $precioventa,
                    'total' => $totalimporte,
                    'idtipodescuento' => 0,
                    'porcentajedescuento' => $importedescuento,
                    'importedescuento' => $porcentajedescuento,
                    'totalneto' => $totalneto,
                    'estado' => 2,
                    'nroFacturaCompra' => $request->nrofactura]);
//            }
            return response()->json(["mensaje" => "Producto Agregado con Exito a la Proforma"]);
        } else {
            $cantidad = $results = DB::select("SELECT detalleproforma.cantidad ,detalleproforma.precio, detalleproforma.importedescuento, detalleproforma.porcentajedescuento
                from detalleproforma
                WHERE detalleproforma.idProforma = ? AND detalleproforma.idProducto = ?", [$request->idVenta, $request->idProducto]);
            $cant = $cantidad[0]->cantidad;
            $precioventa = $cantidad[0]->precio;
            $importedescuento = $cantidad[0]->importedescuento;
            $porcentajedescuento = $cantidad[0]->porcentajedescuento;
            $cant = $cant + $request->cantidad;
            if ($stock < $cant && $venderstock == 0) {
                $mensaje = "Cantidad No Disponible. Solo hay " . $stock . " unidades";
                return response()->json(["mensaje" => $mensaje]);
            } else {
                $totalimporte = $precioventa * $cant;
                $importedescuento = ($totalimporte * $porcentajedescuento) / 100;
                $totalneto = ($totalimporte - $importedescuento);
                DB::table('detalleproforma')
                        ->where('idProforma', $request->idVenta)
                        ->where('idProducto', $request->idProducto)
                        ->update(['cantidad' => $cant,
                            'total' => $totalimporte,
                            'importedescuento' => $importedescuento,
                            'totalneto' => $totalneto]);
            }
            return response()->json(["mensaje" => "Producto Agregado con Exito a la Proforma"]);
        }
    }

    public function validardosveces($idProducto, $idVenta) {
        $datos = 0;
        $results = DB::select("SELECT
                count(detalleproforma.idProducto) as repite
            FROM detalleproforma
            INNER JOIN proforma on proforma.id=detalleproforma.idProforma 
                AND proforma.estado=0
            WHERE detalleproforma.idProducto=? 
                AND  proforma.id=?
            GROUP BY detalleproforma.idProducto
            HAVING count(*) >0", [$idProducto, $idVenta]);
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
        $dato = DB::table('detalleproforma')->select(
                        'detalleproforma.cantidad', 'detalleproforma.id', 'detalleproforma.idProducto', 'producto.nombre', 'producto.imagen', 'producto.descripcion', 'detalleproforma.totalneto', 'detalleproforma.precio', 'detalleproforma.idProducto', 'detalleproforma.total')
                ->where('detalleproforma.id', $id)
                ->join('producto', 'producto.id', '=', 'detalleproforma.idProducto')
                ->get();
        return response()->json($dato);
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
        $porcentajedescuento;
        $subtotal;
        $descuentos = DB::select("Select tipodescuento.descuento
                      from tipodescuento
                      where tipodescuento.id = ?
                ", [$request->iddescuento]);
        foreach ($descuentos as $key => $value) {
            $porcentajedescuento = $value->descuento;
        }
        $totdetalle = DB::select("Select detalleproforma.total
                      from detalleproforma
                      where detalleproforma.id = ?
                ", [$id]);
        foreach ($totdetalle as $key => $value) {
            $subtotal = $value->total;
        }
        $importe = ($subtotal * $porcentajedescuento) / 100;
        $tottalneto = $subtotal - $importe;

        $actua = DB::table('detalleproforma')
                ->where('id', $id)
                ->update(['idtipodescuento' => $request->iddescuento, 'porcentajedescuento' => $porcentajedescuento, 'importedescuento' => $importe,
            'totalneto' => $tottalneto]);
        return response()->json(["actualizado" => $request->all()]);
    }

    public function actualizarcantidad(Request $request) {
        $venderconstocks = DB::Select("select * from empresa");
        $venderstock = $venderconstocks[0]->venderSinStock;
        $cantidad = $results = DB::select("SELECT detalleproforma.cantidad , detalleproforma.importedescuento, detalleproforma.porcentajedescuento, detalleproforma.idProducto, detalleproforma.idProforma
                from detalleproforma
                WHERE detalleproforma.id = ? ", [$request->id]);
        $importedescuento = $cantidad[0]->importedescuento;
        $porcentajedescuento = $cantidad[0]->porcentajedescuento;
        $idproducto = $cantidad[0]->idProducto;
        $idVenta = $cantidad[0]->idVenta;
        $cant = $request->cantidad;
        $stocks = self::obtenerstock($idproducto, $request->idAlmacen);
        if ($stocks == 0) {
            $stock = 0;
        } else {
            $stock = $stocks[0]->stock;
        }
        if ($stock < $cant && $venderstock == 0 && $cant > 0) {
            $mensaje = "Cantidad No Disponible. Solo hay " . $stock . " unidades";
            return response()->json(["mensaje" => $mensaje]);
        } else {



            $precioventas = self::precioventaproducto($idproducto);
            $precioventa = $precioventas[0]->precioVenta;
            $totalimporte = $precioventa * $cant;
            $importedescuento = ($totalimporte * $porcentajedescuento) / 100;
            $totalneto = ($totalimporte - $importedescuento);
            DB::table('detalleproforma')
                    ->where('id', $request->id)
                    ->update(['cantidad' => $cant,
                        'total' => $totalimporte,
                        'importedescuento' => $importedescuento,
                        'totalneto' => $totalneto]);
        }
        return response()->json(["mensaje" => "Cantidad Actualizada Exitosamente"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id) {
        DB::table('detalleproforma')->where('id', $id)->delete();
        DB::table('chasisProducto')
                ->where('txnVenta', $request->idventa)
                ->where('idProducto', $request->idpro)
                ->update(['txnVenta' => 0, 'checkBox' => 0]);
        return response()->json($request->all());
    }

    public function precioventaproducto($id) {
        $results = DB::select("SELECT producto.precioVenta 
                from producto
                WHERE producto.id=?", [$id]);
        return $results;
    }

    public function productocondetalledeproforma($idproforma) { 
//and detalleventa.idVenta=1 
        $results = DB::select("SELECT
            producto.nombre,
            producto.descripcion,
            producto.color,
            producto.imagen,
            producto.tipoproducto as tipo,
            producto.tamano as talla,
            (select marca.nombre
                from marca
                where producto.idMarca = marca.id) as marca,
            detalleproforma.precio as precioVenta,
            detalleproforma.cantidad,
            detalleproforma.idProforma,
            detalleproforma.idProducto,
            detalleproforma.id,
            detalleproforma.nroFacturaCompra,
            detalleproforma.totalneto,
            detalleproforma.importedescuento,
            detalleproforma.porcentajedescuento,
            (select COUNT(chasisproducto.txnVenta) as cantidad from chasisproducto WHERE detalleproforma.idproforma = chasisproducto.txnVenta AND detalleproforma.idProducto = chasisproducto.idProducto) as cantidadChasis,
            (detalleproforma.precio * detalleproforma.cantidad) as subtotal ,
            (SELECT  (sum((detalleproforma.precio*detalleproforma.cantidad) - detalleproforma.importedescuento ))
                FROM detalleproforma
                INNER JOIN producto  
                INNER JOIN proforma 
                WHERE detalleproforma.idproforma=proforma.id 
                AND detalleproforma.idProducto=producto.id 
                AND detalleproforma.idproforma = ? ) AS total
            FROM detalleproforma
            INNER JOIN producto
            INNER JOIN proforma 
            WHERE detalleproforma.idproforma=proforma.id 
            AND detalleproforma.idProducto=producto.id 
            AND proforma.id=?", [$idproforma, $idproforma]);
        return response()->json($results);
    }


    public function obtenerstock($id, $idalmacen) {
        $si = false;
        $results = DB::select("SELECT v_stockalmacensucursal.stock
            FROM v_stockalmacensucursal
            WHERE v_stockalmacensucursal.idproducto = ? and v_stockalmacensucursal.idalmacen = ?
                ", [$id, $idalmacen]);
        foreach ($results as $key => $value) {
            $si = true;
        }
        if ($si == true) {
            return $results;
        } else {
            return 0;
        }
    }

    public function obteneralmacen($id) {
        $Results = DB::Select("SELECT proforma.idAlmacen WHERE proforma.id = ?", [$id]);
        return $results;
    }

}
