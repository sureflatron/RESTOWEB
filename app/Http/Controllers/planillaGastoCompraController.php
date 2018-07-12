<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Redirect;
use DB;
use App\Http\Requests;
use Carbon\Carbon;
use JasperPHP\JasperPHP;

class planillaGastoCompraController extends Controller {

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
        $date = Carbon::now();
        $fecha = $date->toDateString();
        $hora = $date->toTimeString();
//        DB::table('gastocompra')->insert(['nombre' => $request->nombre, 'idsucursal' => $request->sucursal, 'idEmpleado' => $request->responsable]);
        DB::table('gastocompra')->insert([
            'idCompra' => $request->idcompra,
            'idTipoGastoCompra' => $request->idTipoGastoCompra,
            'fecha' => $fecha,
            'hora' => $hora,
            'idProveedor' => $request->idProveedor,
            'idFormaPago' => $request->idFormaPago,
            'nroCuenta' => $request->nroCuenta,
            'importe' => $request->importe,
            'afectaCostoProducto' => $request->afectaCostoProducto
        ]);

        return response()->json(['success' => true,
                    'message' => 'AGREGADO CORRECTAMENTE!']);
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
        $date = Carbon::now();
        $fecha = $date->toDateString();
        $hora = $date->toTimeString();

        $actua = DB::table('gastocompra')
                ->where('idGastoCompra', $id)
                ->update(['fecha' => $fecha,
            'hora' => $hora,
            'idTipoGastoCompra' => $request->gastoCompraModalG,
            'idProveedor' => $request->proveedorModalG,
            'idFormaPago' => $request->conceptoModalG,
            'nroCuenta' => $request->nroCuenta,
            'importe' => $request->importeModal,
            'afectaCostoProducto' => $request->afectaCostoProductoModal]);

        return response()->json($request->all);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id) {
        DB::table('gastocompra')->where('idGastoCompra', $id)->delete();
        return response()->json($request->all());
    }

    public function listarPlanillagasto($id) {
        $results = DB::select(
                        "SELECT
  gastocompra.idGastoCompra as id,
  tipogastoscompra.nombre AS tipoGasto,
  gastocompra.fecha,
  gastocompra.hora,
  proveedor.nombre AS proveedor,
  concepto.nombre AS formaPago,
  gastocompra.importe,
  gastocompra.afectaCostoProducto
FROM
  gastocompra,
  proveedor,
  tipogastoscompra,
  concepto
WHERE
  gastocompra.idProveedor = proveedor.id 
  AND gastocompra.idTipoGastoCompra = tipogastoscompra.idTipoGasto 
  AND gastocompra.idFormaPago = concepto.id 
  AND gastocompra.idCompra = ?", [$id]);
        return response()->json($results);
    }

    public function listarPlanillagastoEditar($id) {
        $results = DB::select(
                        "SELECT
                        gastocompra.idGastoCompra as id,
                        tipogastoscompra.nombre AS tipoGasto,
                        gastocompra.fecha,
                        gastocompra.hora,
                        gastocompra.nroCuenta,
                        proveedor.nombre AS proveedor,
                        concepto.nombre AS formaPago,
                        gastocompra.importe,
                        gastocompra.afectaCostoProducto,
                        
                        gastocompra.idTipoGastoCompra,
                        gastocompra.idProveedor,
                        gastocompra.idFormaPago
                      FROM
                        gastocompra,
                        proveedor,
                        tipogastoscompra,
                        concepto
                      WHERE
                        gastocompra.idProveedor = proveedor.id 
                        AND gastocompra.idTipoGastoCompra = tipogastoscompra.idTipoGasto 
                        AND gastocompra.idFormaPago = concepto.id 
                        AND gastocompra.idGastoCompra = ?", [$id]);
        return response()->json($results);
    }

    public function elimnars(Request $request) {
        $iddelacompra = "";
        $laconsulta = DB::table('gastocompra')->where('idGastoCompra', $request->idetallecompraGasto)->delete();

        return response()->json($laconsulta);
    }

    function reportPlanillaGasto($UsuarioLogeado, $idCompra, $exte) {
        $output = public_path() . '/report/' . 'PlanillaDeGastos';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/planillaDeImportacion.jrxml', $output, array($extencion), array("idempleado" => $UsuarioLogeado, "idcompra" => $idCompra), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'PlanillaDeGastos.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function ComprobanteDatos(Request $request) {
        $idCompra = $request->idcompra;
        $consulta = DB::select("SELECT * FROM gastocompra WHERE gastocompra.idCompra = ?", [$idCompra]);
        if (empty($consulta)) {
            return response()->json([ 'success' => false, 'errors' => $validator->getMessageBag()->toArray()]);
        } else {
            return response()->json(['success' => true, 'message' => 'ACTUALIZACION COMPLETADA!!']);
        }
    }

    

}
