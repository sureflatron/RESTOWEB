<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Venta;
use DB;
use Session;
use Redirect;
use Carbon\Carbon;
use App\Http\Requests;

class AlquilerController extends Controller {

    public function store(Request $request) {
        $venderconstocks = DB::Select("select * from empresa");
        $venderstock = $venderconstocks[0]->venderSinStock;
        $validar = self::validardosveces($request->idProducto, $request->idVenta);
        $almacen = $request->idAlmacen;
        $precioventas = self::precioventaproducto($request->idProducto);
        $precioventa;
        $stocks = self::obtenerstock($request->idProducto, $request->idAlmacen);
        if ($stocks == 0) {
            $stock = 0;
        } else {
            $stock = $stocks[0]->stock;
        }
        if ($request->cantidad <= 0) {
            return response()->json(["mensaje" => "La Cantidad debe ser mayor a 0"]);
        }
        if ($validar == 0) {
            if ($stock < $request->cantidad && $venderstock == 0 && $request->cantidad > 0) {
                $mensaje = "Cantidad No Disponible. Solo hay " . $stock . " unidades";
                return response()->json(["mensaje" => $mensaje]);
            } else {
                $precioventamuevo = $request->precioventa;
                $precioventa = $precioventas[0]->precioVenta;
                if ($precioventamuevo == "null") {
                    $precioventamuevo = $precioventa;
                }
                $totalimporte = $precioventa * $request->cantidad;
                $totalneto = $totalimporte;
                $actua = DB::table('detalleventa')
                        ->where('idVenta', $request->idVenta)
                        ->insert(['idVenta' => $request->idVenta,
                    'idProducto' => $request->idProducto,
                    'cantidad' => $request->cantidad,
                    'precio' => $precioventa,
                    'total' => $totalimporte,
                    'idtipodescuento' => 0,
                    'porcentajedescuento' => 0,
                    'importedescuento' => 0,
                    'totalneto' => $totalneto,
                    'estado' => 2]);
            }
            return response()->json(["mensaje" => "Producto Agregado con Exito al Alquiler"]);
        } else {
            $cantidad = $results = DB::select("SELECT detalleventa.cantidad , detalleventa.importedescuento, detalleventa.porcentajedescuento
                from detalleventa
                WHERE detalleventa.idVenta = ? AND detalleventa.idProducto = ?", [$request->idVenta, $request->idProducto]);
            $cant = $cantidad[0]->cantidad;
            $importedescuento = $cantidad[0]->importedescuento;
            $porcentajedescuento = $cantidad[0]->porcentajedescuento;
            $cant = $cant + $request->cantidad;
            if ($stock < $cant && $venderstock == 0 && $cant > 0) {
                $mensaje = "Cantidad No Disponible. Solo hay " . $stock . " unidades";
                return response()->json(["mensaje" => $mensaje]);
            } else {
                $precioventa = $precioventas[0]->precioVenta;
                $totalimporte = $precioventa * $cant;
                $importedescuento = 0;
                $totalneto = ($totalimporte - $importedescuento);
                DB::table('detalleventa')
                        ->where('idVenta', $request->idVenta)
                        ->where('idProducto', $request->idProducto)
                        ->update(['cantidad' => $cant,
                            'total' => $totalimporte,
                            'importedescuento' => 0,
                            'totalneto' => $totalneto]);
            }
            return response()->json(["mensaje" => "Producto Agregado con Exito al Alquiler"]);
        }
    }

    public function show($id) {
        $dato = DB::select("SELECT SUM(detalleventa.totalneto) as total FROM detalleventa WHERE detalleventa.idVenta = ?", [$id]);
        return response()->json($dato);
    }

    public function validardosveces($idProducto, $idVenta) {
        $datos = 0;
        $results = DB::select("SELECT
                count(detalleventa.idProducto) as repite
            FROM detalleventa
            INNER JOIN venta on venta.id=detalleventa.idVenta 
                AND venta.estado=0
            WHERE detalleventa.idProducto=? 
                AND  venta.id=?
            GROUP BY detalleventa.idProducto
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

    public function precioventaproducto($id) {
        $results = DB::select("SELECT producto.precioVenta 
                from producto
                WHERE producto.id=?", [$id]);
        return $results;
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
        $Results = DB::Select("SELECT venta.idAlmacen WHERE venta.id = ?", [$id]);
        return $results;
    }

    public function listalquilar() {
        $results = DB::select("SELECT 
                    V.ID AS id,
                    v.fecha,
                    v.hora,
                    v.estado,
                    v.`etapa`,
                    v.garantia,
                    IF(v.cobrarCada IS NULL, '',DATE_ADD(v.cobrarCada, INTERVAL 1 WEEK)) as fechaDevolucion,
                    'Administrador' AS nombrepergil,
                    (SELECT empleado.nombre FROM empleado, `puntoventa` WHERE `empleado`.`id` = `puntoventa`.`idEmpleado`
                          AND `puntoventa`.`id` = v.`idPuntoVenta`) AS nombre,
                    v.formaPago,
                    (SELECT cliente.nombre FROM cliente WHERE cliente.`id` = v.idCliente) AS cliente,
                    (SELECT 
                      factura.razonSocial 
                    FROM
                      factura 
                    WHERE factura.idVenta = V.id) AS razon,
                    (SELECT 
                      SUM(
                        detalleventa.cantidad * detalleventa.precio
                      ) 
                    FROM
                      detalleventa
                    WHERE detalleventa.idVenta = v.id) AS total,
                    (
                      (SELECT 
                        SUM(detalleventa.importedescuento) 
                      FROM
                        detalleventa 
                      WHERE detalleventa.idVenta = v.id) + v.importedescuento
                    ) AS importedescuento,
                    (select distinct detalleventa.estado from detalleventa where V.id = detalleventa.idVenta) as devolucion
                  FROM
                    venta V
                  WHERE V.eliminado = 0  AND v.alquiler = 0
                  ORDER BY v.id DESC ");
        return response()->json($results);
    }

    public function obtenerservicios(Request $request) {
        $results = DB::select("
            SELECT producto.id,
                producto.nombre,
                producto.descripcion,
                producto.codigoDeBarra,
                producto.imagen,
                producto.precioVenta as precio,
                (SELECT `v_stockalmacensucursal`.`stock`
                    FROM `v_stockalmacensucursal` 
                    WHERE v_stockalmacensucursal.`idproducto` = producto.id
                        AND `v_stockalmacensucursal`.`idalmacen` = ?) AS stock
            FROM producto 
            WHERE producto.eliminado = 0 
                AND producto.tipoproducto = 'servicio'", [$request->almacen]);
        return response()->json($results);
    }

}
