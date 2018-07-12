<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Redirect;
use DB;
use App\Inventario;
use App\Http\Requests;
use Carbon\Carbon;
use JasperPHP\JasperPHP;

class InventarioController extends Controller {

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
        $actua = DB::table('inventario')->where('id', $id)->update([
            'fecha' => $request->fecha,
            'idMotivomovimiento' => $request->motivo,
            'glosa' => $request->glosa,
            'estado' => 1,]);
        return response()->json($actua);
    }

    /**
     * Remove the specified resource from storage.
     * id
      fecha
      almacen
      motivo
      glosa
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        DB::table('inventario')->where('id', $id)->update(['eliminado' => 1]);
        return response()->json($id);
    }

    public function listarinventario(Request $request) {
        $results = DB::select("SELECT
            inventario.id,
            inventario.estado,
            inventario.fecha,
            inventario.hora,
            inventario.idtipoinventario as tipo,
            inventario.glosa,
            empleado.nombre,
            motivomovimiento.nombre as motivo,
            IF((SELECT almacen.nombre FROM almacen WHERE almacen.id = inventario.idAlmacen) IS NULL, '', (SELECT almacen.nombre FROM almacen WHERE almacen.id = inventario.idAlmacen))AS almacenOrigen,
            IF((SELECT almacen.nombre FROM almacen WHERE almacen.id = inventario.idAlmacenDestino) IS NULL, '', (SELECT almacen.nombre FROM almacen WHERE almacen.id = inventario.idAlmacenDestino)) AS almacenDestino
        from inventario 
        INNER JOIN puntoventa 
        INNER JOIN empleado
        INNER JOIN motivomovimiento
        WHERE  inventario.idMotivomovimiento=motivomovimiento.id
        and puntoventa.idEmpleado=empleado.id
        and inventario.idPuntoventa=puntoventa.id 
        and inventario.eliminado=0
        ORDER BY inventario.id DESC 
        ");
        return response()->json($results); //
    }

//

    public function Generarinventario($id) {
        $date = Carbon::now();
        $fecha = $date->toDateString();
        $hora = $date->toTimeString();
        $idpunto = $id;
        $insertado = DB::table('inventario')->insert(['idPuntoventa' => $idpunto,
            'estado' => 0,
            'fecha' => $fecha,
            'hora' => $hora,
            'idMotivomovimiento' => 1,
            'idAlmacen' => 0,
            'idAlmacenDestino' => 1,
            'idtipoinventario' => 'Ingreso']);
        $ultimo = DB::table('inventario')->select('id')->orderby('id', 'DESC')->take(1)->get('id');
        return response()->json($ultimo);
    }

    public function obtenerdatosinventario($id) {
        $ultimo = DB::table('inventario')
                ->select('glosa', 'idAlmacen', 'idAlmacenDestino', 'fecha', 'idMotivomovimiento', 'idtipoinventario', 'hora')
                ->where('id', $id)
                ->get();
        return response()->json($ultimo);
    }

    public function reporteinventario($id) {
        return self::reportecompletodeinventario($id);
    }

    public function reportecompletodeinventario($id) {
        $idcompra;
        $fecha;
        $almacen;
        $nomempleado;
        $tipoinventario;
        $glosa;
        $nomempresa;
        $imagen;
        $web;
        $correo;
        $results = DB::select("SELECT DISTINCT empresa.nombre as nomempresa,empresa.imagen,empresa.web ,empresa.correo, empleado.nombre as nomempleado,almacen.nombre as almacen,inventario.idtipoinventario as tipo,inventario.id,inventario.fecha,inventario.glosa,producto.nombre,detalleinventario.cantidad
        from inventario 
        INNER JOIN detalleinventario
        INNER JOIN producto
        INNER JOIN almacen
        INNER JOIN empleado
        INNER JOIN puntoventa
        INNER JOIN sucursal
        INNER JOIN empresa
        WHERE inventario.id=?
        and inventario.id = detalleinventario.IdInventario
        and sucursal.idEmpresa=empresa.id
        and detalleinventario.idProducto=producto.id
        and almacen.id=inventario.idAlmacen
        and inventario.idPuntoventa=puntoventa.id
        and puntoventa.idEmpleado=empleado.id
        ", [$id]);
        foreach ($results as $key => $value) {
            $idcompra = $value->id;
            $fecha = $value->fecha;
            $almacen = $value->almacen;
            $nomempleado = $value->nomempleado;
            $tipoinventario = $value->tipo;
            $imagen = $value->imagen;
            $web = $value->web;
            $correo = $value->correo;
            $nomempresa = $value->nomempresa;
            $glosa = $value->glosa;
        }
        return View('Reporte.Inventario', compact(['results', 'idcompra', 'fecha', 'almacen', 'nomempleado', 'tipoinventario', 'nomempleado', 'nomempresa', 'imagen', 'web', 'correo', 'glosa']));
//return $nomempleado;
    }

    public function reporteinventario1($id, $idempleado) {
        $output = public_path() . '/report/' . 'ComprobanteInventario';
        $report = new JasperPHP;
        $extencion = "pdf";

        $report->process(
                public_path() . '/report/comprobanteInventario1.jrxml', $output, array($extencion), array("idinventario" => $id, "idempleado" => $idempleado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ComprobanteInventario.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    public function actualizaralmaceninv($idinventario, $idalmacem) {
        $tipo = DB::table('inventario')->select('idtipoinventario')->where('id', $idinventario)->get('idtipoinventario');
        $inv;
        foreach ($tipo as $tipo) {
            $inv = $tipo->idtipoinventario;
        }
        if ($inv == "Egreso") {
            DB::table('inventario')->where('id', $idinventario)->update([
                'idAlmacenDestino' => 0]);
        }
        $actual = DB::table('inventario')->where('id', $idinventario)->update([
            'idAlmacen' => $idalmacem]);
        $actua = DB::select("select inventario.idAlmacen from inventario where inventario.id = ?", [$idinventario]);
        return response()->json($actua);
    }

    public function actualizaralmaceninvdestino($idinventario, $idalmacem) {
        $tipo = DB::table('inventario')->select('idtipoinventario')->where('id', $idinventario)->get('idtipoinventario');
        $inv;
        foreach ($tipo as $tipo) {
            $inv = $tipo->idtipoinventario;
        }
        if ($inv == "Ingreso") {
            DB::table('inventario')->where('id', $idinventario)->update([
                'idAlmacen' => 0]);
        }
        $actual = DB::table('inventario')->where('id', $idinventario)->update([
            'idAlmacenDestino' => $idalmacem]);
        $actua = DB::select("select inventario.idAlmacenDestino from inventario where inventario.id = ?", [$idinventario]);
        return response()->json($actua);
    }

}
