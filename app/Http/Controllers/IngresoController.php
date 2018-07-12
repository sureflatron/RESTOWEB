<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ingreso;
use DB;
use Session;
use Redirect;
use Carbon\Carbon;
use App\Http\Requests;
use JasperPHP\JasperPHP;

class IngresoController extends Controller {

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
     * @return \Illuminate\Http\Response 'id', 'fecha', 'importe', 'recibidoDe', 'glosa', 'idTipoIngreso', 'idPuntoVenta', 'txnOrigen', 'eliminado'
     */
    public function store(Request $request) {
        $date = Carbon::now();
        $fecha = $date->toDateString();
        $hora = $date->toTimeString();
        DB::table('ingreso')->insert(['fecha' => $request->fecha, 'importe' => $request->importe, 'recibidoDe' => $request->recibidoDe, 'glosa' => $request->glosa, 'idTipoIngreso' => $request->idTipoIngreso, 'idPuntoVenta' => $request->puntoventa, 'hora' => $hora, 'formaPago' => $request->formapago, 'nroCuenta' => $request->nrocuenta]);
        $ultimo = DB::table('ingreso')->select('id')->orderby('id', 'DESC')->take(1)->get('id');
        return response()->json($ultimo);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $dato = Ingreso::find($id);
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
        $actua = DB::table('ingreso')->where('id', $id)->update(['fecha' => $request->fecha, 'importe' => $request->importe, 'recibidoDe' => $request->recibidoDe, 'glosa' => $request->glosa, 'idTipoIngreso' => $request->idTipoIngreso, 'idPuntoVenta' => $request->puntoventa, 'formaPago' => $request->formapago, 'nroCuenta' => $request->nrocuenta]);
        $ultimo = DB::table('ingreso')->select('id')->orderby('id', 'DESC')->take(1)->get('id');
        return response()->json($ultimo);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $actua = DB::table('ingreso')->where('id', $id)->update(['eliminado' => 1]);
        return response()->json(["mensaje" => "listo"]);
    }

    public function listarIngreso(Request $request) {
        $results = DB::select("select ingreso.id,
            tipoingreso.nombre,
            ingreso.fecha,
            empleado.nombre as restgistrado,
            ingreso.importe,
            ingreso.recibidoDe as recibido,
            ingreso.glosa,
            ingreso.hora,
            (select concepto.nombre from concepto where ingreso.formapago = concepto.id) as formapago
        from ingreso 
        INNER JOIN puntoventa
        INNER JOIN empleado
        INNER JOIN tipoingreso
        where ingreso.idPuntoVenta=puntoventa.id
        and puntoventa.idEmpleado=empleado.id
        and tipoingreso.id=ingreso.idTipoIngreso
        and puntoventa.id=?
        and ingreso.eliminado=0
         ORDER BY id DESC", [$request->iddelpuntoventa]);
        return response()->json($results);
        //   return "listando"
    }

//
//    public function reporteingreso($id) {
//        return self::reportecompletodecompra($id);
//    }

    public function reportecompletodecompra($id) {
        $results = DB::select("SELECT DISTINCT empresa.nombre as nomempresa,empresa.imagen,empresa.web ,empresa.correo,ingreso.id,ingreso.fecha,ingreso.importe,ingreso.recibidoDe,ingreso.glosa,tipoingreso.nombre as tipo, sucursal.nombre as sucursal
            FROM ingreso 
            INNER JOIN tipoingreso
             
            INNER JOIN sucursal
            INNER JOIN puntoventa 
            INNER JOIN empresa

            WHERE ingreso.idTipoIngreso=tipoingreso.id 
            and ingreso.idPuntoVenta=puntoventa.id
            and sucursal.idEmpresa=empresa.id
            and puntoventa.idSucursal=sucursal.id
            and ingreso.id=?
             ", [$id]);
        $id;
        $fecha;
        $importe;
        $recibidoDe;
        $glosa;
        $tipo;
        $nomempresa;
        $imagen;
        $web;
        $correo;
        $sucursal;
        foreach ($results as $key => $value) {
            $id = $value->id;
            $fecha = $value->fecha;
            $importe = $value->importe;
            $recibidoDe = $value->recibidoDe;
            $glosa = $value->glosa;
            $tipo = $value->tipo;
            $sucursal = $value->sucursal;
            $imagen = $value->imagen;
            $web = $value->web;
            $correo = $value->correo;
            $nomempresa = $value->nomempresa;
        }
        $pdf = \PDF::loadView('Reporte.Ingreso', compact(['id', 'fecha', 'importe', 'recibidoDe', 'glosa', 'tipo', 'sucursal', 'nomempleado', 'nomempresa', 'imagen', 'web', 'correo']));
        return $pdf->stream();
        //return $nomempleado;
    }

    public function reporteingreso($id, $idempleado) {
        $output = public_path() . '/report/' . 'ComprobanteIngreso';
        $report = new JasperPHP;
        $extencion = "pdf";

        $report->process(
                public_path() . '/report/comprobanteIngreso.jrxml', $output, array($extencion), array("idingreso" => $id, "idempleado" => $idempleado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ComprobanteIngreso.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    public function imprimirreciboingreso($id) {
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
                ingreso.id AS ingresoid,
                ingreso.fecha,
                ingreso.hora,
                ingreso.recibidoDe,
                ingreso.glosa,
                ingreso.importe,
                empresa.nombre AS empresa,
                empresa.propietario,
                empresa.imagen,
                sucursal.nombre AS sucursal, 
                sucursal.direccion,
                sucursal.telefono AS telefonosuc,
                (SELECT pais.nombre FROM pais, ciudad WHERE pais.id = ciudad.idPais AND  sucursal.idCiudad = ciudad.id) AS pais,
                (SELECT ciudad.nombre FROM ciudad WHERE ciudad.id = sucursal.`idCiudad`) AS ciudad,
                (SELECT empleado.nombre FROM empleado, puntoventa WHERE empleado.id = puntoventa.idEmpleado AND puntoventa.id = ingreso.idPuntoVenta) as empleado
            FROM
                empresa, ingreso, puntoventa, sucursal
            WHERE
                ingreso.idPuntoVenta = puntoventa.id
                AND puntoventa.idSucursal = sucursal.id
                AND sucursal.idempresa = empresa.id
                AND ingreso.id = ?
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
        $pdf = \PDF::loadView('Reporte.notaingreso', compact(['ingresoid', 'fecha', 'hora', 'recibidoDe', 'glosa', 'empresa',
                    'propietario', 'imagen', 'sucursal', 'direccion', 'telefonosuc', 'pais', 'ciudad', 'vendedor', 'importe']));
        return $pdf->setPaper('A4')->setOrientation('portrait')->stream();
    }

}
