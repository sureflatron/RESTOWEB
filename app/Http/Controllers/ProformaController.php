<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Venta;
use DB;
use Session;
use Redirect;
use Carbon\Carbon;
use App\Http\Requests;
use JasperPHP\JasperPHP;

class ProformaController extends Controller {

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

        $Venta1 = DB::select("SELECT SUM(detalleventa.cantidad) as cantidadVenta from detalleventa WHERE detalleventa.idVenta = $request->idventa");
        $Venta2 = DB::select("SELECT SUM(chasisproducto.checkBox) as cantidadChasis FROM chasisproducto WHERE chasisproducto.txnVenta = $request->idventa");
        $cantidadVenta = $Venta1[0]->cantidadVenta;
        $cantidadChasis = $Venta2[0]->cantidadChasis;

//        if ($cantidadVenta == $cantidadChasis) {
        $nitempresa = "";
        $nroAutorizacion;
        $llave;
        $estado;
        $idLibroOrden;
        $fechaLimite;
        $numeroinicio;
        $numerofin;
        $date = Carbon::now();
        $fecha = $date->toDateString();
        $date2 = Carbon::now();
        $hora = $date2->toTimeString();
        $fechacontrol = str_replace('-', '', $fecha);
        $ultimo;
        $numeroactual;
        $idSucursal;

        $comision;
        $idelemeplaedo;
        $emplecom = DB::select("SELECT 
                        empleado.id,
                        empleado.comision,
                        puntoventa.idSucursal
                      FROM
                        empleado,
                        puntoventa
                      WHERE
                      empleado.`id` = `puntoventa`.`idEmpleado`
                      AND `puntoventa`.`id` = ? ", [ $request->idpuntoventa]);
        foreach ($emplecom as $com) {
            $comision = $com->comision;
            $idSucursal = $com->idSucursal;
            $idelemeplaedo = $com->id;
        }
        $totalventa = $request->totaltotal - $request->importedescuento;
        $comisionimporte = ($comision * $totalventa) / 100;


        if ($request->facturacredito == "true") {
            $datofactura = self::obtenerdatoslibroderoden($idelemeplaedo, $request->idpuntoventa);
            foreach ($datofactura as $key => $value) {
                $nitempresa = $value->NIT;
                $nroAutorizacion = $value->nroAutorizacion;
                $llave = $value->llave;
                $estado = $value->estado;
                $idLibroOrden = $value->id;
                $fechaLimite = $value->fechaFin;
                $numeroinicio = $value->nroInicio;
                $numerofin = $value->nroFin;
                $idSucursal = $value->idSucursal;
            }
            $numeroactual = self::obtenernumerodefactura($idLibroOrden);
            if ($numeroactual == "No se puede generar factura porque no existe libro de órdenes activos" && $request->facturacredito == "true") {
                return response()->json([$numeroactual]);
            }
            if ($numeroactual == "Fecha limite a Terminado" && $request->facturacredito == "true") {
                return response()->json([$numeroactual]);
            }
            if ($numeroactual == "limite de numero de factura" && $request->facturacredito == "true") {
                return response()->json([$numeroactual]);
            }
        }
        $medaactiva = DB::table('mesa')->where('id', $request->idmesas)->update(['estado' => 0]);
        $sumador = DB::select("SELECT contador+1 as siguiente FROM sucursal WHERE sucursal.id=?", [$idSucursal]);
        $siguiente;
        foreach ($sumador as $key => $value) {
            $siguiente = $value->siguiente;
        }
        $sumarcontador = DB::table('sucursal')->where('id', $idSucursal)->update(['contador' => $siguiente]);
        $actua = DB::table('proforma')->where('id', $request->idventa)->update(['ordennumero' => $siguiente]);
        if ($request->totaltotal != null) {
            $actua = DB::table('proforma')->where('id', $request->idventa)->update(['estado' => 1,
                'formaPago' => $request->pago,
                'Pago' => $request->pagos,
                'Cambio' => $request->cambio,
                'idTipoDescuento' => $request->idTipoDescuento,
                'porcentajedescuento' => $request->porcentajedescuento,
                'total' => $request->totaltotal,
                'importedescuento' => $request->importedescuento,
                'aCuenta' => $request->aCuenta,
                'saldoACobrar' => $request->saldoACobrar,
                'cuotasSaldo' => $request->cuotasSaldo,
                'cobrarCada' => $request->cobrarCada,
                'idCliente' => $request->idCliente,
                'fecha' => $fecha,
                'hora' => $hora,
                'garantia' => $request->garantia,
                'observaciones' => $request->observaciones,
                'personaentrega' => $request->PersonaRecibeenvio,
                'direccionenvio' => $request->DireccionEnvio,
                'importetransporte' => $request->Importetransporte,
                'fechaentrega' => $request->FechaEntrega,
                'horaentrega' => $request->HoraEntrega,
                'estadoVenta' => $request->estadoenvio,
                'telefono' => $request->celular,
                'ciudad' => $request->departamento,
                'nroCuenta' => $request->nroCuenta,
                'idMesa' => $request->idmesas,
                'cobroAnticipo' => $request->cobroAnticipo,
                'comision' => $comisionimporte,
                'idPuntoVenta' => $request->idpuntoventa,
                'fechaEntregaVisal' => $request->fechaEntregaVisal,
                'etapa' => "venta"]);
        }
//            DB::table('chasisproducto')
//                    ->where('chasisproducto.txnVenta', $request->idventa)
//                    ->update(['chasisproducto.vendido' => 1]);

        DB::table('proforma')->where('id', $request->idventa)->update(['estado' => 1]);
//            if ($request->pago == "Credito") {
//                $date = Carbon::now();
//                $fechaInicio = $date->toDateString();
//                $periodoCuotas = $request->cobrarCada;
//                $aux2 = Carbon::now();
//                $endDate = $aux2->addDays($periodoCuotas);
//                $importe = $request->saldoACobrar / $request->cuotasSaldo;
//                $limite = $request->cuotasSaldo;
//                for ($i = 1; $i <= $limite; $i++) {
//                    $aux = $endDate;
//                    $fechaFin2 = $aux->toDateString();
//                    $cuentacobrar = DB::table('cuentaCobrar')->insert([
//                        'fecha' => $fechaInicio,
//                        'fechaVencimiento' => $fechaFin2,
//                        'importe' => $importe,
//                        'glosa' => "",
//                        'idVenta' => $request->idventa,
//                        'idPuntoVenta' => $request->idpuntoventa,
//                        'eliminado' => 0
//                    ]);
//                    $fechaInicio = $fechaFin2;
//                    if ($periodoCuotas == 1) {
//                        $endDate->addDay();
//                    } elseif ($periodoCuotas == 7) {
//                        $endDate->addWeek();
//                    } else if ($periodoCuotas == 15) {
//                        $endDate->addDays(15);
//                    } else if ($periodoCuotas == 30) {
//                        $endDate->addMonth();
//                    }
////                $endDate->addDays($periodoCuotas);
//                }
////            DB::table('detalleventa')->where('idVenta', $request->idventa)->update(['estado' => 0]);
//            }
//            if ($request->facturacredito == "true") {
//                $codigocontrols = $this->generate(
//                        $nroAutorizacion, $nitempresa, $request->nit, $fechacontrol, $request->total, $llave
//                );
//                DB::table('factura')->insert([
//                    'idLibroOrden' => $idLibroOrden,
//                    'idVenta' => $request->idventa,
//                    'idPuntoVenta' => $request->idpuntoventa,
//                    'nroFactura' => $numeroactual,
//                    'nroAutorizacion' => $nroAutorizacion,
//                    'codigoControl' => $codigocontrols,
//                    'fecha' => $fecha,
//                    'NIT' => $request->nit,
//                    'razonSocial' => $request->nombre,
//                    'fechaLimite' => $fechaLimite,
//                    'total' => $request->total,
//                    'totalLiteral' => $request->valorliteral,
//                    'eliminado' => 0]);
//                $actualibro = DB::table('libroorden')->where('id', $idLibroOrden)->update(['nroActual' => $numeroactual]);
//                $medaactiva = DB::table('mesa')->where('id', $request->idmesas)->update(['estado' => 0]);
//                $ultimo = DB::table('factura')->select('id')->orderby('id', 'DESC')->take(1)->get('id');
//                return response()->json([$ultimo]);
//            } else {
        $mensaje = DB::table('proforma')->select('id')->where('id', $request->idventa)->orderby('id', 'DESC')->take(1)->get('id');
        return response()->json([$mensaje]);
//            }
//        }else{
//            $numeroactual ="LAS CANTIDADES DE CHASIS NO SON LAS CORRECTAS!";
//            return response()->json([$numeroactual]);
//        }
    }

    public function obtenerdatoslibroderoden($idempleado, $idpunto) {
        $datos = DB::select("SELECT libroorden.id,
                libroorden.NIT,
                libroorden.nroAutorizacion,
                libroorden.llave,
                libroorden.estado,
                libroorden.nroInicio,
                libroorden.nroFin,
                libroorden.fechaInicio,
                libroorden.fechaFin,
                 libroorden.idSucursal
            from puntoventa 
            INNER JOIN sucursal 
            ON sucursal.id=puntoventa.idSucursal
            INNER JOIN libroorden 
            ON libroorden.idSucursal=sucursal.id 
            AND libroorden.eliminado=0 
            AND libroorden.estado=0 
            AND libroorden.nroFin<>libroorden.nroActual
            INNER JOIN empleado on empleado.id=puntoventa.idEmpleado
            WHERE empleado.id=? and puntoventa.id=?
            ", [$idempleado, $idpunto]);
        return $datos;
    }

    public function obtenernumerodefactura($idlibro) {
        $datos = DB::select("SELECT libroorden.nroActual+1 as total,
                libroorden.nroFin as limite,
                libroorden.estado,
                libroorden.fechaFin
            from libroorden
            WHERE libroorden.id=?
            and libroorden.estado=0
             ", [$idlibro]);
        $actual;
        $hasta;
        $estado;
        $mensaje;
        $fechafin;
        $date = Carbon::now();
        $fechaactual = $date->toDateString();
        foreach ($datos as $key => $value) {
            $actual = $value->total;
            $hasta = $value->limite;
            $estado = $value->estado;
            $fechafin = $value->fechaFin;
        }
        if ($estado == 1) {
            return $mensaje = "No se puede generar factura porque no existe libro de órdenes activos";
        }
        if ($actual > $hasta) {
            $mensaje = "limite de numero de factura";
        } else {
            $mensaje = $actual;
        }
        if ($fechafin < $fechaactual) {
            $mensaje = "Fecha limite a Terminado";
        } else {
            $mensaje = $actual;
        }
        return $mensaje;
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
        DB::table('proforma')->where('id', $id)->update(['eliminado' => 1]);
        DB::table('chasisProducto')
                ->where('txnVenta', $id)
                ->update(['txnVenta' => 0, 'checkBox' => 0]);
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
        $actua = DB::table('proforma')->where('id', $idVenta)->update(['estado' => 2, 'Motivo' => $request->motivo]);
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
        $insertado = DB::table('proforma')->insert(['fecha' => $fecha,
            'hora' => $hora,
            'idPuntoVenta' => $idpunto,
            'idCliente' => NULL,
            'formaPago' => 'Efectivo',
            'estado' => 0,
            'idAlmacen' => $idalmacen,
            'etapa' => 'venta',
            'alquiler' => 1]);
        $ultimo = DB::table('proforma')->select('id')->orderby('id', 'DESC')->take(1)->get('id');
        return response()->json($ultimo);
    }

//    public function generaralquiler($id) {
//        $date = Carbon::now();
//        $fecha = $date->toDateString();
//        $hora = $date->toTimeString();
//        $idpunto = $id;
//        $idalmacen = self::obteneralmacen($idpunto);
//        $insertado = DB::table('venta')->insert(['fecha' => $fecha,
//            'hora' => $hora,
//            'idPuntoVenta' => $idpunto,
//            'idCliente' => NULL,
//            'formaPago' => 'Efectivo',
//            'estado' => 0,
//            'idAlmacen' => $idalmacen,
//            'etapa' => 'venta',
//            'alquiler' => 0]);
//        $ultimo = DB::table('venta')->select('id')->orderby('id', 'DESC')->take(1)->get('id');
//        return response()->json($ultimo);
//    }
//    public function AnularVentas($id) {
//        $estado = DB::table('proforma')->select('id', 'estado')->where('id', $id)->get();
//        $estados = $estado[0]->estado;
//        if ($estados == 1) {
//            return response()->json(["mensaje" => "Venta ya cobrada no se puede anular"]);
//        }if ($estados == 0 || $estados == 4) {
//            $actual = DB::table('proforma')->where('id', $id)->update(['estado' => 2]);
//            return response()->json(["mensaje" => "Venta anulada exitosamente"]);
//        }if ($estados == 2) {
//            return response()->json(["mensaje" => "Esta venta ya fue anulada no se puede volver a nular"]);
//        }
//    }

    public function obteneralmacen($idpuntoventa) {
        $results = DB::select("CALL obteneralmacen(?);", [$idpuntoventa]);
        $idalmacen;
        foreach ($results as $key => $value) {
            $idalmacen = $value->idAlmacen;
        }
        return $idalmacen;
    }

    
    //  listar proforma sin id venta
//    public function listarproforma(Request $request) {
//        $empleado = $request->idempleado;
//        $otro = DB::select("SELECT
//                            perfil.nombre
//                          FROM
//                            empleado,
//                            puntoventa,
//                            perfil,
//                            usuario
//                          WHERE
//                            empleado.id = puntoventa.idEmpleado AND
//                            empleado.id = usuario.idEmpleado AND
//                            usuario.idPerfil = perfil.id AND
//                            perfil.nombre = 'Administrador' and
//                            empleado.id = $empleado");
//        $perf = "";
//        foreach ($otro as $otros) {
//            $perf = $otros->nombre;
//        }
//        if ($perf == "Administrador") {
//            $results = DB::select("SELECT 
//                    V.ID AS id,
//                    v.fecha,
//                    v.hora,
//                    v.esVenta,
//                    v.estado,
//                    v.cobroAnticipo,
//                    v.cobroAnticipo,
//                    v.`etapa`,
//                    'Administrador' AS nombrepergil,
//                    (SELECT empleado.nombre FROM empleado, `puntoventa` WHERE `empleado`.`id` = `puntoventa`.`idEmpleado`
//                          AND `puntoventa`.`id` = v.`idPuntoVenta`) AS nombre,
//                    v.formaPago,
//                    (SELECT cliente.nombre FROM cliente WHERE cliente.`id` = v.idCliente) AS cliente,
//                    (SELECT 
//                      factura.razonSocial 
//                    FROM
//                      factura 
//                    WHERE factura.idVenta = V.id) AS razon,
//                    (SELECT 
//                      SUM(
//                        detalleproforma.cantidad * detalleproforma.precio
//                      ) 
//                    FROM
//                      detalleproforma
//                    WHERE detalleproforma.idProforma = v.id) AS total,
//                    (
//                      (SELECT 
//                        SUM(detalleproforma.importedescuento) 
//                      FROM
//                        detalleproforma 
//                      WHERE detalleproforma.idProforma = v.id) + v.importedescuento
//                    ) AS importedescuento,
//
//                                             (IFNULL((SELECT 
//                                              SUM(pago.pagado) AS cuotasPagadas 
//                                            FROM
//                                              (SELECT 
//                                                SUM(cobrocuota.`importe`) AS pagado,
//                                                cuentacobrar.`id`,
//                                                cuentacobrar.`idVenta` 
//                                              FROM
//                                                cobrocuota,
//                                                cuentacobrar 
//                                              WHERE cobrocuota.`idCuentaCobrar` = cuentacobrar.`id` 
//                                              GROUP BY cuentacobrar.`id`) AS pago 
//                                            WHERE pago.idVenta = v.`id`),0)  + v.`aCuenta`)AS saldo
//                  FROM
//                    proforma V
//                  WHERE V.eliminado = 0 AND v.fecha = ? AND v.alquiler = 1
//                  ORDER BY v.id DESC", [$request->fecha]);
//            return response()->json($results);
//        } else {
//            $resultss = DB::select("SELECT proforma.id,
//                proforma.fecha,
//                proforma.cobroAnticipo,
//                usuario.idPerfil as perfil,
//                proforma.hora,
//                proforma.cobroAnticipo,
//                proforma.esVenta,
//                cliente.nombre AS cliente,
//                (SELECT
//                                    perfil.nombre empleadoPerfil
//                                  FROM
//                                    empleado,
//                                    puntoventa,
//                                    perfil,
//                                    usuario
//                                  WHERE
//                                    empleado.id = puntoventa.idEmpleado AND
//                                    empleado.id = usuario.idEmpleado AND
//                                    usuario.idPerfil = perfil.id AND
//                                    perfil.nombre = 'Administrador' and
//                                    empleado.id =?) as nombrepergil,
//
//                (SELECT factura.razonSocial 
//                    FROM factura 
//                    WHERE factura.idVenta = proforma.id) AS razon,
//                empleado.nombre,
//                proforma.estado,
//                proforma.etapa,
//                (SELECT SUM(detalleproforma.cantidad*detalleproforma.precio) 
//                        FROM detalleproforma 
//                        INNER JOIN producto
//                        WHERE producto.id=detalleproforma.idProducto 
//                        AND detalleproforma.idproforma=proforma.id) AS total,
//                ((SELECT SUM(detalleproforma.importedescuento) 
//                        FROM detalleproforma 
//                        WHERE  detalleproforma.idproforma=proforma.id)+ proforma.importedescuento) as importedescuento ,
//                proforma.formaPago,
//                    (IFNULL((SELECT 
//                     SUM(pago.pagado) AS cuotasPagadas 
//                   FROM
//                     (SELECT 
//                       SUM(cobrocuota.`importe`) AS pagado,
//                       cuentacobrar.`id`,
//                       cuentacobrar.`idVenta` 
//                     FROM
//                       cobrocuota,
//                       cuentacobrar 
//                     WHERE cobrocuota.`idCuentaCobrar` = cuentacobrar.`id` 
//                     GROUP BY cuentacobrar.`id`) AS pago 
//                   WHERE pago.idVenta = proforma.`id`),0)  + proforma.`aCuenta`)AS saldo
//                FROM usuario,proforma 
//                INNER JOIN puntoventa 
//                INNER JOIN empleado
//                LEFT JOIN cliente ON  cliente.id = proforma.idCliente
//                WHERE proforma.idPuntoVenta=puntoventa.id 
//                           AND puntoventa.idEmpleado=empleado.id
//                           and empleado.id = usuario.idEmpleado
//                           AND empleado.id=?
//                           AND puntoventa.id=?  
//                           AND proforma.eliminado=0
//                           and proforma.garantia = 0
//                           and proforma.fecha = ?
//                           AND proforma.alquiler = 1
//                order by proforma.id DESC", [$request->idempleado, $request->idempleado, $request->iddelpuntoventa, $request->fecha]);
//            return response()->json($resultss);
//        }
//    }
    
    
        public function listarproforma(Request $request) {
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
                    v.esVenta,
                    v.estado,
                    venta.id as idventa,
                    venta.estado as estadoVenta,
                    v.cobroAnticipo,
                    v.cobroAnticipo,
                    v.`etapa`,
                    'Administrador' AS nombrepergil,
                    (SELECT empleado.nombre FROM empleado, `puntoventa` WHERE `empleado`.`id` = `puntoventa`.`idEmpleado`
                          AND `puntoventa`.`id` = v.`idPuntoVenta`) AS nombre,
                    v.formaPago,
                    (SELECT cliente.nombre FROM cliente WHERE cliente.`id` = v.idCliente) AS cliente,
                    (SELECT 
                      factura.razonSocial 
                    FROM factura 
                    WHERE factura.idVenta = V.id) AS razon,
                    (SELECT 
                      SUM(detalleproforma.cantidad * detalleproforma.precio) 
                    FROM detalleproforma
                    WHERE detalleproforma.idProforma = v.id) AS total,
                    ( (SELECT 
                        SUM(detalleproforma.importedescuento) 
                      FROM  detalleproforma 
                      WHERE detalleproforma.idProforma = v.id) + v.importedescuento
                    ) AS importedescuento,
                      (IFNULL((SELECT 
                        SUM(pago.pagado) AS cuotasPagadas 
                        FROM (SELECT SUM(cobrocuota.`importe`) AS pagado, cuentacobrar.`id`,cuentacobrar.`idVenta` 
                         FROM cobrocuota,cuentacobrar 
                         WHERE cobrocuota.`idCuentaCobrar` = cuentacobrar.`id` 
                         GROUP BY cuentacobrar.`id`) AS pago 
                        WHERE pago.idVenta = v.`id`),0)  + v.`aCuenta`)AS saldo
                  FROM proforma V  LEFT JOIN venta ON venta.idProforma = v.id
                  WHERE V.eliminado = 0 AND v.fecha = ? AND v.alquiler = 1
                  ORDER BY v.id DESC", [$request->fecha]);
            return response()->json($results);
        } else {
            $resultss = DB::select("
                SELECT proforma.id,
                proforma.fecha,
                proforma.cobroAnticipo,
                usuario.idPerfil as perfil,
                proforma.hora,
                venta.id as idventa,
                venta.estado as estadoVenta,
                proforma.cobroAnticipo,
                proforma.esVenta,
                cliente.nombre AS cliente,
                (SELECT perfil.nombre empleadoPerfil
                FROM  empleado,puntoventa,perfil, usuario
                WHERE empleado.id = puntoventa.idEmpleado AND  empleado.id = usuario.idEmpleado AND
               usuario.idPerfil = perfil.id AND perfil.nombre = 'Administrador' and
               empleado.id =?) as nombrepergil,
                (SELECT factura.razonSocial 
                 FROM factura 
                 WHERE factura.idVenta = proforma.id) AS razon,empleado.nombre,proforma.estado, proforma.etapa,
                (SELECT SUM(detalleproforma.cantidad*detalleproforma.precio) 
                        FROM detalleproforma INNER JOIN producto
                        WHERE producto.id=detalleproforma.idProducto 
                        AND detalleproforma.idproforma=proforma.id) AS total,
                ((SELECT SUM(detalleproforma.importedescuento) 
                        FROM detalleproforma 
                        WHERE  detalleproforma.idproforma=proforma.id)+ proforma.importedescuento) as importedescuento ,
                proforma.formaPago,
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
                     WHERE cobrocuota.`idCuentaCobrar` = cuentacobrar.`id` 
                     GROUP BY cuentacobrar.`id`) AS pago 
                   WHERE pago.idVenta = proforma.`id`),0)  + proforma.`aCuenta`)AS saldo
                FROM usuario,proforma 
                INNER JOIN puntoventa INNER JOIN empleado LEFT JOIN cliente ON  cliente.id = proforma.idCliente
                LEFT JOIN venta ON venta.idProforma = proforma.id
                WHERE proforma.idPuntoVenta=puntoventa.id AND puntoventa.idEmpleado=empleado.id
                           and empleado.id = usuario.idEmpleado AND empleado.id=?
                           AND puntoventa.id=?  AND proforma.eliminado=0
                           and proforma.garantia = 0 and proforma.fecha = ?  AND proforma.alquiler = 1
                order by proforma.id DESC",
             [$request->idempleado, $request->idempleado, $request->iddelpuntoventa, $request->fecha]);
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
        $idpunto = $request->idpuntoventa;
        $idmesa = $request->idmesa;
        $insertado = DB::table('venta')->insert(['fecha' => $fecha,
            'hora' => $hora,
            'idPuntoVenta' => $request->iddelpuntoventa,
            'idCliente' => NULL,
            'formaPago' => 'Efectivo',
            'estado' => 0,
            'idMesa' => $request->idmesas,
            'etapa' => 'venta']);
        $ultimo = DB::table('venta')->select('id')->orderby('id', 'DESC')->take(1)->get('id');
        return response()->json($ultimo);
//return response()->json($ultimo);
    }

    public function Validarventaantigua($idventa) {
        $estados;

        $results = DB::select("SELECT proforma.estado FROM proforma WHERE proforma.id= ?", [$idventa]);
//        $results = DB::select("SELECT venta.estado, COUNT(chasisproducto.checkBox) as cantidad "
//                . "FROM venta,detalleventa,chasisproducto "
//                . "WHERE detalleventa.idVenta=? and "
//                . "detalleventa.idVenta = venta.id "
//                . "and detalleventa.idProducto= chasisproducto.idProducto  "
//                . "AND chasisproducto.idProducto=? "
//                . "AND chasisproducto.checkBox =1", [$idventa,$idpro]);
        foreach ($results as $key => $value) {
            $estados = $value->estado;
        }
        return response()->json($estados);
    }

    public function validarestado($id) {
        $estado = DB::table('proforma')->select('id', 'estado')->where('id', $id)->get();
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
        $producto = DB::select("CALL buscarproducto(?,?)", [$parametro, $almacen]);
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

    public function imprimirproforma($id) {
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
        $results = DB::select("call proforma (?)", [$id]);
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
            $direccion = $value->direccion;
            $pais = $value->pais;
            $ciudad = $value->ciudad;
            $telefonoEmpresa = $value->telefono;
        }
        $pdf = \PDF::loadView('Reporte.proforma', compact(['telefonoEmpresa', 'direccion', 'pais', 'ciudad', 'fecha', 'descripcion', 'results', 'idventa', 'cliente', 'nit', 'correo', 'celular', 'telefono', 'imagen', 'total']));
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

    public function proforma($id, $idcliente, $total) {
        $estado = DB::table('proforma')->select('id', 'estado')->where('id', $id)->get();
        $estados;
        foreach ($estado as $key => $value) {
            $estados = $value->estado;
        }
        $actual;
        if ($estados == 1) {
            return 0;
        }if ($estados == 0) {
            $actua = DB::table('venta')->where('id', $id)->update([
                'estado' => 0,
                'idCliente' => $idcliente,
                'total' => $total,
                'etapa' => 'proforma']);
            return response()->json($actua);
        }if ($estados == 2) {
            return 2;
        }
    }

    public function listarcreditos(Request $request) {
        $date = Carbon::now();
        $fecha = $date->toDateString();
        $results = DB::select("SELECT venta.id,
        venta.fecha,
        venta.hora,
        venta.observaciones,
        venta.cobrarCada,
        cliente.nombre AS nombrecli,
        (SELECT factura.razonSocial FROM factura WHERE factura.idVenta = venta.id) AS razon,
        empleado.nombre,
        (SELECT SUM(pago.pagado) AS cuotasPagadas
		FROM (SELECT SUM(cobrocuota.`importe`) AS pagado, cuentacobrar.`id`, cuentacobrar.`idVenta`
			FROM cobrocuota, cuentacobrar
			WHERE cobrocuota.`idCuentaCobrar` = cuentacobrar.`id`
			GROUP BY cuentacobrar.`id`) AS pago
		WHERE pago.idVenta = venta.`id`
		) AS pgado,
        venta.total,
        venta.importedescuento,
        venta.saldoACobrar,
        venta.aCuenta
        FROM venta 
        INNER JOIN puntoventa 
        INNER JOIN empleado
        LEFT JOIN cliente ON  cliente.id =venta.idCliente
        WHERE venta.idPuntoVenta=puntoventa.id 
                   AND puntoventa.idEmpleado=empleado.id 
                   AND venta.eliminado=0
                   AND venta.formaPago = 'Credito'
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
		cuentacobrar.`importe`,
		(SELECT SUM(cobrocuota.`importe`)
			FROM cobrocuota
			WHERE cobrocuota.`idCuentaCobrar` = cuentacobrar.`id`) AS totalCobrado,
		cuentacobrar.`id`
	FROM cuentacobrar
	WHERE cuentacobrar.`idVenta` = ?", [$id]);
        return response()->json($results);
    }

    public function listarCuotasPagadas($id) {
        $results = DB::select("SELECT cobrocuota.`fecha`, cobrocuota.`importe`,cobrocuota.`id`,cobrocuota.formaPago
                                FROM cobrocuota
                                WHERE cobrocuota.`idCuentaCobrar` = ? ", [$id]);
        return response()->json($results);
    }

    public function imprimirnotaventa($id) {
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
        $cobroAnticipo;

        $results = DB::select("call proformaVisal (?)", [$id]);
        $cuotas = DB::select("
            SELECT cuentacobrar.`fechaVencimiento`, `cuentacobrar`.`importe`
            FROM cuentacobrar
            WHERE `cuentacobrar`.`idVenta` = ?", [$id]);
        foreach ($results as $key => $value) {
            $fecha = $value->fecha;
            $idventa = $value->idProforma;
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
            $cobroAnticipo = $value->cobroAnticipo;
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
            $pdf = \PDF::loadView('Reporte.notaventaEnvio', compact(['cuotas', 'cobroAnticipo','ciudadcliente', 'observaciones', 'empleado', 'cobrarCada', 'cuidadEnvio', 'garantia', 'codcliente', 'formapago', 'direccion', 'telefonosuc', 'ciudad', 'pais', 'propietario', 'nombre', 'descuento', 'saldo', 'totalVenta', 'hora', 'aCuenta', 'sucursal', 'fecha', 'descripcion', 'results', 'idventa', 'cliente', 'nit', 'correo', 'celular', 'telefono', 'imagen', 'total', 'personaentrega', 'direccionenvio', 'importetransporte', 'fechaentrega', 'horaentrega', 'telefonopedido', 'ci', 'estadoVenta']));
            return $pdf->setPaper('A4')->setOrientation('portrait')->stream();
        } else {
            //sin envio
//        return view('Reporte.notaventa', compact(['cuotas', 'garantia', 'observaciones', 'ciudadcliente', 'empleado', 'cobrarCada', 'codcliente', 'formapago', 'direccion', 'telefonosuc', 'ciudad', 'pais', 'propietario', 'nombre', 'descuento', 'saldo', 'totalVenta', 'hora', 'aCuenta', 'sucursal', 'fecha', 'descripcion', 'results', 'idventa', 'cliente', 'nit', 'correo', 'celular', 'telefono', 'imagen', 'total']));
            $pdf = \PDF::loadView('Reporte.notaventa', compact(['cuotas', 'cobroAnticipo','garantia', 'observaciones', 'ciudadcliente', 'empleado', 'cobrarCada', 'codcliente', 'formapago', 'direccion', 'telefonosuc', 'ciudad', 'pais', 'propietario', 'nombre', 'descuento', 'saldo', 'totalVenta', 'hora', 'aCuenta', 'sucursal', 'fecha', 'descripcion', 'results', 'idventa', 'cliente', 'nit', 'correo', 'celular', 'telefono', 'imagen', 'total', 'estadoVenta']));
            return $pdf->setPaper('A4')->setOrientation('portrait')->stream();
        }
    }

    public function imprimirContratoChasis($id) {
//        self::imprimirContrato($id);
//        self::imprimirChasis($id);
        $this->imprimirContrato($id);
        $this->imprimirChasis($id);
    }

    public function imprimirContrato($id) {
        //Contrato========================================================
        $output = public_path() . '/report/' . 'Contrato';
        $report = new JasperPHP;
        $exte = 0;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/ContratoVisal.jrxml', $output, array($extencion), array("idventa" => $id), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'Contrato.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    public function imprimirChasis($id) {
        //Chasis========================================================
        $output = public_path() . '/report/' . 'Chasis';
        $report = new JasperPHP;
        $exte = 0;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/ChasisAdjuntoAlContrato.jrxml', $output, array($extencion), array("idventa" => $id), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'Chasis.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
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
        }
        return view('Reporte.recibo', compact(['fechacuota', 'totalcancelado', 'idcuota', 'cancelado', 'ciudad', 'pais', 'telf', 'direccion', 'propietario', 'empresa', 'glosa', 'tot', 'pagado', 'credito', 'descuento', 'saldo', 'totalVenta', 'hora', 'aCuenta', 'sucursal', 'fecha', 'descripcion', 'results', 'idventa', 'cliente', 'nit', 'correo', 'celular', 'telefono', 'imagen', 'total']));
    }

    public function ventasXempleados() {
        $results = DB::select("SELECT
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
                 ORDER BY total desc");
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
        $results = DB::select("SELECT cliente.razonSocial, cliente.nit, cliente.nombre
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

    public function AnularProforma($id) {
        $actua = DB::table('proforma')->where('id', $id)->update(['estado' => 2]);
//        $actual = DB::table('cuentacobrar')->where('idVenta', $id)->update(['eliminado' => 1]);
        
        return response()->json($actua);
    }

    public function actualizaralmacenventa($idinventario, $idalmacem) {
        $actua = DB::table('venta')->where('id', $idinventario)->update([
            'idAlmacen' => $idalmacem]);
        DB::table('detalleventa')->where('idVenta', $idinventario)->delete();
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

    public function buscarcodigodebarra($parametro, $almacen) {
        $producto = DB::select("SELECT
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
                and `producto`.`tipoproducto`<> 'servicio'", [$almacen, $parametro]);
        return $producto;
    }

    public function buscarcodigointerno($parametro, $almacen) {
        $producto = DB::select("SELECT
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
              WHERE  producto.`codigoInterno` = ?
                AND producto.eliminado = 0
                and `producto`.`tipoproducto`<> 'servicio'", [$almacen, $parametro]);
        return $producto;
    }

    public function buscarnombreproducto($parametro, $almacen) {
        $producto = DB::select("SELECT
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
               WHERE  producto.nombre LIKE CONCAT('%', ?, '%')
                AND producto.eliminado = 0
                and `producto`.`tipoproducto`<> 'servicio'", [$almacen, $parametro]);
        return $producto;
    }

    public function listaproductodetalleventa($id) {
        $producto = DB::table('producto')
                ->select('nombre', 'id', 'precioVenta')
                ->where('id', $id)
                ->get('nombre', 'id', 'precioVenta');
        return response()->json($producto);
    }

    public function generarproformas($id) {
        $date = Carbon::now();
        $fecha = $date->toDateString();
        $hora = $date->toTimeString();
        $idpunto = $id;
        $idalmacen = self::obteneralmacen($idpunto);
        $insertado = DB::table('proforma')->insert(['fecha' => $fecha,
            'hora' => $hora,
            'idPuntoVenta' => $idpunto,
            'idCliente' => NULL,
            'formaPago' => 'Efectivo',
            'estado' => 0,
            'idAlmacen' => $idalmacen,
            'etapa' => 'venta',
            'alquiler' => 1]);
        $ultimo = DB::table('proforma')->select('id')->orderby('id', 'DESC')->take(1)->get('id');
        return response()->json($ultimo);
    }

    public function convertirProformaVenta($idpunto, $idproforma) {
        $date = Carbon::now();
        $fecha = $date->toDateString();
        $hora = $date->toTimeString();
        
        $almacen = DB::select("SELECT proforma.idAlmacen as almacen from proforma WHERE proforma.id = $idproforma");
        $idalmacen = $almacen[0]->almacen;
        $productosProforma = DB::select("select detalleproforma.idProducto as producto,detalleproforma.cantidad, producto.nombre from detalleproforma,producto WHERE detalleproforma.idProforma =$idproforma AND detalleproforma.idProducto = producto.id ");

        $ExisteVenta = DB::select("SELECT venta.id as idventa FROM venta WHERE venta.estado <> 2 AND venta.idProforma = $idproforma");
        $entro = true;
        foreach ($ExisteVenta as $key2 => $value2) {
            $existe = $value2->idventa;
            $entro =false;
        $variable = ["message" => "entro a la consulta de existe venta /n"];
        }
        
        if($entro == false){ 
            DB::table('venta')->where('idProforma', $idproforma)->update(['fecha' => $fecha,'hora' => $hora]);
            return response()->json(["id" => $existe]); 
           $variable = ["message" => "entro a la bandera de entro == false /n"]; 
        }
        
        
        foreach ($productosProforma as $key => $value) {
            $variable = ["message" =>  "entro al forech de datosProducto as key => value /n"]; 
            $productoProf = $value->producto;
            $cantProforma = $value->cantidad;
            $nombreProducto = $value->nombre;

            $consulta1 = DB::select("SELECT
                  `v_stockalmacensucursal`.`stock`
                    FROM
                  `v_stockalmacensucursal`
                WHERE v_stockalmacensucursal.`idproducto` = $productoProf AND `v_stockalmacensucursal`.`idalmacen` = $idalmacen");
            
            $bandera = true;
            foreach ($consulta1 as $key1 => $value1) {
                $variable = ["message" => "entro al foreach de consulta1 as key =>value1 /n"];
                $stockDisponible = $value1->stock;
                $bandera =false;
            }

            if($bandera == true){
                $variable = ["message" => "entro a bandera == true con estockdisponible /n"];
                $stockDisponible = 0;
            }
            
            if ($stockDisponible >= $cantProforma) {
                
            } else {

                return response()->json(["message" => "NO HAY ESTOCK DISPONIBLE DE LA MOTO :  " . $nombreProducto]);
            }
            
        }







        //Generar nueva venta==============================================================================

        
//        $idpunto = $idpunto;
//        $idalmacen = self::obteneralmacen($idpunto);
        $datosPro = DB::select("select * from proforma WHERE proforma.id = $idproforma");
        
        foreach ($datosPro as $key => $datosProforma) {
            $variable = ["message" => "Entro al foreach de la convercion de la proforma /n"];        ////////////
            $totaltotal = $datosProforma->total-$datosProforma->cobroAnticipo;
            $insertado = DB::table('venta')->insert([
                'estado' => 0,
                'formaPago' => $datosProforma->formaPago,
                'idAlmacen' => $idalmacen,
                'Pago' => $datosProforma->Pago,
                'Cambio' => $datosProforma->Cambio,
                'idTipoDescuento' => $datosProforma->idTipoDescuento,
                'porcentajedescuento' => $datosProforma->porcentajedescuento,
                'total' => $totaltotal,
                'importedescuento' => $datosProforma->importedescuento,
                'aCuenta' => $datosProforma->aCuenta,
                'saldoACobrar' => $datosProforma->saldoACobrar,
                'cuotasSaldo' => $datosProforma->cuotasSaldo,
                'cobrarCada' => $datosProforma->cobrarCada,
                'idCliente' => $datosProforma->idCliente,
                'fecha' => $fecha,
                'hora' => $hora,
                'garantia' => $datosProforma->garantia,
                'observaciones' => $datosProforma->observaciones,
                'personaentrega' => $datosProforma->personaentrega,
                'direccionenvio' => $datosProforma->direccionenvio,
                'importetransporte' => $datosProforma->importetransporte,
                'fechaentrega' => $datosProforma->fechaentrega,
                'horaentrega' => $datosProforma->horaentrega,
                'estadoVenta' => $datosProforma->estadoVenta,
                'telefono' => $datosProforma->telefono,
                'ciudad' => $datosProforma->ciudad,
                'nroCuenta' => $datosProforma->nroCuenta,
                'idMesa' => $datosProforma->idMesa,
                'cobroAnticipo' => $datosProforma->cobroAnticipo,
                'comision' => $datosProforma->comision,
                'idPuntoVenta' => $datosProforma->idPuntoVenta,
                'fechaEntregaVisal' => $datosProforma->fechaEntregaVisal,
                'etapa' => $datosProforma->etapa,
                'esProforma' => 1,
                'idProforma' => $idproforma]);
        }

        //PARA EL DETALLE DE LA VENTA!!
        $ultimo = DB::select("select  venta.id FROM venta ORDER by venta.id DESC  LIMIT 1");
        $ultimoIdVenta = $ultimo[0]->id;
        $id = $ultimo[0]->id;

        $consulta = DB::select("SELECT * FROM detalleproforma WHERE detalleproforma.idProforma = $idproforma");
        foreach ($consulta as $key => $value) {
            $variable = ["message" => "entroa al foreach de consulta as key => value para insertar al detalle de la  proforma a la venta /n"];
            $actua = DB::table('detalleventa')->insert([
                'idVenta' => $ultimoIdVenta,
                'idProducto' => $value->idProducto,
                'cantidad' => $value->cantidad,
                'precio' => $value->precio,
                'total' => $value->total,
                'idtipodescuento' => $value->idtipodescuento,
                'porcentajedescuento' => $value->porcentajedescuento,
                'importedescuento' => $value->importedescuento,
                'totalneto' => $value->totalneto,
                'estado' => $value->estado,
                'nroFacturaCompra' => $value->nroFacturaCompra]);
        }



        $ultimo = DB::table('venta')->select('id')->orderby('id', 'DESC')->take(1)->get('id');
        
//        dd($ultimo);
        //=================================================================================================



        $estado = DB::table('venta')->select('etapa', 'estado', 'fecha', 'idCliente', 'idAlmacen', 'idPuntoVenta', 'cobroAnticipo')->where('id', $idproforma)->get();
        $clientes = DB::select("SELECT cliente.nombre, cliente.id, cliente.nit, cliente.razonSocial
                FROM cliente WHERE cliente.eliminado = 0");
        $Ciudad = DB::table('ciudad')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        //0=guardado 1=cobrado  2=anulado Gestionarfactura
        $moneda = DB::table('tipomoneda')->select('moneda', 'bs', 'id')->where('eliminado', 0)->get();
        $descuento = DB::table('tipodescuento')->select('id', 'descuento')->lists('descuento', 'id');
        foreach ($moneda as $key => $value) {
            $moneda1 = $value->moneda;
            $bs = $value->bs;
            $idm = $value->id;
        }
        $estados;
        $actual;
        $fecha;
        $idCliente = null;
        $nombre;
        $idalamcen;
        $puntoventa;
        $etapa;
        $cobroAnticipo;
        foreach ($estado as $key => $value) {
            $variable = ["message" => "entro al foreach  de estado as key => value  que es de la  consulta de la venta /n"];
            $estados = $value->estado;
            $fecha = $value->fecha;
            $idCliente = $value->idCliente;
            $idalmacen = $value->idAlmacen;
            $puntoventa = $value->idPuntoVenta;
            $etapa = $value->etapa;
            $cobroAnticipo = $value->cobroAnticipo;
        }
        $alm = DB::select("SELECT almacen.nombre FROM almacen WHERE almacen.id = ?", [$idalmacen]);
        $a;
        foreach ($alm as $key => $value) {
            $a = $value->nombre;
        }
        $punt = DB::select("SELECT puntoventa.ventamultialmacen FROM puntoventa WHERE puntoventa.id = ?", [$puntoventa]);
        $p;
        foreach ($punt as $key => $value) {
            $p = $value->ventamultialmacen;
        }
        if ($estados == 1) {
            $actual = "Cobrado";
            return redirect('/listadeproforma');
        }if ($estados == 0) {
            $actual = "Guardado";
        }if ($estados == 2) {
            $actual = "Anulado";
            return redirect('/listadeproforma');
        }
        if ($estados == 4) {
            $actual = "Proforma";
        }
        if ($estados == 5) {
            $actual = "Credito";
            return redirect('/listadeproforma');
        }
        $razon = null;
        $nit = null;
        $cliente = DB::table('cliente')->select('nombre', 'NIT', 'razonSocial')->where('id', $idCliente)->get();
        foreach ($cliente as $key => $value) {
            $nombre = $value->nombre;
            $razon = $value->razonSocial;
            $nit = $value->NIT;
        }
        $tipocliente = DB::table('tipocliente')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        $mesa = DB::table('mesa')->select('numeromesa', 'id')->where('eliminado', 0)->lists('numeromesa', 'id');
        $puntosdeventa = DB:: table('empleado')->select('empleado.nombre', 'puntoventa.id')
                        ->join('puntoventa', 'puntoventa.idEmpleado', '=', 'empleado.id')
                        ->where('empleado.eliminado', 0)->lists('empleado.nombre', 'puntoventa.id');
//        return view('Ventas.EditarVentas', compact('cobroAnticipo', 'puntosdeventa', 'etapa', 'p', 'a', 'idalmacen', 'nit', 'razon', 'Ciudad', 'id', 'mesa', 'actual', 'fecha', 'idCliente', 'nombre', 'clientes', 'tipocliente', 'moneda1', 'bs', 'descuento', 'idm'));
        return response()->json($ultimo);
    }

}
