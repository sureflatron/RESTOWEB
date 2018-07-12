<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use Session;
use Redirect;
use Carbon\Carbon;
use JasperPHP\JasperPHP;

class ComprasController extends Controller {

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
        
        $results = DB::select("
            SELECT IFNULL(COUNT(detallecompra.idcompra),0) AS existedetalle
            FROM detallecompra 
            WHERE idcompra=?
         ", [$id]);
        $existeonodetalle=0;
        foreach ($results as $key => $value) {
            $existeonodetalle=$value->existedetalle;
        }
        if($existeonodetalle>0)
        {
        $actua = DB::table('compra')
                ->where('id', $id)
                ->update(['fecha' => $request->fecha,
            'estado' => $request->estado,
            'glosa' => $request->glosa,
            'tiempodeorden' => $request->tiempodeorden,
            'ordenestado'=>$request->ordenestado,
            'idProveedor' => $request->proveedor,
            'idAlmacen' => $request->almacen,
            'saldoacobrar' => $request->saldoacobrar,
            'acuenta' => $request->acuenta,
            'cobrarcada' => $request->cobrarcada,
            'cuotassaldo' => $request->cuotassaldo,
            'formaPago' => $request->formapago]);
        
        if ($request->formapago == "Credito") {
            $date = Carbon::now();
            $fechaInicio = $date->toDateString();
            $aux2 = Carbon::now();
            $endDate = $aux2->addMonth(1)->day($request->cobrarcada);
            $importe = $request->saldoacobrar / $request->cuotassaldo;
            $limite = $request->cuotassaldo;
            for ($i = 1; $i <= $limite; $i++) {
                $aux = $endDate;
                $fechaFin = $aux->toDateString();
                $cuentacobrar = DB::table('cuentaacobrar')->insert([
                    'fecha' => $fechaInicio,
                    'fechaVencimiento' => $fechaFin,
                    'importe' => $importe,
                    'glosa' => "",
                    'idCompra' => $id,
                    'idPuntoVenta' => $request->iddelpuntoventa,
                    'eliminado' => 0
                ]);
                $fechaInicio = $fechaFin;
                $endDate->addMonth(1);
            }
        }
       // return response()->json($request->all);    
        return response()->json(["bien"]);
        }else {
            return response()->json(["mal"]);
        }
        
    }

    /**
     * Remove the specified resource from obtenerdatoscompra.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        DB::table('compra')->where('id', $id)->update(['eliminado' => 1]);
        return response()->json($id);
    }

    public function listarcompras(Request $request) {
        $results = DB::select("SELECT almacen.nombre as almacen,
            compra.id,
            proveedor.nombre as proveedores,
            compra.fecha,
            compra.hora,
            compra.formaPago,
            empleado.nombre,
            compra.estado,
            compra.ordenestado,
            compra.tiempodeorden,
            compra.saldoacobrar,
            compra.saldoacobrar as saldo,
            compra.total as total,
                                          (IFNULL((SELECT 
                                              SUM(pago.pagado) AS cuotasPagadas 
                                            FROM
                                              (SELECT 
                                                SUM(cobroacuota.`importe`) AS pagado,
                                                cuentaacobrar.`id`,
                                                cuentaacobrar.`idCompra` 
                                              FROM
                                                cobroacuota,
                                                cuentaacobrar 
                                              WHERE cobroacuota.`idCuentaaCobrar` = cuentaacobrar.`id` AND cobroacuota.eliminado = 0
                                              GROUP BY cuentaacobrar.`id`) AS pago 
                                            WHERE pago.idCompra = compra.`id`),0)  + compra.`acuenta`)AS cobro
        from compra
        INNER JOIN puntoventa
        INNER JOIN proveedor
        INNER JOIN empleado 
        INNER JOIN almacen 
        WHERE compra.idPuntoventa=puntoventa.id
        and empleado.id=puntoventa.idEmpleado
        and proveedor.id=compra.idProveedor
        and almacen.id=compra.idAlmacen
        and compra.eliminado=0 
        and empleado.id=?
        and puntoventa.id=?
        ORDER BY compra.id DESC 
        ", [$request->idempleado, $request->iddelpuntoventa]);
        return response()->json($results);
    }

    public function generarcompras($id) {
        $date = Carbon::now();
        $fecha = $date->toDateString();
        $hora = $date->toTimeString();
        $idpunto = $id;
        $insertado = DB::table('compra')->insert(['idPuntoventa' => $idpunto,
            'estado' => 0,
            'hora' => $hora,
            'fecha' => $fecha,
            'idAlmacen' => 1,
            'idProveedor' => 1]);
        $ultimo = DB::table('compra')->select('id')->orderby('id', 'DESC')->take(1)->get('id');
        return response()->json($ultimo);
    }

    public function obtenerdatoscompra($id) {
        $ultimo = DB::table('compra')->select('glosa','tiempodeorden', 'idProveedor', 'fecha', 'hora', 'idAlmacen')->where('id', $id)->get();
        return response()->json($ultimo);
    }

    public function reportecompra($id) {
        return self::reportecompletodecompra($id);
    }

    public function reportecompletodecompra($id) {
        $idcompra;
        $fecha;
        $proveedor;
        $nomempleado;
        $nomempresa;
        $imagen;
        $web;
        $correo;
        $glosa;
        $almacen;
        $results = DB::select("SELECT DISTINCT almacen.nombre as almacen,empresa.nombre as nomempresa,empresa.imagen,empresa.web ,empresa.correo,empleado.nombre as nomempleado,proveedor.nombre as prove,compra.id,compra.fecha,compra.glosa,producto.nombre,detallecompra.cantidad,detallecompra.costo,  detallecompra.total as subtotal ,compra.total
        from compra 
        INNER JOIN detallecompra
        INNER JOIN producto
        INNER JOIN proveedor
        INNER JOIN empleado
        INNER JOIN puntoventa
        INNER JOIN sucursal
        INNER JOIN empresa
        INNER JOIN almacen
        WHERE compra.id=?
        and compra.id = detallecompra.idcompra
        and sucursal.idEmpresa=empresa.id
        and almacen.id=compra.idAlmacen
        and detallecompra.idProducto=producto.id
        and proveedor.id=compra.idProveedor
        and compra.idPuntoventa=puntoventa.id
        and puntoventa.idEmpleado=empleado.id
         ", [$id]);
        foreach ($results as $key => $value) {
            $idcompra = $value->id;
            $fecha = $value->fecha;
            $proveedor = $value->prove;
            $nomempleado = $value->nomempleado;
            $nomempresa = $value->nomempresa;
            $imagen = $value->imagen;
            $web = $value->web;
            $correo = $value->correo;
            $glosa = $value->glosa;
            $almacen = $value->almacen;
        }
        $pdf = \PDF::loadView('Reporte.Compra', compact(['results', 'idcompra', 'fecha', 'proveedor', 'nomempleado', 'nomempresa', 'imagen', 'web', 'correo', 'glosa', 'almacen']));
        return $pdf->stream();
//return $nomempleado;
    }

    //---------------------------------------------------------------------------
    //REPORTES 
    //---------------------------------------------------------------------------

    public function reportecompra1($id, $idempleado) {
        $output = public_path() . '/report/' . 'ComprobanteCompra';
        $report = new JasperPHP;
        $extencion = "pdf";
        $report->process(
                public_path() . '/report/comprobanteCompra.jrxml', $output, array($extencion), array("idcompra" => $id, "idempleado" => $idempleado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ComprobanteCompra.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    public function listarCuotasPagadas($id) {
        $results = DB::select("SELECT cobroacuota.`fecha`, cobroacuota.`importe`, `cobroacuota`.`id`
                                FROM cobroacuota
                                WHERE cobroacuota.`idCuentaaCobrar` = ? ", [$id]);
        return response()->json($results);
    }

    public function listarCuotas($id) {
        $results = DB::select("SELECT cuentaacobrar.`fechaVencimiento`,
        cuentaacobrar.`importe`,
        (SELECT SUM(cobroacuota.`importe`)
            FROM cobroacuota
            WHERE cobroacuota.idCuentaaCobrar = cuentaacobrar.`id`) AS totalCobrado,
        cuentaacobrar.`id`
    FROM cuentaacobrar
    WHERE cuentaacobrar.idCompra=?", [$id]);

        return response()->json($results);
    }

    public function listarcuotasdecompras(Request $request) {
        $date = Carbon::now();
        $fecha = $date->toDateString();
        $results = DB::select("SELECT Compra.id,
        Compra.fecha,
        Compra.hora,
        empleado.nombre,
        (SELECT SUM(pago.pagado) AS cuotasPagadas
        FROM (SELECT SUM(cobroacuota.`importe`) AS pagado, cuentaacobrar.`id`, cuentaacobrar.`idCompra`
            FROM cobroacuota, cuentaacobrar
            WHERE cobroacuota.`idCuentaaCobrar` = cuentaacobrar.`id`
            GROUP BY cuentaacobrar.`id`) AS pago
        WHERE pago.idCompra = Compra.`id`
        ) AS pgado,
        Compra.total,
        Compra.saldoACobrar,
        Compra.aCuenta
        FROM Compra 
        INNER JOIN puntoventa
        INNER JOIN empleado
        WHERE Compra.idpuntoventa=puntoventa.id 
                   AND puntoventa.idEmpleado=empleado.id
                   AND empleado.id=?
                   AND puntoventa.id=?
                   AND Compra.eliminado=0
                   AND Compra.formaPago = 'Credito'
        ORDER BY id DESC ", [$idEmpleado, $idEmpleado]);


        return response()->json($results);
    }
    
    
       public function buscarnombreproductocompra($parametro, $almacen) {       
        //$producto = DB::select("CALL buscarProductomaxmin(?,?)", [$parametro, $almacen]);
        $producto = DB::select("CALL buscarProductoeqq(?,?)", [$parametro, $almacen]);
        $existeidunavez=null;
         foreach ($producto as $key => $value)
                {
             $existeidunavez=$value->id;
             break;
                }
         if($existeidunavez==null){
         $producto = DB::select("CALL buscarProducto(?,?)", [$parametro, $almacen]);
         }
        return $producto;
       }
              
        public function reporteordencompra($id, $idempleado) {
        $output = public_path() . '/report/' . 'ComprobanteordenCompra';
        $report = new JasperPHP;
        $extencion = "pdf";
        $report->process(
                public_path() . '/report/comprobanteordenCompra.jrxml', $output, array($extencion), array("idcompra" => $id, "idempleado" => $idempleado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ComprobanteordenCompra.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }


}
