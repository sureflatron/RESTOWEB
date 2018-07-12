<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Venta;
use DB;
use Session;
use Redirect;
use Carbon\Carbon;
use App\Http\Requests;

class VentaController extends Controller {

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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
//
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
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response generarventas
     */
    public function destroy($id) {
        DB::table('venta')->where('id', $id)->update(['eliminado' => 1]);
        return response()->json($id);
    }

    public function anularfacturaventa(Request $request) {
        $idfactura;
        $idVenta;
        $datos = DB::select("SELECT factura.id,factura.idVenta FROM factura WHERE factura.id=?", [$request->idfactura]);
        foreach ($datos as $key => $value) {
            $idfactura = $value->id;
            $idVenta = $value->idVenta;
        }
        $actua = DB::table('venta')->where('id', $idVenta)->update(['estado' => 2, 'Motivo' => $request->motivo]);
        $actua = DB::table('factura')->where('id', $idfactura)->update(['eliminado' => 1]);
        return response()->json($datos);
    }

    public function listarfacturas(Request $request) {
        $datos = DB::select("SELECT 
                factura.nroFactura,
                factura.id,
                factura.idVenta,
                factura.fecha,
                factura.razonSocial,
                factura.NIT,
                factura.total,
                empleado.nombre
              FROM
                factura 
                INNER JOIN puntoventa 
                  ON puntoventa.id = factura.idPuntoVenta
                LEFT JOIN empleado 
                  ON empleado.id = puntoventa.idEmpleado
              WHERE factura.eliminado = 0 and factura.fecha = ?
              ORDER BY factura.id DESC", [ $request->fecha]);
        return response()->json($datos);
    }

    public function generarventas($id) {
        $date = Carbon::now();
        $fecha = $date->toDateString();
        $hora = $date->toTimeString();
        $idpunto = $id;
        $idalmacen = self::obteneralmacen($idpunto);
        $insertado = DB::table('venta')->insert(['fecha' => $fecha,
            'hora' => $hora,
            'idPuntoVenta' => $idpunto,
            'idCliente' => NULL,
            'formaPago' => 'Efectivo',
            'estado' => 0,
            'idAlmacen' => $idalmacen,
            'etapa' => 'venta',
            'alquiler' => 1]);
        $ultimo = DB::table('venta')->select('id')->orderby('id', 'DESC')->take(1)->get('id');
        return response()->json($ultimo);
    }

    public function generaralquiler($id) {
        $date = Carbon::now();
        $fecha = $date->toDateString();
        $hora = $date->toTimeString();
        $idpunto = $id;
        $idalmacen = self::obteneralmacen($idpunto);
        $insertado = DB::table('venta')->insert(['fecha' => $fecha,
            'hora' => $hora,
            'idPuntoVenta' => $idpunto,
            'idCliente' => NULL,
            'formaPago' => 'Efectivo',
            'estado' => 0,
            'idAlmacen' => $idalmacen,
            'etapa' => 'venta',
            'alquiler' => 0]);
        $ultimo = DB::table('venta')->select('id')->orderby('id', 'DESC')->take(1)->get('id');
        return response()->json($ultimo);
    }

    public function AnularVentas($id) {
        $estado = DB::table('venta')->select('id', 'estado')->where('id', $id)->get();
        $estados = $estado[0]->estado;
        if ($estados == 1) {
            return response()->json(["mensaje" => "Venta ya cobrada no se puede anular"]);
        }if ($estados == 0 || $estados == 4) {
            $actual = DB::table('venta')->where('id', $id)->update(['estado' => 2]);
            return response()->json(["mensaje" => "Venta anulada exitosamente"]);
        }if ($estados == 2) {
            return response()->json(["mensaje" => "Esta venta ya fue anulada no se puede volver a nular"]);
        }
    }

    public function listarventa(Request $request) {
        $empleado = $request->idempleado;
        $otro = DB::select("SELECT
                            perfil.nombre
                          FROM
                            empleado,
                            puntoventa,
                            perfil,
                            usuario
                          WHERE
                            empleado.id = puntoventa.idEmpleado AND
                            empleado.id = usuario.idEmpleado AND
                            usuario.idPerfil = perfil.id AND
                            perfil.nombre = 'Administrador' and
                            empleado.id = $empleado");
        $perf = "";
        foreach ($otro as $otros) {
            $perf = $otros->nombre;
        }
        if ($perf == "Administrador") {
            $results = DB::select("SELECT 
                    V.ID AS id,
                    v.fecha,
                    v.hora,
                    v.estado,
                    v.`etapa`,
                    v.cancelado,
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

                                             (IFNULL((SELECT 
                                              SUM(pago.pagado) AS cuotasPagadas 
                                            FROM
                                              (SELECT 
                                                SUM(cobrocuota.`importe`) AS pagado,
                                                cuentacobrar.`id`,
                                                cuentacobrar.`idVenta` 
                                              FROM
                                                cobrocuota,
                                                cuentacobrar 
                                              WHERE cobrocuota.`idCuentaCobrar` = cuentacobrar.`id` and cobrocuota.eliminado = 0
                                              GROUP BY cuentacobrar.`id`) AS pago 
                                            WHERE pago.idVenta = v.`id`),0)  + v.`aCuenta`)AS saldo
                  FROM
                    venta V
                  WHERE V.eliminado = 0 AND v.fecha = ? AND v.alquiler = 1
                  ORDER BY v.id DESC 
                  LIMIT 500", [$request->fecha]);
            return response()->json($results);
        } else {
            $resultss = DB::select("SELECT venta.id,
                venta.fecha,
                venta.cancelado,
                usuario.idPerfil as perfil,
                venta.hora,
                cliente.nombre AS cliente,
                'Otro' as nombrepergil,
                (SELECT factura.razonSocial 
                    FROM factura 
                    WHERE factura.idVenta = venta.id) AS razon,
                empleado.nombre,
                venta.estado,
                venta.etapa,
                (SELECT SUM(detalleventa.cantidad*detalleventa.precio) 
                        FROM detalleventa 
                        INNER JOIN producto
                        WHERE producto.id=detalleventa.idProducto 
                        AND detalleventa.idVenta=venta.id) AS total,
                ((SELECT SUM(detalleventa.importedescuento) 
                        FROM detalleventa 
                        WHERE  detalleventa.idVenta=venta.id)+ venta.importedescuento) as importedescuento ,
                venta.formaPago,
                    (IFNULL((SELECT 
                     SUM(pago.pagado) AS cuotasPagadas 
                   FROM
                     (SELECT 
                       SUM(cobrocuota.`importe`) AS pagado,
                       cuentacobrar.`id`,
                       cuentacobrar.`idVenta` 
                     FROM
                       cobrocuota,
                       cuentacobrar 
                     WHERE cobrocuota.`idCuentaCobrar` = cuentacobrar.`id` and cobrocuota.eliminado = 0
                     GROUP BY cuentacobrar.`id`) AS pago 
                   WHERE pago.idVenta = venta.`id`),0)  + venta.`aCuenta`)AS saldo
                FROM usuario,venta 
                INNER JOIN puntoventa 
                INNER JOIN empleado
                LEFT JOIN cliente ON  cliente.id = venta.idCliente
                WHERE venta.idPuntoVenta=puntoventa.id 
                           AND puntoventa.idEmpleado=empleado.id
                           and empleado.id = usuario.idEmpleado
                           AND empleado.id=?
                           AND puntoventa.id=?  
                           AND venta.eliminado=0
                           and venta.garantia = 0
                           and venta.fecha = ?
                           AND venta.alquiler = 1
                order by venta.id DESC  LIMIT 100", [$request->idempleado, $request->iddelpuntoventa, $request->fecha]);
            return response()->json($resultss);
        }
    }

    public function listarventaadmin() {
        $results = DB::select("SELECT venta.id,
            venta.fecha,
            empleado.nombre,
            venta.estado,
            (SELECT sum(detalleventa.cantidad*producto.precioVenta)
                FROM detalleventa
                INNER JOIN producto 
                WHERE producto.id=detalleventa.idProducto
                AND detalleventa.idVenta=venta.id) AS total
        FROM venta 
        INNER JOIN puntoventa 
        INNER JOIN empleado 
        WHERE venta.idPuntoVenta=puntoventa.id 
            AND puntoventa.idEmpleado=empleado.id  
            AND venta.eliminado=0
        ");
        return response()->json($results);
    }

    public function generarventadesdemesa(Request $request) {
        $date = Carbon::now();
        $fecha = $date->toDateString();
        $hora = $date->toTimeString();
        $idpunto = $request->iddelpuntoventa;
        $idmesa = $request->idmesa;
        $idalmacen;
        $idalmacen = self::obteneralmacen($idpunto);
        $insertado = DB::table('venta')->insert(['fecha' => $fecha,
            'hora' => $hora,
            'idPuntoVenta' => $request->iddelpuntoventa,
            'idCliente' => NULL,
            'formaPago' => 'Efectivo',
            'estado' => 0,
            'idMesa' => $request->idmesas,
            'etapa' => 'venta',
            'idAlmacen' => $idalmacen]);
        $ultimo = DB::table('venta')->select('id')->orderby('id', 'DESC')->take(1)->get('id');
        return response()->json($ultimo);
//return response()->json($ultimo);
    }

    public function obteneralmacen($idpuntoventa) {
        $results = DB::select("CALL obteneralmacen(?);", [$idpuntoventa]);
        $idalmacent;
        foreach ($results as $key => $value) {
            $idalmacent = $value->idAlmacen;
        }
        return $idalmacent;
    }

    public function Validarventaantigua($idventa) {
        $estados;
        $results = DB::select("SELECT venta.estado FROM venta WHERE venta.id= ?", [$idventa]);
        foreach ($results as $key => $value) {
            $estados = $value->estado;
        }
        return response()->json($estados);
    }

    public function validarestado($id) {
        $estado = DB::table('venta')->select('id', 'estado')->where('id', $id)->get();
        return response()->json($estado);
    }

    public function facturaempresa($idpuntoventa) {
        $factura = '';
        $estado = DB::table('empresa')->select('empresa.factura')
                        ->join('sucursal', 'sucursal.idEmpresa', '=', 'empresa.id')
                        ->join('puntoventa', 'puntoventa.idSucursal', '=', 'sucursal.id')
                        ->where('puntoventa.id', $idpuntoventa)->get();
        foreach ($estado as $key => $value) {
            $factura = $value->factura;
        }
        return response()->json($factura);
    }

    public function buscarProducto($parametro, $almacen) {
        
        $datostablacalculada=DB::select("
        SELECT IFNULL(COUNT(id),0) AS hayproductosbuscar
        FROM productominmaxalmacen
        WHERE idAlmacen=?  AND anio=YEAR(NOW())     
        ",[$almacen]);
         $hayonohay=$datostablacalculada[0]->hayproductosbuscar;
         if($hayonohay==0)
         {
              $producto = DB::select("CALL buscarproducto(?,?)", [$parametro, $almacen]);
         }
         else
         {
               $producto = DB::select("CALL buscarProductomaxmin(?,?)", [$parametro, $almacen]);
         }
       
        return $producto;
    }

    public function editarfactura($id) {
        $estado = DB::select("CALL editarfactura(?);", [$id]);
        return response()->json($estado);
    }

    public function editarfacturaventa(Request $request) {
        $actua = DB::table('factura')->where('id', $request->idfactura)->update([
            'NIT' => $request->nit,
            'razonSocial' => $request->razonsocial]);
        return response()->json($actua);
    }

    public function comanda($id) {
        $usuario;
        $fecha;
        $hora;
        $idventa;
        $results = DB::select("call comanda (?)", [$id]);
        foreach ($results as $key => $value) {
            $usuario = $value->empleado;
            $fecha = $value->fecha;
            $hora = $value->hora;
            $idventa = $value->idVenta;
        }
        return view('Reporte.comanda', compact(['usuario', 'fecha', 'hora', 'results', 'idventa']));
    }

    public function imprircomandaventa($id) {
        $usuario;
        $fecha;
        $hora;
        $idventa;
        $nomempresa;
        $correo;
        $web;
        $sucursal;
        $imagen;
        $results = DB::select("call comandaventa (?)", [$id]);
        foreach ($results as $key => $value) {
            $usuario = $value->empleado;
            $fecha = $value->fecha;
            $hora = $value->hora;
            $idventa = $value->idVenta;
            $correo = $value->correo;
            $web = $value->web;
            $nomempresa = $value->nombre;
            $sucursal = $value->sucursal;
            $imagen = $value->imagen;
        }
        $pdf = \PDF::loadView('Reporte.ComandaVenta', compact(['usuario', 'fecha', 'hora', 'results', 'idventa', 'web', 'correo', 'nomempresa', 'sucursal', 'imagen']));
        return $pdf->stream();
//return view ('Reporte.comanda',compact(['usuario','fecha','hora','results','idventa']));
    }

    public function imprimirproforma($id)
        {
        $fecha;
        $idventa;
        $cliente;
        $nit;
        $correo;
        $celular;
        $telefono;
        $imagen;
        $total;
        $descripcion;
        $direccion;
        $ciudad;
        $pais;
        $telefonoEmpresa;
        $descuentocliente;
        $descuento;
        $results = DB::select("call proforma (?)", [$id]);
        foreach ($results as $key => $value) {
            $fecha = $value->fecha;
            $idventa = $value->idVenta;
            $cliente = $value->cliente;
            $nit = $value->NIT;
            $correo = $value->correo;
            $celular = $value->celular;
            $telefono = $value->telefonoFijo;
            $descuento = $value->importedescuento;
            $total = $value->total;
            $imagen = $value->imagen;
            $descripcion = $value->descripcion;
            $direccion = $value->direccion;
            $pais = $value->pais;
            $ciudad = $value->ciudad;
            $telefonoEmpresa = $value->telefono;
           $descuentocliente=$value->descuentocliente;
           $descuentoclienteliteral=$value->descuentoclienteliteral;
        }
        $pdf = \PDF::loadView('Reporte.proforma', compact(['telefonoEmpresa', 
            'direccion', 'pais', 'ciudad', 'fecha', 'descripcion', 'results', 'idventa', 'cliente', 
            'nit', 'correo', 'celular', 'telefono', 'imagen', 'total','descuento','descuentocliente',
            'descuentoclienteliteral']));
        return $pdf->stream();
//return view ('Reporte.comanda',compact(['usuario','fecha','hora','results','idventa']));
    }

    public function generarventasres($id) {
        $date = Carbon::now();
        $fecha = $date->toDateString();
        $hora = $date->toTimeString();
        $idpunto = $id;
        $idalmacen = self::obteneralmacen($idpunto);
        $insertado = DB::table('venta')
                ->insert(['fecha' => $fecha,
            'hora' => $hora,
            'idPuntoVenta' => $idpunto,
            'idCliente' => NULL,
            'formaPago' => 'Efectivo',
            'estado' => 0,
            'idAlmacen' => $idalmacen]);

        $ultimo = DB::table('venta')->select('id')->orderby('id', 'DESC')->take(1)->get('id');

        return response()->json($ultimo);
    }

    public function conenvioventa(Request $request) {
        $actua = DB::table('venta')->where('id', $request->idventa)
                ->update(['estado' => $request->estado,
            'horaentrega' => $request->HoraEntrega,
            'fechaentrega' => $request->FechaEntrega,
            'entregadomicilio' => $request->EnvioaDomicilio,
            'cobroalentregar' => $request->Cobroalentregar,
            'direccionenvio' => $request->DireccionEnvio,
            'importetransporte' => $request->Importetransporte,
            'personaentrega' => $request->PersonaRecibeenvio,
            'etapa' => 'venta'
        ]);
        return response()->json($request->all());
    }

    public function Ventaprogramda($id) {
        $estado = DB::select("CALL listarprogramacion(?);", [$id]);
        return response()->json($estado);
    }

    public function proforma($id, $idcliente, $total,$descuento,$importe,$tipo) {
        $estado = DB::table('venta')->select('id', 'estado')->where('id', $id)->get();
        $estados;
        foreach ($estado as $key => $value) {
            $estados = $value->estado;
        }
        $actual;
        if ($estados == 1) {
            return 0;
        }if ($estados == 0)
         {
            $actua = DB::table('venta')->where('id', $id)->update([
                'estado' => 0,
                'idCliente' => $idcliente,
                'total' => $total,
                'descuentocliente' => $descuento,
                'importedescuento'=>$importe,
                'idTipoDescuento'=>$tipo,
                'etapa' => 'proforma']);
            return response()->json($actua);
        }if ($estados == 2) 
        {
            return 2;
        }
    }

    public function listarcreditos(Request $request) {
        $date = Carbon::now();
        $fecha = $date->toDateString();
        $results = DB::select("SELECT venta.id,
        venta.fecha,
        venta.hora,
        IF(venta.observaciones IS NULL, '', venta.observaciones) AS observaciones,
        venta.cobrarCada,
        venta.cancelado,
        cliente.nombre AS nombrecli,
        IF((SELECT factura.razonSocial FROM factura WHERE factura.idVenta = venta.id) IS NULL, '', (SELECT factura.razonSocial FROM factura WHERE factura.idVenta = venta.id)) AS razon,
        empleado.nombre,
        (SELECT SUM(pago.pagado) AS cuotasPagadas
		FROM (SELECT SUM(cobrocuota.`importe`) AS pagado, cuentacobrar.`id`, cuentacobrar.`idVenta`
			FROM cobrocuota, cuentacobrar
			WHERE cobrocuota.`idCuentaCobrar` = cuentacobrar.`id` and cobrocuota.eliminado = 0
			GROUP BY cuentacobrar.`id`) AS pago
		WHERE pago.idVenta = venta.`id`
		) AS pgado,
        ROUND((IF((SELECT SUM(pago.pagado) AS cuotasPagadas
		FROM (SELECT SUM(cobrocuota.`importe`) AS pagado, cuentacobrar.`id`, cuentacobrar.`idVenta`
			FROM cobrocuota, cuentacobrar
			WHERE cobrocuota.`idCuentaCobrar` = cuentacobrar.`id` and cobrocuota.eliminado = 0
			GROUP BY cuentacobrar.`id`) AS pago
		WHERE pago.idVenta = venta.`id`
		)IS NULL,0,(SELECT SUM(pago.pagado) AS cuotasPagadas
		FROM (SELECT SUM(cobrocuota.`importe`) AS pagado, cuentacobrar.`id`, cuentacobrar.`idVenta`
			FROM cobrocuota, cuentacobrar
			WHERE cobrocuota.`idCuentaCobrar` = cuentacobrar.`id` and cobrocuota.eliminado = 0
			GROUP BY cuentacobrar.`id`) AS pago
		WHERE pago.idVenta = venta.`id`
		)) + venta.aCuenta),2) AS cobrado,
        ABS(ROUND(((venta.total - venta.importedescuento) - (IF((SELECT SUM(pago.pagado) AS cuotasPagadas
		FROM (SELECT SUM(cobrocuota.`importe`) AS pagado, cuentacobrar.`id`, cuentacobrar.`idVenta`
			FROM cobrocuota, cuentacobrar
			WHERE cobrocuota.`idCuentaCobrar` = cuentacobrar.`id` and cobrocuota.eliminado = 0
			GROUP BY cuentacobrar.`id`) AS pago
		WHERE pago.idVenta = venta.`id`
		)IS NULL,0,(SELECT SUM(pago.pagado) AS cuotasPagadas
		FROM (SELECT SUM(cobrocuota.`importe`) AS pagado, cuentacobrar.`id`, cuentacobrar.`idVenta`
			FROM cobrocuota, cuentacobrar
			WHERE cobrocuota.`idCuentaCobrar` = cuentacobrar.`id` and cobrocuota.eliminado = 0
			GROUP BY cuentacobrar.`id`) AS pago
		WHERE pago.idVenta = venta.`id`
		)) + venta.aCuenta)) ,2))AS saldo,        
        venta.total,
        venta.importedescuento,
        (venta.total - venta.importedescuento) as totalneto, 
        venta.saldoACobrar,
        venta.aCuenta
        FROM venta 
        INNER JOIN puntoventa 
        INNER JOIN empleado
        LEFT JOIN cliente ON  cliente.id =venta.idCliente
        WHERE venta.idPuntoVenta=puntoventa.id 
                   AND puntoventa.idEmpleado=empleado.id 
                   AND venta.eliminado=0
                   AND venta.etapa = 'credito'
                   AND venta.estado != 2
        ORDER BY id DESC");
        return response()->json($results);
    }

    public function proformas(Request $request) {
        $results = DB::select("SELECT venta.id,
        venta.fecha,
        venta.hora,
        cliente.nombre AS nombrecli,
        (SELECT factura.razonSocial 
            FROM factura 
            WHERE factura.idVenta = venta.id) AS razon,
        empleado.nombre,
        venta.estado,
        (SELECT SUM(detalleventa.cantidad*detalleventa.precio) 
                FROM detalleventa 
                INNER JOIN producto
                WHERE producto.id=detalleventa.idProducto 
                AND detalleventa.idVenta=venta.id) AS total,
        venta.importedescuento,
        venta.formaPago
        FROM venta 
        INNER JOIN puntoventa 
        INNER JOIN empleado
        LEFT JOIN cliente ON  cliente.id = venta.idCliente
        WHERE venta.idPuntoVenta=puntoventa.id 
                   AND puntoventa.idEmpleado=empleado.id
                   AND empleado.id=?
                   AND puntoventa.id=? 
                   AND venta.eliminado=0
                   AND venta.estado = 4
        ORDER BY id, fecha, hora", [$request->idempleado, $request->iddelpuntoventa]);
        return response()->json($results);
    }

    public function listarCuotas($id) {
        $results = DB::select("SELECT cuentacobrar.`fechaVencimiento`,
		ROUND(cuentacobrar.`importe`,2) AS importe,
		ABS(ROUND(IF((SELECT SUM(cobrocuota.`importe`)
			FROM cobrocuota
			WHERE cobrocuota.`idCuentaCobrar` = cuentacobrar.`id` AND cobrocuota.eliminado = 0) IS NULL, 0,
                        (SELECT SUM(cobrocuota.`importe`)
			FROM cobrocuota
			WHERE cobrocuota.`idCuentaCobrar` = cuentacobrar.`id` AND cobrocuota.eliminado = 0)),2)) AS cobrado,
                ABS(((cuentacobrar.`importe`) - (IF((SELECT SUM(cobrocuota.`importe`)
			FROM cobrocuota
			WHERE cobrocuota.`idCuentaCobrar` = cuentacobrar.`id` AND cobrocuota.eliminado = 0) IS NULL, 0,
                        (SELECT SUM(cobrocuota.`importe`)
			FROM cobrocuota
			WHERE cobrocuota.`idCuentaCobrar` = cuentacobrar.`id` AND cobrocuota.eliminado = 0))))) AS saldo,
		cuentacobrar.`id`
	FROM cuentacobrar
	WHERE cuentacobrar.`idVenta` = ?", [$id]);
        return response()->json($results);
    }

    public function listarCuotasPagadas($id) {
        $results = DB::select("SELECT cobrocuota.`fecha`, ROUND(cobrocuota.`importe`,2) AS importe,cobrocuota.`id`, cobrocuota.formaPago
                                FROM cobrocuota
                                WHERE cobrocuota.`idCuentaCobrar` = ? and cobrocuota.eliminado = 0", [$id]);
        return response()->json($results);
    }

    public function imprimirnotaventa($id)
      {
        $fecha;
        $idventa;
        $cliente;
        $nit;
        $correo;
        $celular;
        $telefono;
        $imagen;
        $total;
        $descripcion;
        $sucursal;
        $aCuenta;
        $totalVenta;
        $descuento;
        $hora;
        $saldo;
        $nombre;
        $propietario;
        $pais;
        $ciudad;
        $telefonosuc;
        $direccion;
        $formapago;
        $codcliente;
        $cobrarCada;
        $empleado;
        $ciudadcliente;
        $observaciones;
        $garantia;
        $etapa;

        $estadoVenta = "";

        $personaentrega;
        $direccionenvio;
        $importetransporte;
        $fechaentrega;
        $horaentrega;
        $cuidadEnvio;
        $telefonopedido;        
        $ci;
        $descuentocliente;
        $descuentoclienteliteral;
        $descuentomasivoliteral;
        $results = DB::select("call proforma (?)", [$id]);
        $cuotas = DB::select("
            SELECT cuentacobrar.`fechaVencimiento`, `cuentacobrar`.`importe`
            FROM cuentacobrar
            WHERE `cuentacobrar`.`idVenta` = ?", [$id]);
        foreach ($results as $key => $value) {
            $fecha = $value->fecha;
            $idventa = $value->idVenta;
            $cliente = $value->cliente;
            $nit = $value->NIT;
            $correo = $value->correo;
            $celular = $value->celular;
            $telefono = $value->telefonoFijo;
            $total = $value->total;
            $imagen = $value->imagen;
            $descripcion = $value->descripcion;
            $sucursal = $value->sucursal;
            $aCuenta = $value->aCuenta;
            $hora = $value->hora;
            $totalVenta = $value->totalNeto;
            $saldo = $value->saldoACobrar;
            $descuento = $value->importedescuento;
            $nombre = $value->nombre;
            $propietario = $value->propietario;
            $pais = $value->pais;
            $ciudad = $value->ciudad;
            $telefonosuc = $value->telefono;
            $direccion = $value->direccion;
            $formapago = $value->formaPago;
            $codcliente = $value->codcliente;
            $cobrarCada = $value->cobrarCada;
            $empleado = $value->empleado;
            $ciudadcliente = $value->ciudadcliente;
            $observaciones = $value->observaciones;
            $garantia = $value->garantia;
            $etapa = $value->etapa;

            $estadoVenta = $value->estadoVenta;

            $personaentrega = $value->personaentrega;
            $direccionenvio = $value->direccionenvio;
            $importetransporte = $value->importetransporte;
            $fechaentrega = $value->fechaentrega;
            $horaentrega = $value->horaentrega;
            $cuidadEnvio = $value->ciudadEnvio;
            $telefonopedido = $value->telefono;
            $ci = $value->ci;
            $descuentocliente=$value->descuentocliente;
            $descuentoclienteliteral=$value->descuentoclienteliteral;
            $descuentomasivoliteral=$value->descuentomasivoliteral;
        }
        
        if ($estadoVenta == 2) {
            //escon envio
//        return view('Reporte.notaventaEnvio', compact(['descuento', 'saldo', 'totalVenta', 'hora', 'aCuenta', 'sucursal', 'fecha', 'descripcion', 'results', 'idventa', 'cliente', 'nit', 'correo', 'celular', 'telefono', 'imagen', 'total','personaentrega','direccionenvio','importetransporte','fechaentrega','horaentrega','estadoVenta']));
 $pdf = \PDF::loadView('Reporte.notaventaEnvio',
  compact(['etapa', 'cuotas', 'ciudadcliente', 'observaciones', 'empleado', 'cobrarCada', 'cuidadEnvio', 'garantia',
    'codcliente', 'formapago', 'direccion', 'telefonosuc', 'ciudad', 'pais', 'propietario', 'nombre', 'descuento',
    'saldo', 'totalVenta', 'hora', 'aCuenta', 'sucursal', 'fecha', 'descripcion', 'results', 'idventa', 'cliente',
    'nit', 'correo', 'celular', 'telefono', 'imagen', 'total', 'personaentrega', 'direccionenvio',
    'importetransporte','fechaentrega', 'horaentrega', 'telefonopedido', 'ci', 'estadoVenta','descuentocliente','descuentoclienteliteral',
    'descuentomasivoliteral']));
      return $pdf->setPaper('A4')->setOrientation('portrait')->stream();
        } else {
            //sin envio
//        return view('Reporte.notaventa', compact(['cuotas', 'garantia', 'observaciones', 'ciudadcliente', 'empleado', 'cobrarCada', 'codcliente', 'formapago', 'direccion', 'telefonosuc', 'ciudad', 'pais', 'propietario', 'nombre', 'descuento', 'saldo', 'totalVenta', 'hora', 'aCuenta', 'sucursal', 'fecha', 'descripcion', 'results', 'idventa', 'cliente', 'nit', 'correo', 'celular', 'telefono', 'imagen', 'total']));
            $pdf = \PDF::loadView('Reporte.notaventa',
  compact(['etapa', 'cuotas', 'garantia', 'observaciones', 'ciudadcliente', 'empleado', 'cobrarCada', 
   'codcliente', 'formapago', 'direccion', 'telefonosuc', 'ciudad', 'pais', 'propietario', 'nombre', 'descuento',
   'saldo', 'totalVenta', 'hora', 'aCuenta', 'sucursal', 'fecha', 'descripcion', 'results', 'idventa', 'cliente', 
   'nit', 'correo', 'celular', 'telefono', 'imagen', 'total', 'estadoVenta','descuentocliente','descuentoclienteliteral',
   'descuentomasivoliteral']));
   return $pdf->setPaper('A4')->setOrientation('portrait')->stream();
        }
    }

    public function imprimirrecibo($id) {
        $fecha;
        $idventa;
        $cliente;
        $nit;
        $correo;
        $celular;
        $telefono;
        $imagen;
        $total;
        $descripcion;
        $sucursal;
        $aCuenta;
        $totalVenta;
        $descuento;
        $hora;
        $saldo;
        $pagado;
        $cancelado;
        $credito;
        $tot;
        $glosa;
        $empresa;
        $propietario;
        $direccion;
        $telf;
        $ciudad;
        $pais;
        $idcuota;
        $totalcancelado;
        $ultimo = DB::table('cobrocuota')->select('id')->orderby('id', 'DESC')->take(1)->get('id');
        foreach ($ultimo as $ult) {
            $idcuota = $ult->id;
        }
        $results = DB::select("call recibo (?,?)", [$id, $idcuota]);
        $fechacuota;
        $venc;
        $pago;
        foreach ($results as $key => $value) {
            $fecha = $value->fechacuota;
            $idventa = $value->idVenta;
            $cliente = $value->cliente;
            $nit = $value->NIT;
            $totalcancelado = $value->totalcancelado;
            $correo = $value->correo;
            $celular = $value->celular;
            $telefono = $value->telefonoFijo;
            $total = $value->total;
            $imagen = $value->imagen;
            $cancelado = $value->pgado;
            $descripcion = $value->descripcion;
            $sucursal = $value->sucursal;
            $aCuenta = $value->aCuenta;
            $hora = $value->hora;
            $totalVenta = $value->totalNeto;
            $saldo = $value->totalcredito;
            $descuento = $value->importedescuento;
            $pagado = $value->totalcuota;
            $credito = $value->totalcredito;
            $tot = $value->saldo;
            $glosa = $value->glosa;
            $empresa = $value->nombre;
            $propietario = $value->propietario;
            $direccion = $value->direc;
            $telf = $value->tel;
            $ciudad = $value->ciudad;
            $pais = $value->pais;
            $fechacuota = $value->fechacuotacredito;
            $venc = $value->fechaVencimiento;
            $pago = $value->pago;
        }
        return view('Reporte.recibo', compact(['pago', 'venc', 'fechacuota', 'totalcancelado', 'idcuota', 'cancelado', 'ciudad', 'pais', 'telf', 'direccion', 'propietario', 'empresa', 'glosa', 'tot', 'pagado', 'credito', 'descuento', 'saldo', 'totalVenta', 'hora', 'aCuenta', 'sucursal', 'fecha', 'descripcion', 'results', 'idventa', 'cliente', 'nit', 'correo', 'celular', 'telefono', 'imagen', 'total']));
    }

    public function ventasXempleados() {
        $results = DB::select("
                    SELECT
                    COUNT(empleado.nombre) AS total,
                    empleado.nombre 
                 FROM venta 
                 INNER JOIN puntoventa 
                 INNER JOIN empleado 
                 WHERE venta.idPuntoVenta = puntoventa.id 
                    AND puntoventa.idEmpleado = empleado.id 
                    AND venta.eliminado = 0
                    AND venta.estado = 1
                 GROUP BY empleado.nombre
                 ORDER BY total desc
                 ");
        return response()->json($results);
    }

    
    public function productosXtipo() {
        $results = DB::select("SELECT
                (SELECT tipoproducto.nombre
                    FROM tipoproducto
                    WHERE tipoproducto.id = producto.idTipoProducto) as categoria,
                COUNT((SELECT tipoproducto.nombre
                            FROM tipoproducto
                            WHERE tipoproducto.id = producto.idTipoProducto) ) as total
            FROM producto
            WHERE producto.eliminado = 0
            GROUP BY categoria
            ORDER BY total desc
            ");
        return response()->json($results);
    }

    public function ventasXdiaXsemana() {
        $results = DB::select("SELECT 
                venta.fecha,
                COUNT(venta.fecha) AS total,
                SUM(
                  venta.total - venta.importedescuento
                ) AS totalMoney 
              FROM
                venta 
              WHERE fecha BETWEEN DATE_ADD(NOW(), INTERVAL - 7 DAY) 
                AND NOW() 
                AND venta.estado = 1  OR venta.`estado` = 5
                AND venta.eliminado = 0 
              GROUP BY venta.fecha 
              ORDER BY venta.fecha DESC ");
        return response()->json($results);
    }

    public function ventasXmes() {
        $results = DB::select("SELECT 
                COUNT(*) AS contador,
                YEAR(fecha) AS anno,
                MONTH(fecha) AS mes,
                SUM(total) AS total 
              FROM
                venta 
              WHERE venta.estado = 1 
                AND venta.eliminado = 0 
                OR venta.estado = 5
              GROUP BY YEAR(fecha),
                MONTH(fecha) 
              ORDER BY anno asc, mes asc ");
        return response()->json($results);
    }

    public function ventasformapago() {
        $results = DB::select("SELECT
            COUNT(*) AS total,
            venta.formaPago
            FROM venta
            WHERE venta.eliminado = 0 
            GROUP BY venta.formaPago
            ORDER BY total desc");
        return response()->json($results);
    }

    public function topproductos() {
        $results = DB::select("SELECT 
                producto.nombre AS Producto,
                producto.codigoDeBarra,
                producto.color,
                producto.tamano,
                SUM((detalleventa.Cantidad)) AS Cantidadvendida,
                SUM((detalleventa.totalneto)) AS ImporteTotal
              FROM
                detalleventa 
                INNER JOIN producto 
                  ON detalleventa.idproducto = producto.id 
                INNER JOIN venta 
                  ON venta.id = detalleventa.idVenta  
              WHERE venta.estado = 1
              GROUP BY producto.id 
              ORDER BY Cantidadvendida DESC 
              LIMIT 10 ");
        return response()->json($results);
    }

    public function topclientesfacturados() {
        $results = DB::select("SELECT
                        COUNT(*) as cantidad,
                        factura.razonSocial,
                        factura.nit,
                        SUM(factura.total) as total
                FROM factura
                WHERE factura.NIT != 0
                        AND factura.eliminado  = 0
                GROUP BY factura.nit 
                ORDER BY total DESC 
                LIMIT 5");
        return response()->json($results);
    }

    public function topclientesventas() {
        $results = DB::select("SELECT
                    COUNT(*) AS cantidad,
                    (SELECT cliente.nombre
                    FROM cliente WHERE cliente.id = venta.idCliente  ) as cliente,
                (SELECT cliente.NIT
                    FROM cliente WHERE cliente.id = venta.idCliente ) as NIT,
                SUM(venta.total - venta.importedescuento) as totalventa
            FROM venta
            WHERE venta.eliminado = 0
            AND venta.estado = 1
            AND venta.idCliente IS NOT NULL
            GROUP by cliente, NIT
            ORDER BY totalventa DESC
            LIMIT 6");
        return response()->json($results);
    }

    public function nitrazoncliente($id) {
        $results = DB::select("
               SELECT cliente.razonSocial, cliente.nit, cliente.nombre ,cliente.descuentoporcliente as descuentocliente
                FROM cliente
                WHERE cliente.eliminado = 0 AND cliente.id = ?", [$id]);
        return response()->json($results);
    }

    public function listaralquiler() {
        $results = DB::select("SELECT
            producto.`codigoDeBarra`,
            producto.`imagen`,
            producto.nombre,
            producto.descripcion,
            producto.color,
            producto.tipoproducto AS tipo,
            producto.tamano AS talla,
            (SELECT
              marca.nombre
            FROM
              marca
            WHERE producto.idMarca = marca.id) AS marca,
            detalleventa.precio AS precioVenta,
            detalleventa.cantidad,
            detalleventa.idVenta,
            detalleventa.idProducto,
            DATE_ADD(venta.`cobrarCada`,INTERVAL 6 DAY) AS fechadeentrega,
            venta.garantia,
            (SELECT
              cliente.`nombre`
            FROM
              cliente
            WHERE cliente.`id` = venta.`idCliente`) AS cliente,
            (SELECT
              cliente.`nit`
            FROM
              cliente
            WHERE cliente.`id` = venta.`idCliente`) AS nit
          FROM
            detalleventa
            INNER JOIN producto
              ON producto.`tipoproducto` = 'servicio'
            INNER JOIN venta
          WHERE detalleventa.idVenta = venta.id
            AND detalleventa.idProducto = producto.id
            AND garantia > 0");
        return response()->json($results);
    }

    public function obteneralquiler($id) {
        $results = DB::select("SELECT
                producto.`codigoDeBarra`,
                producto.`imagen`,
                producto.nombre,
                producto.descripcion,
                producto.color,
                producto.tipoproducto AS tipo,
                producto.tamano AS talla,
                (SELECT
                  marca.nombre
                FROM
                  marca
                WHERE producto.idMarca = marca.id) AS marca,
                detalleventa.precio AS precioVenta,
                detalleventa.cantidad,
                detalleventa.idVenta,
                detalleventa.idProducto,
                DATE_ADD(venta.`cobrarCada`,INTERVAL 6 DAY) AS fechadeentrega,
                venta.garantia,
                (SELECT
                  cliente.`nombre`
                FROM
                  cliente
                WHERE cliente.`id` = venta.`idCliente`) AS cliente,
                (SELECT
                  cliente.`nit`
                FROM
                  cliente
                WHERE cliente.`id` = venta.`idCliente`) AS nit
              FROM
                detalleventa
                INNER JOIN producto
                  ON producto.`tipoproducto` = 'servicio'
                INNER JOIN venta
              WHERE detalleventa.idVenta = venta.id
                AND detalleventa.idProducto = producto.id
                AND detalleventa.estado = 2
                AND detalleventa.`idVenta` = ?", [$id]);
        return response()->json($results);
    }

    public function devolverproducto($id) {
        DB::table('detalleventa')->where('idVenta', $id)->update(['estado' => 0]);
        return response()->json($id);
    }

    public function AnularVenta($id) {
        $actua = DB::table('venta')->where('id', $id)->update(['estado' => 2]);
        $actual = DB::table('cuentacobrar')->where('idVenta', $id)->update(['eliminado' => 1]);
        return response()->json($actua);
    }

    public function actualizaralmacenventa($idventa, $idalmacem) {
        $actua = DB::table('venta')->where('id', $idventa)->update([
            'idAlmacen' => $idalmacem]);
        DB::table('detalleventa')->where('idVenta', $idventa)->delete();
        $almacen = DB::select("Select almacen.nombre FROM almacen WHERE almacen.id = ?", [$idalmacem]);
        return response()->json($almacen);
    }

    public function imprimirnotaventa2($id) {
        $fecha;
        $idventa;
        $cliente;
        $nit;
        $correo;
        $celular;
        $telefono;
        $imagen;
        $total;
        $descripcion;
        $sucursal;
        $aCuenta;
        $totalVenta;
        $descuento;
        $hora;
        $saldo;
        $nombre;
        $propietario;
        $pais;
        $ciudad;
        $telefonosuc;
        $direccion;
        $formapago;
        $codcliente;
        $cobrarCada;
        $empleado;
        $ciudadcliente;
        $observaciones;
        $garantia;

        $estadoVenta = "";

        $personaentrega;
        $direccionenvio;
        $importetransporte;
        $fechaentrega;
        $horaentrega;
        $cuidadEnvio;
        $telefonopedido;
        $ci;
        $actividad;
        $results = DB::select("call proforma (?)", [$id]);
        $cuotas = DB::select("SELECT cuentacobrar.`fechaVencimiento`, `cuentacobrar`.`importe`
            FROM cuentacobrar
            WHERE `cuentacobrar`.`idVenta` = ?", [$id]);
        foreach ($results as $key => $value) {
            $fecha = $value->fecha;
            $idventa = $value->idVenta;
            $cliente = $value->cliente;
            $nit = $value->NIT;
            $correo = $value->correo;
            $celular = $value->celular;
            $telefono = $value->telefonoFijo;
            $total = $value->total;
            $imagen = $value->imagen;
            $descripcion = $value->descripcion;
            $sucursal = $value->sucursal;
            $aCuenta = $value->aCuenta;
            $hora = $value->hora;
            $totalVenta = $value->totalNeto;
            $saldo = $value->saldoACobrar;
            $descuento = $value->importedescuento;
            $nombre = $value->nombre;
            $propietario = $value->propietario;
            $pais = $value->pais;
            $ciudad = $value->ciudad;
            $telefonosuc = $value->telefono;
            $direccion = $value->direccion;
            $formapago = $value->formaPago;
            $codcliente = $value->codcliente;
            $cobrarCada = $value->cobrarCada;
            $empleado = $value->empleado;
            $ciudadcliente = $value->ciudadcliente;
            $observaciones = $value->observaciones;
            $garantia = $value->garantia;
            $estadoVenta = $value->estadoVenta;
            $personaentrega = $value->personaentrega;
            $direccionenvio = $value->direccionenvio;
            $importetransporte = $value->importetransporte;
            $fechaentrega = $value->fechaentrega;
            $horaentrega = $value->horaentrega;
            $cuidadEnvio = $value->ciudadEnvio;
            $telefonopedido = $value->telefono;
            $ci = $value->ci;
            $actividad = $value->actividad;
        }
        $usuario;
        $idventa;
        $mesa;
        $estadopedido;
        $numeromesa;
        $orden;
        $comanda = DB::select("call comanda (?)", [$id]);
        foreach ($comanda as $key => $value) {
            $usuario = $value->empleado;
            $idventa = $value->idVenta;
            $mesa = $value->mesa;
            $numeromesa = $value->numeromesa;
            $orden = '0' . $value->orden;
        }
        if ($mesa == '' || $mesa == "0") {
            $estadopedido = "Para llevar";
        } else {
            $estadopedido = "Para " . $numeromesa;
        }
        if ($estadoVenta == 2) {
            return view('Reporte.notaventaEnvio2', compact(['orden', 'cuotas', 'ciudadcliente',
                'observaciones', 'empleado', 'cobrarCada', 'cuidadEnvio',
                'garantia', 'codcliente', 'formapago', 'direccion', 'telefonosuc',
                'ciudad', 'pais', 'propietario', 'nombre', 'descuento', 'saldo',
                'totalVenta', 'hora', 'aCuenta', 'sucursal', 'fecha', 'descripcion',
                'results', 'idventa', 'cliente', 'nit', 'correo', 'celular', 'telefono',
                'imagen', 'total', 'personaentrega', 'direccionenvio', 'importetransporte',
                'fechaentrega', 'horaentrega', 'telefonopedido', 'ci', 'estadoVenta', 'actividad',
                'cliente', 'comanda', 'estadopedido']));
        } else {
            return view('Reporte.notaventafactura.notaventa2', compact(['cuotas', 'garantia', 'observaciones', 'ciudadcliente', 'empleado',
                'cobrarCada', 'codcliente', 'formapago', 'direccion', 'telefonosuc', 'ciudad',
                'pais', 'propietario', 'nombre', 'descuento', 'saldo', 'totalVenta', 'hora',
                'aCuenta', 'sucursal', 'fecha', 'descripcion', 'results', 'idventa', 'cliente',
                'nit', 'correo', 'celular', 'telefono', 'imagen', 'total', 'orden',
                'usuario', 'estadopedido', 'comanda', 'actividad']));
        }
    }

    public function buscarcodigodebarra($parametro, $almacen, $tipoventa) 
         {
        $producto = DB::select("
                SELECT
                producto.id,
                producto.nombre,
                producto.descripcion,
                IF(? = 'Contado',producto.precioVenta, producto.precioVentaCredito) as precioVenta,
                producto.modelo,
                producto.estilo,
                producto.corte,
                (SELECT
                  marca.nombre
                FROM
                  marca
                WHERE producto.idMarca = marca.id) AS marca,
                (SELECT
                  `v_stockalmacensucursal`.`stock`
                    FROM
                  `v_stockalmacensucursal`
                WHERE v_stockalmacensucursal.`idproducto` = producto.id AND `v_stockalmacensucursal`.`idalmacen` = ?) AS stock,
                producto.color,
                producto.`stockMin`,
                producto.`stockMax`,
                producto.tamano,
                producto.`tipoproducto`,
                producto.imagen,
                producto.material,
                producto.eliminado,
                producto.codigoInterno,
                producto.codigoDeBarra,
                (SELECT
                  tipoproducto.nombre
                FROM
                  tipoproducto
                WHERE tipoproducto.id = producto.`idTipoProducto`) AS categoria,
                (SELECT
                  origen.nombre
                FROM
                  origen
                WHERE origen.id = producto.`idOrigen`) AS origen
              FROM
                producto
              WHERE  producto.codigoDeBarra = ?
                AND producto.eliminado = 0
                and `producto`.`tipoproducto`<> 'servicio'", [$tipoventa, $almacen, $parametro]);
        
        return $producto;
      }

    public function buscarcodigointerno($parametro, $almacen, $tipoventa, $sucursal) {
        $producto = DB::select("SELECT
                producto.id,
                producto.nombre,
                producto.descripcion,
                IF(? = 'Contado',productosucursal.precioVenta, productosucursal.precioVentaCredito) as precioVenta,
                producto.modelo,
                producto.estilo,
                producto.corte,
                (SELECT
                  marca.nombre
                FROM
                  marca
                WHERE producto.idMarca = marca.id) AS marca,
                (SELECT
                  `v_stockalmacensucursal`.`stock`
                    FROM
                  `v_stockalmacensucursal`
                WHERE v_stockalmacensucursal.`idproducto` = producto.id AND `v_stockalmacensucursal`.`idalmacen` = ?) AS stock,
                producto.color,
                producto.`stockMin`,
                producto.`stockMax`,
                producto.tamano,
                producto.`tipoproducto`,
                producto.imagen,
                producto.material,
                producto.eliminado,
                producto.codigoInterno,
                producto.codigoDeBarra,
                (SELECT
                  tipoproducto.nombre
                FROM
                  tipoproducto
                WHERE tipoproducto.id = producto.`idTipoProducto`) AS categoria,
                (SELECT
                  origen.nombre
                FROM
                  origen
                WHERE origen.id = producto.`idOrigen`) AS origen
              FROM
                producto
                INNER JOIN productosucursal ON producto.id=productosucursal.idproducto  AND productosucursal.idsucursal= ? 
              WHERE  producto.`codigoInterno` = ?
                AND producto.eliminado = 0
                and `producto`.`tipoproducto`<> 'servicio'", [$tipoventa, $almacen, $sucursal, $parametro]);
        return $producto;
    }

    public function buscarnombreproducto($parametro, $almacen, $tipoventa, $sucursal) {
                    
        /////////////////////////////
         $producto = DB::select("CALL buscarProductomaxminventa(?,?,?,?)",
                 [$tipoventa, $almacen,$sucursal,$parametro]);
        $existeidunavez=null;
         foreach ($producto as $key => $value) {
             $existeidunavez=$value->id;
             break;
         }
         if($existeidunavez==null){
          $producto = DB::select("
            SELECT
                producto.id,
                producto.nombre,
                producto.descripcion,
                IF(? = 'Contado',productosucursal.precioVenta, productosucursal.precioVentaCredito) as precioVenta,
                producto.modelo,
                producto.estilo,
                producto.corte,
                (SELECT
                  marca.nombre
                FROM
                  marca
                WHERE producto.idMarca = marca.id) AS marca,
                (SELECT
                  `v_stockalmacensucursal`.`stock`
                    FROM
                  `v_stockalmacensucursal`
                WHERE v_stockalmacensucursal.`idproducto` = producto.id AND `v_stockalmacensucursal`.`idalmacen` = ?) AS stock,
                producto.color,
                producto.`stockMin`,
                producto.`stockMax`,
                producto.tamano,
                producto.`tipoproducto`,
                producto.imagen,
                producto.material,
                producto.eliminado,
                producto.codigoInterno,
                producto.codigoDeBarra,
                (SELECT
                  tipoproducto.nombre
                FROM
                  tipoproducto
                WHERE tipoproducto.id = producto.`idTipoProducto`) AS categoria,
                (SELECT
                  origen.nombre
                FROM
                  origen
                WHERE origen.id = producto.`idOrigen`) AS origen
              FROM
                producto
                INNER JOIN productosucursal ON producto.id=productosucursal.idproducto  AND productosucursal.idsucursal= ? 
              WHERE  producto.nombre LIKE CONCAT('%', ?, '%')
                AND producto.eliminado = 0
                and `producto`.`tipoproducto`<> 'servicio'
                and `producto`.`tipoproducto`<> 'ingrediente' ",
                [$tipoventa, $almacen, $sucursal, $parametro]);
         }
        return $producto;
        ////////////////////////////
    }

    public function listaproductodetalleventa($id) {
        $producto = DB::table('producto')
                ->select('nombre', 'id')
                ->where('id', $id)
                ->get('nombre', 'id');
        return response()->json($producto);
    }

    public function imprimirnotalquiler($id) {
        $fecha;
        $idventa;
        $cliente;
        $nit;
        $correo;
        $celular;
        $telefono;
        $imagen;
        $total;
        $descripcion;
        $sucursal;
        $aCuenta;
        $totalVenta;
        $descuento;
        $hora;
        $saldo;
        $nombre;
        $propietario;
        $pais;
        $ciudad;
        $telefonosuc;
        $direccion;
        $formapago;
        $codcliente;
        $cobrarCada;
        $empleado;
        $ciudadcliente;
        $observaciones;
        $garantia;

        $estadoVenta = "";

        $personaentrega;
        $direccionenvio;
        $importetransporte;
        $fechaentrega;
        $horaentrega;
        $cuidadEnvio;
        $telefonopedido;
        $ci;

        $results = DB::select("call proforma (?)", [$id]);
        $cuotas = DB::select("
            SELECT cuentacobrar.`fechaVencimiento`, `cuentacobrar`.`importe`
            FROM cuentacobrar
            WHERE `cuentacobrar`.`idVenta` = ?", [$id]);
        foreach ($results as $key => $value) {
            $fecha = $value->fecha;
            $idventa = $value->idVenta;
            $cliente = $value->cliente;
            $nit = $value->NIT;
            $correo = $value->correo;
            $celular = $value->celular;
            $telefono = $value->telefonoFijo;
            $total = $value->total;
            $imagen = $value->imagen;
            $descripcion = $value->descripcion;
            $sucursal = $value->sucursal;
            $aCuenta = $value->aCuenta;
            $hora = $value->hora;
            $totalVenta = $value->totalNeto;
            $saldo = $value->saldoACobrar;
            $descuento = $value->importedescuento;
            $nombre = $value->nombre;
            $propietario = $value->propietario;
            $pais = $value->pais;
            $ciudad = $value->ciudad;
            $telefonosuc = $value->telefono;
            $direccion = $value->direccion;
            $formapago = $value->formaPago;
            $codcliente = $value->codcliente;
            $cobrarCada = $value->cobrarCada;
            $empleado = $value->empleado;
            $ciudadcliente = $value->ciudadcliente;
            $observaciones = $value->observaciones;
            $garantia = $value->garantia;

            $estadoVenta = $value->estadoVenta;

            $personaentrega = $value->personaentrega;
            $direccionenvio = $value->direccionenvio;
            $importetransporte = $value->importetransporte;
            $fechaentrega = $value->fechaentrega;
            $horaentrega = $value->horaentrega;
            $cuidadEnvio = $value->ciudadEnvio;
            $telefonopedido = $value->telefono;
            $ci = $value->ci;
        }
        if ($estadoVenta == 2) {
            //escon envio
//        return view('Reporte.notaventaEnvio', compact(['descuento', 'saldo', 'totalVenta', 'hora', 'aCuenta', 'sucursal', 'fecha', 'descripcion', 'results', 'idventa', 'cliente', 'nit', 'correo', 'celular', 'telefono', 'imagen', 'total','personaentrega','direccionenvio','importetransporte','fechaentrega','horaentrega','estadoVenta']));
            $pdf = \PDF::loadView('Reporte.notaventaEnvio', compact(['cuotas', 'ciudadcliente', 'observaciones', 'empleado', 'cobrarCada', 'cuidadEnvio', 'garantia', 'codcliente', 'formapago', 'direccion', 'telefonosuc', 'ciudad', 'pais', 'propietario', 'nombre', 'descuento', 'saldo', 'totalVenta', 'hora', 'aCuenta', 'sucursal', 'fecha', 'descripcion', 'results', 'idventa', 'cliente', 'nit', 'correo', 'celular', 'telefono', 'imagen', 'total', 'personaentrega', 'direccionenvio', 'importetransporte', 'fechaentrega', 'horaentrega', 'telefonopedido', 'ci', 'estadoVenta']));
            return $pdf->setPaper('A4')->setOrientation('portrait')->stream();
        } else {
            //sin envio
//        return view('Reporte.notaventa', compact(['cuotas', 'garantia', 'observaciones', 'ciudadcliente', 'empleado', 'cobrarCada', 'codcliente', 'formapago', 'direccion', 'telefonosuc', 'ciudad', 'pais', 'propietario', 'nombre', 'descuento', 'saldo', 'totalVenta', 'hora', 'aCuenta', 'sucursal', 'fecha', 'descripcion', 'results', 'idventa', 'cliente', 'nit', 'correo', 'celular', 'telefono', 'imagen', 'total']));
            $pdf = \PDF::loadView('Reporte.notaalquiler', compact(['cuotas', 'garantia', 'observaciones', 'ciudadcliente', 'empleado', 'cobrarCada', 'codcliente', 'formapago', 'direccion', 'telefonosuc', 'ciudad', 'pais', 'propietario', 'nombre', 'descuento', 'saldo', 'totalVenta', 'hora', 'aCuenta', 'sucursal', 'fecha', 'descripcion', 'results', 'idventa', 'cliente', 'nit', 'correo', 'celular', 'telefono', 'imagen', 'total', 'estadoVenta']));
            return $pdf->setPaper('A4')->setOrientation('portrait')->stream();
        }
    }

    function obtenerdatosvent($id) {
        $acual = DB::select("SELECT
                (SELECT cliente.nombre FROM cliente WHERE cliente.id = venta.idCliente) as cliente,
                (venta.total - venta.importeDescuento) as total,
                venta.cobrarCada,
                venta.observaciones,
                venta.id
            FROM venta
            WHERE venta.id = ?", [$id]);
        return response()->json($acual);
    }

    function updatedate(Request $request) {
        $id = $request->id;
        $fecha = $request->fecha;
        $datos = DB::select("SELECT venta.cancelado FROM venta WHERE venta.id = ?", [$id]);
        $cancelado = $datos[0]->cancelado;
        if ($cancelado == 0) {
            DB::table('venta')->where('id', $id)->update(['cobrarCada' => $fecha]);
            $cant = DB::select("SELECT count(*) as total from (SELECT cuentacobrar.`fechaVencimiento`,
		cuentacobrar.`importe`,
		IF((SELECT SUM(cobrocuota.`importe`)
			FROM cobrocuota
			WHERE cobrocuota.`idCuentaCobrar` = cuentacobrar.`id` and cobrocuota.eliminado = 0) IS NULL, 0,
                        (SELECT SUM(cobrocuota.`importe`)
			FROM cobrocuota
			WHERE cobrocuota.`idCuentaCobrar` = cuentacobrar.`id` and cobrocuota.eliminado = 0)) AS totalCobrado,
		cuentacobrar.`id`,
                (cuentacobrar.`importe` - (IF((SELECT SUM(cobrocuota.`importe`)
			FROM cobrocuota
			WHERE cobrocuota.`idCuentaCobrar` = cuentacobrar.`id` and cobrocuota.eliminado = 0) IS NULL, 0,
                        (SELECT SUM(cobrocuota.`importe`)
			FROM cobrocuota
			WHERE cobrocuota.`idCuentaCobrar` = cuentacobrar.`id` and cobrocuota.eliminado = 0)))) as total
	FROM cuentacobrar
	WHERE cuentacobrar.`idVenta` = ? and (cuentacobrar.`importe` - (IF((SELECT SUM(cobrocuota.`importe`)
			FROM cobrocuota
			WHERE cobrocuota.`idCuentaCobrar` = cuentacobrar.`id` and cobrocuota.eliminado = 0) IS NULL, 0,
                        (SELECT SUM(cobrocuota.`importe`)
			FROM cobrocuota
			WHERE cobrocuota.`idCuentaCobrar` = cuentacobrar.`id` and cobrocuota.eliminado = 0)))) != 0
	ORDER BY ID desc) as v", [$id]);
            $cuotasSaldo = $cant[0]->total;
            $results = DB::select("SELECT cuentacobrar.`fechaVencimiento`,
		cuentacobrar.`importe`,
		IF((SELECT SUM(cobrocuota.`importe`)
			FROM cobrocuota
			WHERE cobrocuota.`idCuentaCobrar` = cuentacobrar.`id` AND cobrocuota.eliminado = 0) IS NULL, 0,
                        (SELECT SUM(cobrocuota.`importe`)
			FROM cobrocuota
			WHERE cobrocuota.`idCuentaCobrar` = cuentacobrar.`id` AND cobrocuota.eliminado = 0)) AS totalCobrado,
		cuentacobrar.`id`,
                (cuentacobrar.`importe` - (IF((SELECT SUM(cobrocuota.`importe`)
			FROM cobrocuota
			WHERE cobrocuota.`idCuentaCobrar` = cuentacobrar.`id` AND cobrocuota.eliminado = 0) IS NULL, 0,
                        (SELECT SUM(cobrocuota.`importe`)
			FROM cobrocuota
			WHERE cobrocuota.`idCuentaCobrar` = cuentacobrar.`id` AND cobrocuota.eliminado = 0)))) AS total
	FROM cuentacobrar
	WHERE cuentacobrar.`idVenta` = ? ORDER BY id desc", [$id]);
            $fecha = Carbon::parse($fecha);
            $fechaFin = $fecha->subMonth();
            $date = Carbon::now();
            $cantdias = $date->diffInDays($fechaFin);
            $periodoCuotas = $cantdias / $cuotasSaldo;
            foreach ($results as $cuota) {
                if ($cuota->total !== "0") {
                    $fechavenc = $fechaFin->toDateString();
                    DB::table('cuentacobrar')->where('id', $cuota->id)->update(['fechaVencimiento' => $fechavenc]);
                    $fechaFin = $fechaFin->subDay($periodoCuotas);
                }
            }
        }
        return response()->json($results);
    }

    function updatecancel(Request $request) {
        $id = $request->id;
        $observaciones = $request->observaciones;
        $results = DB::table('venta')->where('id', $id)->update(['observaciones' => $observaciones, 'cancelado' => 1]);
        return response()->json($results);
    }
    
    
    function calculosventasmes($year,$month)
    {
 
            $anio=$request->anio;
            $mes=$request->mes;
            
            $results=DB::select(
                  "                   
	 SELECT producto.nombre as producto,detalleventa.idProducto as productoid,venta.idAlmacen as almacenid,SUM(detalleventa.cantidad)AS ideal
         FROM venta INNER JOIN detalleventa ON venta.id=detalleventa.idVenta LEFT JOIN producto ON 
         producto.id=detalleventa.idproducto -- and venta.idAlmacen=1
         WHERE YEAR(venta.fecha)=(SELECT (YEAR(NOW())-1) ) AND MONTH(venta.fecha)=?
         GROUP BY producto.nombre,detalleventa.idProducto,venta.idAlmacen
         ORDER BY ideal DESC 
               ",[$mes]);            
        return response()->json($results);
    }
            
        public function faltanterafa(Request $request) {             
            $mes=$request->mes;                      
            $results=DB::select(
                  "                   		  		  
		  SELECT productoid,producto,
		      almacenid,anio,mes,IFNULL((SELECT v_stockalmacensucursal.stock AS cantidad
                  FROM v_stockalmacensucursal WHERE v_stockalmacensucursal.idproducto =productoid
                  AND v_stockalmacensucursal.idalmacen = almacenid),0)AS cantidad,ideal,            
                  CASE WHEN
                     IFNULL((SELECT v_stockalmacensucursal.stock AS cantidad
                  FROM v_stockalmacensucursal WHERE v_stockalmacensucursal.idproducto =productoid
                     AND v_stockalmacensucursal.idalmacen = almacenid),0)<0 
                  THEN
                     (SELECT ((IFNULL((SELECT v_stockalmacensucursal.stock AS cantidad
                  FROM v_stockalmacensucursal WHERE v_stockalmacensucursal.idproducto =productoid
                     AND v_stockalmacensucursal.idalmacen = almacenid),0))-ideal))                  
		  WHEN
                   IFNULL((SELECT v_stockalmacensucursal.stock AS cantidad
                  FROM v_stockalmacensucursal
                    WHERE v_stockalmacensucursal.idproducto =productoid
                    AND v_stockalmacensucursal.idalmacen = almacenid),0)=0 
                  THEN
                     -ideal                   
                     WHEN
                    IFNULL((SELECT v_stockalmacensucursal.stock AS cantidad
                  FROM v_stockalmacensucursal
                  WHERE v_stockalmacensucursal.idproducto =productoid
                  AND v_stockalmacensucursal.idalmacen = almacenid),0)<ideal 
                  THEN                   
                  (SELECT ( (IFNULL((SELECT v_stockalmacensucursal.stock AS cantidad
                  FROM v_stockalmacensucursal
                  WHERE v_stockalmacensucursal.idproducto =productoid
                  AND v_stockalmacensucursal.idalmacen = almacenid),0)) )-ideal)                                                        
		  ELSE
		 (SELECT ((IFNULL((SELECT v_stockalmacensucursal.stock AS cantidad
                  FROM v_stockalmacensucursal
                  WHERE v_stockalmacensucursal.idproducto =productoid
                  AND v_stockalmacensucursal.idalmacen = almacenid),0)) )-ideal)  
	          END faltante                 
                   FROM idealtodoslosanios
                  WHERE anio=(SELECT (YEAR(NOW()))-1) AND mes=MONTH(NOW())
                  GROUP BY productoid,producto,
		  almacenid,anio,mes
		  HAVING  faltante<0
		ORDER BY faltante DESC 
               ");
        return response()->json($results);
    }
    
        public function faltanterafa2($mes,$almacen) {             
            $results=DB::select(
                  "                   		  		  
		  SELECT productoid,producto,
		      almacenid,anio,mes,IFNULL((SELECT v_stockalmacensucursal.stock AS cantidad
                  FROM v_stockalmacensucursal WHERE v_stockalmacensucursal.idproducto =productoid
                  AND v_stockalmacensucursal.idalmacen = almacenid),0)AS cantidad,ideal,            
                  CASE WHEN
                     IFNULL((SELECT v_stockalmacensucursal.stock AS cantidad
                  FROM v_stockalmacensucursal WHERE v_stockalmacensucursal.idproducto =productoid
                     AND v_stockalmacensucursal.idalmacen = almacenid),0)<0 
                  THEN
                     (SELECT ((IFNULL((SELECT v_stockalmacensucursal.stock AS cantidad
                  FROM v_stockalmacensucursal WHERE v_stockalmacensucursal.idproducto =productoid
                     AND v_stockalmacensucursal.idalmacen = almacenid),0))-ideal))                  
		  WHEN
                   IFNULL((SELECT v_stockalmacensucursal.stock AS cantidad
                  FROM v_stockalmacensucursal
                    WHERE v_stockalmacensucursal.idproducto =productoid
                    AND v_stockalmacensucursal.idalmacen = almacenid),0)=0 
                  THEN
                     -ideal                   
                     WHEN
                    IFNULL((SELECT v_stockalmacensucursal.stock AS cantidad
                  FROM v_stockalmacensucursal
                  WHERE v_stockalmacensucursal.idproducto =productoid
                  AND v_stockalmacensucursal.idalmacen = almacenid),0)<ideal 
                  THEN                   
                  (SELECT ( (IFNULL((SELECT v_stockalmacensucursal.stock AS cantidad
                  FROM v_stockalmacensucursal
                  WHERE v_stockalmacensucursal.idproducto =productoid
                  AND v_stockalmacensucursal.idalmacen = almacenid),0)) )-ideal)                                                        
		  ELSE
		 (SELECT ((IFNULL((SELECT v_stockalmacensucursal.stock AS cantidad
                  FROM v_stockalmacensucursal
                  WHERE v_stockalmacensucursal.idproducto =productoid
                  AND v_stockalmacensucursal.idalmacen = almacenid),0)) )-ideal)  
	          END faltante                 
                   FROM idealtodoslosanios
                  WHERE idealtodoslosanios.anio=(SELECT (YEAR(NOW()))-1)
                  AND idealtodoslosanios.mes=? and idealtodoslosanios.almacenid=?
                  GROUP BY productoid,producto,
		  almacenid,anio,mes
		  HAVING  faltante<0
		  RDER BY faltante DESC 
                  
               ",[$mes,$almacen]);
            
        return response()->json($results);
    }
    
     
            public function analisismetodo($mes,$anio, $idalmacen) {
            if($mes==0 && $anio==0 )//quiere decir que no selecciono ell mes adecuado
            {
                return response()->json(["mensaje" => "El anio y mes q consulta no pueden ser cero"]);
            }else 
            {
               if($mes>0 || $anio>0)
               {
              if($mes>0 && $anio>0){
                $results2 = DB::select("
                SELECT IFNULL(COUNT(anio),0)AS existe	    
                FROM valorventasvendidas 
                WHERE anio=? and mes=?", [$anio,$mes]);
                          $anioexiste=$results2[0]->existe;
                          if($anioexiste>0) // si año o mes existe o es >0
                          {
                DB::table('inicialparetto')->delete();
               /* DB::table('inicialparetto')
                          ->where('anio', $anio)
                        //  ->where('mes', $mes)
                          ->where('almacen',$idalmacen)->delete();*/
    
                DB::table('inicialparetto2')  ->delete();
                       /* ->where('anio2',$anio)
                      //  ->where('mes2',$mes)
                        ->where('almacen2',$idalmacen)
                        ->delete();*/
                
                DB::table('inicialreglaparettoabc')->delete();
                       /* ->where('anio',$anio)
                       // ->where('mes',$mes)
                        ->where('almacen',$idalmacen)
                        ->delete();*/
                         ////////////////////APLICO LA REGLA DE PARETTO ABC ///////////
      $results = DB::select("call AINICIALPARETTOANIOMES (?,?,?)",[$mes,$anio,$idalmacen]);
      $results = DB::select("call AINICIALPARETTOANIOMES2(?,?,?)",[$mes,$anio,$idalmacen]);
        
                     ////////////////////////obtenemos la consulta despuej de realizarse lo de paretto///
                         $results2 = DB::select("
                        SELECT idproductop2,nombrep2,preciopromediop2,unidadesvendidas2,
                        valorvendido2,participacion2,participacionacumulada2,clasificacionp2	    
                        FROM inicialparetto2");
                         $analisis = self::analisisabc_aniomesalmacen($mes,$anio,$idalmacen);
                    return response()->json($results2);  
                          }else 
                          {
                             return response()->json(["mensaje" => "El anio q consulta no existe"]);    
                          }   
                   }
                   if($anio>0){
                          $results2 = DB::select("
                SELECT IFNULL(COUNT(anio),0)AS existe	    
                FROM valorventasvendidas 
                WHERE anio=?", [$anio]);
                          $anioexiste=$results2[0]->existe;
                          if($anioexiste>0)
                          {
                         DB::table('inicialparetto')->where('anio',$anio)->delete();
                         DB::table('inicialparetto2')->where('anio2',$anio)->delete();
                         DB::table('inicialreglaparettoabc')->where('anio',$anio)->delete();
                         ////////////////////APLICO LA REGLA DE PARETTO ABC ///////////
           $results = DB::select("call AINICIALPARETTOANIO (?,?)", [$anio,$idalmacen]);
           $results = DB::select("call AINICIALPARETTOANIO2(?,?)", [$anio,$idalmacen]);
                     ////////////////////////obtenemos la consulta despuej de realizarse lo de paretto///
                         $results2 = DB::select("
                        SELECT idproductop2,nombrep2,preciopromediop2,unidadesvendidas2,
                        valorvendido2,participacion2,participacionacumulada2,clasificacionp2	    
                        FROM inicialparetto2 ");
                         $analisis = self::analisisabc_anioalmacen($anio,$idalmacen);
                    return response()->json($results2);  
                          }else {
                             return response()->json(["mensaje" => "El anio q consulta no existe"]);    
                          }                                               
                }else {
                        if($mes>0){
                            $anioactual=date('Y');
                            
                $results2 = DB::select("
                SELECT IFNULL(COUNT(anio),0)AS existe	    
                FROM valorventasvendidas 
                WHERE mes=? AND anio=?", [$mes,$anioactual]);
                          $mesexiste=$results2[0]->existe;
                          if($mesexiste>0)
                          {
                          DB::table('inicialparetto')
                                  ->where('anio',$anioactual)
                                 // ->where('mes',$mes)
                                  ->where('almacen',$idalmacen)
                                  ->delete();
                         DB::table('inicialparetto2')
                                 ->where('anio2',$anioactual)
                                // ->where('mes2',$mes)
                                 ->where('almacen2',$idalmacen)
                                 ->delete();                         
                         DB::table('inicialreglaparettoabc')
                                 ->where('anio',$anio)
                                // ->where('mes',$mes)
                                 ->where('almacen',$idalmacen)
                                 ->delete();
                         ////////////////////APLICO LA REGLA DE PARETTO ABC ///////////
                     $results = DB::select("call AINICIALPARETTOMES (?,?)", [$mes,$idalmacen]);
                     $results = DB::select("call AINICIALPARETTOMES2(?,?)",[$mes,$idalmacen]);
                     ////////////////////////obtenemos la consulta despuej de realizarse lo de paretto///
                         $results2 = DB::select("
                        SELECT idproductop2,nombrep2,preciopromediop2,unidadesvendidas2,
                        valorvendido2,participacion2,participacionacumulada2,clasificacionp2	    
                        FROM inicialparetto2 ");
                         $analisis = self::analisisabc();
                    return response()->json($results2);  
                          }else {
                             return response()->json(["mensaje" => "El mes q consulta no existe"]);    
                          }        
                        }
                   }
               }
            }
           /* DB::table('inicialparetto')->delete();
            DB::table('inicialparetto2')->delete();
            DB::table('inicialreglaparettoabc')->delete();*/
            
       
      //  return response()->json($results);
    }
   
   
    public function abc() {
        $results = DB::select("
    SELECT idproductop2,nombrep2,preciopromediop2,unidadesvendidas2,
	valorvendido2,participacion2,participacionacumulada2,
	CASE 
	WHEN participacionacumulada2 >=0.00 AND participacionacumulada2<=0.80
	THEN 'A'
	WHEN participacionacumulada2 >=0.81 AND participacionacumulada2<=0.95
	THEN 'B'
	ELSE 'C' END clasificacion2	    
    FROM inicialparetto2 ");
        return response()->json($results);
    }
    
    
    public function analisisabc() {
        $bool=false;
      $results = DB::select("
      SELECT CASE 
      WHEN clasificacionp2='A' THEN '0% - 80%' 
      WHEN clasificacionp2='B' THEN '81% - 95%'
      ELSE '96% - 100%' END participacion_estimada,
      clasificacionp2 AS participacion,COUNT(valorvendido2) AS n ,SUM(valorvendido2) AS ventas,
      anio2 as anio,mes2 as mes, almacen2 as almacen
      FROM inicialparetto2      
      GROUP BY clasificacionp2");
       ////////////////////////////////////////////////////////////////////////////
 $results2 = DB::select("SELECT IFNULL(COUNT(ventas),0)as existe
                                  FROM inicialreglaparettoabc
                                  ");
          
 $results3 = DB::select("SELECT id,participacion_estimada,participacion,n,participacionn,
                                 ventas,participacionventas
                                 FROM inicialreglaparettoabc
                                ");  
     foreach ($results2 as $key => $value)
            {  $existe=$value->existe;
                if($existe>0)
                 {$bool=true;}
            }
              $sumatotalventas = DB::select("
         SELECT SUM(valorvendido2) ventastotal
         FROM inicialparetto2 
          ");
          
          $totalventas=$sumatotalventas[0]->ventastotal;   
            $contadortotalventas = DB::select("
            SELECT (COUNT(valorvendido2)) AS ntotal
            FROM inicialparetto2
            ");          
        $contadorn=$contadortotalventas[0]->ntotal;          
        $participacion_estimada;
        $participacion;
        $n;
        $participacionn;
        $ventas;
        $participacionventas;
        $anio;
        $almacen;
        if($bool==false)
        {
             foreach ($results as $key => $value)
           {   $participacion_estimada=$value->participacion_estimada;
                $participacion=$value->participacion;
                $n=$value->n;
                $participacionn=$value->n/$contadorn;
                $ventas=$value->ventas;
                $participacionventas=$value->ventas/$totalventas;
                $anio=$value->anio; 
                $mes=$value->mes;
                $almacen=$value->almacen;                
                                     
             $actua2 = DB::table('inicialreglaparettoABC')->insert([
                 'participacion_estimada' => $participacion_estimada,  
                 'participacion' => $participacion,
                 'n' => $n,
                 'participacionn' => $participacionn,
                 'ventas' => $ventas,
                 'participacionventas' => $participacionventas,
                 'anio' => $anio,   
                 'mes'=>$mes,
                 'almacen' => $almacen,
                 ]);   
           }
        }else {
             return response()->json($results3);
              }

            $results3 = DB::select("
            SELECT id,participacion_estimada,participacion,n,participacionn,ventas,
            participacionventas,anio,almacen
                FROM inicialreglaparettoabc
                                 ");            
           return response()->json($results3);    
    }
    public function analisisabc_aniomesalmacen($mes,$anio,$almacen) {
         $bool=false;
         $results = DB::select("
      SELECT CASE 
      WHEN clasificacionp2='A' THEN '0% - 80%' 
      WHEN clasificacionp2='B' THEN '81% - 95%'
      ELSE '96% - 100%' END participacion_estimada,
      clasificacionp2 AS participacion,COUNT(valorvendido2) AS n ,SUM(valorvendido2) AS ventas,
      anio2 as anio,mes2 as mes,almacen2 as almacen
      FROM inicialparetto2
      WHERE anio2=? AND mes2=? AND almacen2=?
      GROUP BY clasificacionp2",[$anio,$mes,$almacen]);
////////////////////////////////////////////////////////////////////////////
 $results2 = DB::select("SELECT IFNULL(COUNT(ventas),0)as existe
                                  FROM inicialreglaparettoabc
                                  WHERE anio=? AND mes=? AND almacen=?",[$anio,$mes,$almacen]);
          
 $results3 = DB::select("SELECT id,participacion_estimada,participacion,n,participacionn,
                                 ventas,participacionventas
                                 FROM inicialreglaparettoabc
                                 WHERE anio=? AND mes=? AND almacen=?",[$anio,$mes,$almacen]);
          
           foreach ($results2 as $key => $value)
            {  $existe=$value->existe;
                if($existe>0)
                 {$bool=true;}
            }
         $sumatotalventas = DB::select("
         SELECT SUM(valorvendido2) ventastotal
         FROM inicialparetto2 
         WHERE anio2=? AND mes2=? AND almacen2=?",[$anio,$mes,$almacen]);
          
          $totalventas=$sumatotalventas[0]->ventastotal;   
            $contadortotalventas = DB::select("
            SELECT (COUNT(valorvendido2)) AS ntotal
            FROM inicialparetto2
            WHERE anio2=? AND mes2=? AND almacen2=?",[$anio,$mes,$almacen]);            
            
        $contadorn=$contadortotalventas[0]->ntotal;          
        $participacion_estimada;
        $participacion;
        $n;
        $participacionn;
        $ventas;
        $participacionventas;
        $anio;$mes;$almacen;
        if($bool==false)
        {
             foreach ($results as $key => $value)
           {   $participacion_estimada=$value->participacion_estimada;
                $participacion=$value->participacion;
                $n=$value->n;
                $participacionn=$value->n/$contadorn;
                $ventas=$value->ventas;
                $participacionventas=$value->ventas/$totalventas;
                $anio=$value->anio;
                $mes=$value->mes;
                $almacen=$value->almacen;                
                                     
             $actua2 = DB::table('inicialreglaparettoABC')->insert([
                 'participacion_estimada' => $participacion_estimada,  
                 'participacion' => $participacion,
                 'n' => $n,
                 'participacionn' => $participacionn,
                 'ventas' => $ventas,
                 'participacionventas' => $participacionventas,
                 'anio' => $anio,
                 'mes' => $mes,
                 'almacen' => $almacen,
                 ]);   
           }
        }else {
             return response()->json($results3);
        }  
         $results3 = DB::select("
            SELECT id,participacion_estimada,participacion,n,participacionn,ventas,
            participacionventas,anio,mes,almacen
                FROM inicialreglaparettoabc
                    ");
           return response()->json($results3);
    }
    
    //*ANALISIS PARETTO ANIOALMACEN*/
    public function analisisabc_anioalmacen($anio,$almacen)
    {
           $bool=false;
      $results = DB::select("
      SELECT CASE 
      WHEN clasificacionp2='A' THEN '0% - 80%' 
      WHEN clasificacionp2='B' THEN '81% - 95%'
      ELSE '96% - 100%' END participacion_estimada,
      clasificacionp2 AS participacion,COUNT(valorvendido2) AS n ,SUM(valorvendido2) AS ventas,
      anio2 as anio,mes2 as mes, almacen2 as almacen
      FROM inicialparetto2
      WHERE anio2=? AND almacen2=?
      GROUP BY clasificacionp2",[$anio,$almacen]);
       ////////////////////////////////////////////////////////////////////////////
 $results2 = DB::select("SELECT IFNULL(COUNT(ventas),0)as existe
                                  FROM inicialreglaparettoabc
                                  WHERE anio=? AND almacen=?",[$anio,$almacen]);
          
 $results3 = DB::select("SELECT id,participacion_estimada,participacion,n,participacionn,
                                 ventas,participacionventas
                                 FROM inicialreglaparettoabc
                                 WHERE anio=? AND almacen=?",[$anio,$almacen]);  
     foreach ($results2 as $key => $value)
            {  $existe=$value->existe;
                if($existe>0)
                 {$bool=true;}
            }
              $sumatotalventas = DB::select("
         SELECT SUM(valorvendido2) ventastotal
         FROM inicialparetto2 
         WHERE anio2=? AND almacen2=?",[$anio,$almacen]);
          
          $totalventas=$sumatotalventas[0]->ventastotal;   
            $contadortotalventas = DB::select("
            SELECT (COUNT(valorvendido2)) AS ntotal
            FROM inicialparetto2
            WHERE anio2=? AND almacen2=?",[$anio,$almacen]);          
        $contadorn=$contadortotalventas[0]->ntotal;          
        $participacion_estimada;
        $participacion;
        $n;
        $participacionn;
        $ventas;
        $participacionventas;
        $anio;
        $almacen;
        if($bool==false)
        {
             foreach ($results as $key => $value)
           {   $participacion_estimada=$value->participacion_estimada;
                $participacion=$value->participacion;
                $n=$value->n;
                $participacionn=$value->n/$contadorn;
                $ventas=$value->ventas;
                $participacionventas=$value->ventas/$totalventas;
                $anio=$value->anio; 
                $mes=$value->mes;
                $almacen=$value->almacen;                
                                     
             $actua2 = DB::table('inicialreglaparettoABC')->insert([
                 'participacion_estimada' => $participacion_estimada,  
                 'participacion' => $participacion,
                 'n' => $n,
                 'participacionn' => $participacionn,
                 'ventas' => $ventas,
                 'participacionventas' => $participacionventas,
                 'anio' => $anio,   
                 'mes'=>$mes,
                 'almacen' => $almacen,
                 ]);   
           }
        }else {
             return response()->json($results3);
              }

            $results3 = DB::select("
            SELECT id,participacion_estimada,participacion,n,participacionn,ventas,
            participacionventas,anio,almacen
                FROM inicialreglaparettoabc
                                 ");            
           return response()->json($results3);               
    }
    
    
    
    protected function maxminxdefecto()
    { 
        $año=date("Y"); 
        $almacen=1;
        $existeidunavez=null;
        $producto = DB::select("
    SELECT p.id,p.idProducto,producto.nombre,producto.descripcion,producto.precioVenta,
      producto.modelo, producto.estilo, producto.corte,p.idAlmacen,
    (SELECT marca.nombre
    FROM  marca
    WHERE producto.idMarca = marca.id) AS marca,
    (SELECT IFNULL(SUM(v_stockalmacensucursal.stock),0)AS stock
    FROM  v_stockalmacensucursal
    WHERE v_stockalmacensucursal.idproducto= p.idProducto AND v_stockalmacensucursal.idalmacen= ?) AS stock,  
    p.Emn AS stockMin,
    p.Emx AS stockMax,
    p.Pp AS puntoPedido,
    p.Cp AS consumoMediodiario,p.anio AS año,p.bandera 
    FROM productominmaxalmacen  p INNER JOIN producto ON producto.id=p.idProducto
    WHERE p.idAlmacen=? AND p.anio=?
    ORDER BY stock DESC  ",[$almacen,$almacen,$año]);
        $existeidunavez=null;
         foreach ($producto as $key => $value) 
             {
             $existeidunavez=$value->id;
             break;                            
             }
         if($existeidunavez==null)
         {
         $producto = DB::select("
        SELECT
          producto.id,
          producto.nombre,
          producto.descripcion,
          producto.precioVenta,
          producto.modelo,
          producto.estilo,
          producto.corte,
          (SELECT
            marca.nombre
          FROM
            marca
          WHERE producto.idMarca = marca.id) AS marca,
          (SELECT IFNULL(SUM(v_stockalmacensucursal.stock),0)AS stock
              FROM
            v_stockalmacensucursal
          WHERE v_stockalmacensucursal.idproducto = producto.id 
          AND v_stockalmacensucursal.idalmacen = ?) AS stock,
          producto.color,
          producto.stockMin,
          producto.stockMax,
          producto.tamano,
          producto.tipoproducto,
          producto.imagen,
          producto.material,
          producto.eliminado,
          producto.codigoInterno,
          producto.codigoDeBarra,
          '0'AS puntoPedido,
          '0' AS consumoMediodiario,
          'no calculado' AS bandera,
          (SELECT
            tipoproducto.nombre
          FROM
            tipoproducto
          WHERE tipoproducto.id = producto.idTipoProducto) AS categoria,
          (SELECT
            origen.nombre
          FROM
            origen
          WHERE origen.id = producto.idOrigen) AS origen
        FROM
          producto
        WHERE producto.eliminado = 0
        ORDER BY stock DESC ",[$almacen]);
         }
        return $producto;     
    }
    protected function ejecutarmaxminanual($anio,$almacen){
        $existeidunavez=0;
        $salida=0;        
        $verificasihay=0;
        $anioactual= date("Y");  
      //  $anioactual=$anioactual-1;
        $anioparapruebas=0;
        if($anio==0)
        {//con el año actual anterior ese almacen 
         //hay q contar si hay datos para este año
            $producto= DB::select("
                SELECT IFNULL(COUNT(p.id),0)as cantidad
                FROM productominmaxalmacen p
                WHERE p.anio=? AND p.idAlmacen=?
                ", [$anioactual, $almacen]);
         $verificasihay=$producto[0]->cantidad;   
         if($verificasihay>0)
         {
             //$anioactual = (string)$anioactual;                       
             //return response()->json(["mensaje" => "Ya esta ejecutado para este año ".$anio]);
             return response()->json(["mensaje" => "ya esta ejecutado para este año"]);
         }
        }else 
          {//aka lo restara con -1 el Año actual para realizar MaxMin
            $anio=(int)$anio;
            $anioparapruebas=$anio;           
            $producto= DB::select("
                SELECT IFNULL(COUNT(p.id),0)as cantidad
                FROM productominmaxalmacen p
                WHERE p.anio=? AND p.idAlmacen=?
                ", [$anioparapruebas, $almacen]);
         $verificasihay=$producto[0]->cantidad;
            if($verificasihay>0)
            {
            // $anio = (string) $anio;    
             return response()->json(["mensaje"=>"ya esta ejecutado para este año"]);
            }
           else{//lo ejecuta y devuelve algo     
              // $anioparapruebas=(int)$anioparapruebas+1;
        $producto= DB::select("call MAXIMOSMINIMOS(?,?)",[$anioparapruebas,$almacen]);  
         // $results = DB::select("call AINICIALPARETTOANIOMES (?,?)", [$mes,$anio]);
         //////////////////////////
            $producto= DB::select("
                SELECT IFNULL(COUNT(p.id),0)as cantidad
                FROM productominmaxalmacen p
                WHERE p.anio=? AND p.idAlmacen=?
                ", [$anioparapruebas, $almacen]);
            $verificasihay=$producto[0]->cantidad;
               if($verificasihay>0)            
               {
                  return response()->json(["mensaje"=>"Ejecutado Exitosamente"]);   
               }
               else
                 {
//$producto = DB::select("CALL buscarproducto(?,?)", [$parametro, $almacen]);
                   $anio2=$anio-1;
                   $anio2=(string)$anio;
                  return response()->json(["mensaje"=>"No habia datos el año".$anio2." para ejecutarlo para este año"]);      
                 }             
               }
           }
      }
      
      protected function graficarcalculo($anio,$almacen)
      {
        if($anio==0 && $almacen==0)  
        {
            return response()->json("0");
        }else 
        {
            $producto= DB::select("
                SELECT *
                FROM productominmaxalmacen p
                WHERE p.anio=? AND p.idAlmacen=?
                ", [$anio, $almacen]);
            return response()->json($producto);
        }
      }
      
     protected function eqq($producto,$almacen) {             
/*             $producto=$request->idProducto;  
             $almacen=$request->idAlmacen;                                  */
            /*$results=DB::select(
              "SELECT eqq.idProducto,eqq.idAlmacen,eqq.cantidad_optima,
                eqq.inventario_promedio,eqq.costo_total,eqq.n_pedidos,
                eqq.punto_reposicion
                FROM eqq 
                WHERE eqq.idProducto=? AND eqq.idAlmacen=? AND eqq.anio=YEAR(NOW())  
               ",[$producto, $almacen]);
            */
          $results=DB::select(
              "SELECT eqq.idProducto,producto.nombre,eqq.idAlmacen,eqq.cantidad_optima,
              eqq.punto_reposicion,eqq.inventario_promedio,eqq.costo_total,eqq.n_pedidos
                
                FROM eqq INNER JOIN producto ON producto.id=eqq.idProducto
                WHERE eqq.anio=YEAR(NOW())  
               ");
        return response()->json($results);
    }
    
    protected function ejecutareqq($almacen,$producto){
        /*$existeidunavez=0;
        $salida=0;        
        $verificasihay=0;*/
        $anioactual= date("Y");  
      
                DB::table('eqq')->delete();
                $results = DB::select("call EQQ_cantidadparametro (?)", [$almacen]);
                
                  $results2 = DB::select("
                SELECT eqq.idProducto,producto.nombre,eqq.idAlmacen,eqq.cantidad_optima,
                eqq.inventario_promedio,eqq.costo_total,eqq.n_pedidos,
                eqq.punto_reposicion
                FROM eqq INNER JOIN producto ON producto.id=eqq.idProducto
                WHERE eqq.anio=YEAR(NOW()) and eqq.idProducto=? and eqq.idAlmacen=?
                        ",[$producto,$almacen]);
                         
                    return response()->json($results2); 
               /* DB::table('inicialparetto2')->delete();
                DB::table('inicialreglaparettoabc')->delete(); */       
      }
}
