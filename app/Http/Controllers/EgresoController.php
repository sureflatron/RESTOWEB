<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Egreso;
use DB;
use Session;
use Redirect;
use Carbon\Carbon;
use App\Http\Requests;
use JasperPHP\JasperPHP;

class EgresoController extends Controller {

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
     * @return \Illuminate\Http\Response `id`, `fecha`, `importe`, `pagadoA`, `glosa`, `idTipoEgreso`, `idPuntoVenta`, `idProveedor`, `txnOrigen`, `eliminado`
     */
    public function store(Request $request) {
        $date = Carbon::now();
        $fecha = $date->toDateString();
        $hora = $date->toTimeString();
        DB::table('egreso')->insert([
            'fecha' => $request->fecha,
            'importe' => $request->importe,
            'pagadoA' => $request->pagadoA,
            'glosa' => $request->glosa,
            'idTipoEgreso' => $request->idTipoEgreso,
            'idPuntoVenta' => $request->puntoventa,
            'idProveedor' => $request->idProveedor,
            'hora' => $hora,
            'formaPago' => $request->formapago,
            'nroCuenta' => $request->nrocuenta
        ]);
        $ultimo = DB::table('egreso')->select('id')->orderby('id', 'DESC')->take(1)->get('id');
        return response()->json($ultimo);
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
        $dato = Egreso::find($id);
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
        $date = Carbon::now();
        $fecha = $date->toDateString();
        $actua = DB::table('egreso')->where('id', $id)
                ->update(['fecha' => $request->fecha, 'importe' => $request->importe, 'pagadoA' => $request->pagadoA, 'glosa' => $request->glosa, 'idTipoEgreso' => $request->idTipoEgreso, 'idPuntoVenta' => $request->puntoventa, 'idProveedor' => $request->idProveedor, 'formaPago' => $request->formapago, 'nroCuenta' => $request->nrocuenta]);
        $ultimo = DB::table('egreso')->select('id')->orderby('id', 'DESC')->take(1)->get('id');
        return response()->json($ultimo);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $actua = DB::table('egreso')->where('id', $id)->update(['eliminado' => 1]);
        return response()->json(["mensaje" => "listo"]);
    }

    public function listarEgreso(Request $request) {
        $results = DB::Select("select egreso.id,
                tipoegreso.nombre,
                egreso.fecha,
                empleado.nombre as restgistrado,
                egreso.importe,
                egreso.pagadoA,
                proveedor.nombre as provedor,
                egreso.glosa,
                egreso.hora,
            (select concepto.nombre from concepto where egreso.formapago = concepto.id) as formapago
            from egreso 
            INNER JOIN puntoventa
            INNER JOIN empleado
            INNER JOIN tipoegreso
            INNER JOIN proveedor
            where egreso.idPuntoVenta=puntoventa.id
            and puntoventa.idEmpleado=empleado.id
            and tipoegreso.id=egreso.idTipoEgreso
            and proveedor.id=egreso.idProveedor
            and puntoventa.id=?
            and egreso.eliminado=0
            order by id desc
            ", [$request->iddelpuntoventa]);
        return response()->json($results);
        ///   return "listando"
    }

    public function ultimoGuardado() {
        $results = DB::Select("select id from egreso ORDER BY id DESC LIMIT 1");
        return response()->json($results);
    }

//    public function reporteegreso($id){
//        return  self::reportecompletodecompra($id);
//    }

    public function reportecompletodecompra($id) {
        $results = DB::select("SELECT empresa.nombre as nomempresa,empresa.imagen,empresa.web ,empresa.correo,egreso.id,egreso.fecha,egreso.importe,egreso.pagadoA,egreso.glosa,tipoegreso.nombre as tipo,proveedor.nombre as proveedor,sucursal.nombre as sucursal
        FROM egreso 
        INNER JOIN tipoegreso
        INNER JOIN proveedor
        INNER JOIN sucursal
        INNER JOIN puntoventa
        INNER JOIN empresa
        WHERE egreso.idTipoEgreso=tipoegreso.id 
        and egreso.idPuntoVenta=puntoventa.id
        and egreso.idProveedor=proveedor.id
        and puntoventa.idSucursal=sucursal.id
        and sucursal.id=empresa.id
        and egreso.id=?
         ", [$id]);
        $id;
        $fecha;
        $importe;
        $pagadoA;
        $glosa;
        $tipo;
        $proveedor;
        $sucursal;
        $nomempresa;
        $imagen;
        $web;
        $correo;
        foreach ($results as $key => $value) {
            $id = $value->id;
            $fecha = $value->fecha;
            $proveedor = $value->proveedor;
            $importe = $value->importe;
            $pagadoA = $value->pagadoA;
            $glosa = $value->glosa;
            $tipo = $value->tipo;
            $sucursal = $value->sucursal;
            $nomempresa = $value->nomempresa;
            $imagen = $value->imagen;
            $web = $value->web;
            $correo = $value->correo;
        }
        $pdf = \PDF::loadView('Reporte.Egresos', compact(['id', 'fecha', 'importe', 'pagadoA', 'glosa', 'tipo', 'proveedor', 'sucursal', 'nomempresa', 'imagen', 'web', 'correo']));
        return $pdf->stream();
        //return $nomempleado;
    }

    public function reporteegreso($id, $idempleado) {
        $output = public_path() . '/report/' . 'ComprobanteEgreso';
        $report = new JasperPHP;
        $extencion = "pdf";

        $report->process(
                public_path() . '/report/comprobanteEgreso.jrxml', $output, array($extencion), array("idegreso" => $id, "idempleado" => $idempleado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ComprobanteEgreso.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    public function imprimirreciboegreso($id) {
        $ingresoid;
        $fecha;
        $hora;
        $recibidoDe;
        $glosa;
        $empresa;
        $propietario;
        $imagen;
        $sucursal;
        $direccion;
        $telefonosuc;
        $pais;
        $ciudad;
        $vendedor;
        $importe;
        $results = DB::select("SELECT 
                egreso.id AS ingresoid,
                egreso.fecha,
                egreso.hora,
                egreso.pagadoA as recibidoDe,
                egreso.glosa,
                egreso.importe,
                empresa.nombre AS empresa,
                empresa.propietario,
                empresa.imagen,
                sucursal.nombre AS sucursal, 
                sucursal.direccion,
                sucursal.telefono AS telefonosuc,
                (SELECT pais.nombre FROM pais, ciudad WHERE pais.id = ciudad.idPais AND  sucursal.idCiudad = ciudad.id) AS pais,
                (SELECT ciudad.nombre FROM ciudad WHERE ciudad.id = sucursal.`idCiudad`) AS ciudad,
                (SELECT empleado.nombre FROM empleado, puntoventa WHERE empleado.id = puntoventa.idEmpleado AND puntoventa.id = egreso.idPuntoVenta) as empleado
            FROM
                empresa, egreso, puntoventa, sucursal
            WHERE
                egreso.idPuntoVenta = puntoventa.id
                AND puntoventa.idSucursal = sucursal.id
                AND sucursal.idempresa = empresa.id
                AND egreso.id = ?
                ", [$id]);
        foreach ($results as $key => $value) {
            $ingresoid = $value->ingresoid;
            $fecha = $value->fecha;
            $hora = $value->hora;
            $recibidoDe = $value->recibidoDe;
            $glosa = $value->glosa;
            $empresa = $value->empresa;
            $propietario = $value->propietario;
            $imagen = $value->imagen;
            $sucursal = $value->sucursal;
            $direccion = $value->direccion;
            $telefonosuc = $value->telefonosuc;
            $pais = $value->pais;
            $ciudad = $value->ciudad;
            $vendedor = $value->empleado;
            $importe = $value->importe;
        }
        $pdf = \PDF::loadView('Reporte.notaegreso', compact(['ingresoid', 'fecha', 'hora', 'recibidoDe', 'glosa', 'empresa',
                    'propietario', 'imagen', 'sucursal', 'direccion', 'telefonosuc', 'pais', 'ciudad', 'vendedor', 'importe']));
        return $pdf->setPaper('A4')->setOrientation('portrait')->stream();
    }

    public function listaregresos() {
        $results = DB::Select("SELECT 
                `tipoegreso`.id,
                `tipoegreso`.`nombre`
              FROM `tipoegreso`
              WHERE `tipoegreso`.`eliminado` = 0");
        return response()->json($results);
    }

}
