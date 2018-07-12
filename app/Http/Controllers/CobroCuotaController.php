<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CobroCuota;
use DB;
use Session;
use Redirect;
use App\Http\Requests;
use Carbon\Carbon;

class CobroCuotaController extends Controller {

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
        $fecha = Carbon::now();
        $hora = Carbon::now();
        $fecha->toDateString();
        $hora->toTimeString();
        DB::table('cobrocuota')->insert([
            'fecha' => $fecha,
            'importe' => $request->importe,
            'idPuntoVenta' => $request->idPuntoVenta,
            'idCuentaCobrar' => $request->idCuentaCobrar,
            'eliminado' => 0,
            'hora' => $hora,
            'formaPago' => $request->formaPago,
            'pago' => $request->pago]);
        $ultimo = DB::table('cuentacobrar')->select('idVenta as id')->where('id', $request->idCuentaCobrar)->orderby('id', 'DESC')->take(1)->get('id');
        return response()->json([$ultimo]);
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
        $dato = DB::select("SELECT 
                    cobrocuota.id,
                    cobrocuota.importe,
                    ((SELECT 
                      cuentacobrar.importe 
                    FROM
                      cuentacobrar 
                    WHERE cuentacobrar.`id` = `cobrocuota`.`idCuentaCobrar`) - 
                    (SELECT 
                      SUM(c.`importe`) 
                    FROM
                      cobrocuota c,
                      `cuentacobrar`
                    WHERE c.`idCuentaCobrar` = cuentacobrar.`id` 
                      AND cobrocuota.`idCuentaCobrar` = c.idCuentaCobrar
                      AND c.eliminado = 0))AS totalCobrado 
                  FROM
                    cobrocuota 
                  WHERE cobrocuota.id = ? ", [$id]);
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
        $venta = DB::select("SELECT venta.id FROM venta, cuentacobrar, cobrocuota WHERE venta.id = cuentacobrar.idVenta AND cuentacobrar.id = cobrocuota.idCuentaCobrar
                AND cobrocuota.id = ?", [$id]);
        $idventa = $venta[0]->id;
        $datos = DB::select("SELECT venta.cancelado FROM venta WHERE venta.id = ?", [$idventa]);
        $cancelado = $datos[0]->cancelado;
        if ($cancelado == 0) {
            $actua = DB::table('cobrocuota')->where('id', $id)->update(['importe' => $request->importe]);
        }
        return response()->json(["mensaje" => "listo"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $venta = DB::select("SELECT venta.id FROM venta, cuentacobrar, cobrocuota WHERE venta.id = cuentacobrar.idVenta AND cuentacobrar.id = cobrocuota.idCuentaCobrar
                AND cobrocuota.id = ?", [$id]);
        $idventa = $venta[0]->id;
        $datos = DB::select("SELECT venta.cancelado FROM venta WHERE venta.id = ?", [$idventa]);
        $cancelado = $datos[0]->cancelado;
        if ($cancelado == 0) {
            $eliminado = DB::table('cobrocuota')->where('id', $id)->update(['eliminado' => 1]);
        }
        return response()->json(["mensaje" => "listo"]);
//
    }

    public function obtenerporductocredito($idVenta) {
        $dato = DB::select("SELECT 
                        producto.id,
                        producto.`nombre`,
                        producto.`descripcion`,
                        producto.`codigoDeBarra`,
                        producto.`color`, 
                        producto.`estilo`,
                        producto.`corte`,
                        cliente.nombre as cliente,
                        venta.cobrarCada,
                        (SELECT `tipoproducto`.`nombre` FROM `tipoproducto` WHERE `tipoproducto`.`id` = producto.`idTipoProducto`) AS categoria,
                        (SELECT marca.nombre FROM marca WHERE marca.id = producto.`idMarca`) AS marca,
                        producto.`imagen`,
                        detalleventa.`cantidad`,
                        venta.`observaciones`,
                        venta.`cobrarCada` AS fechaEntrega,
                        empresa.imagen as logo,
                        ROUND((detalleventa.`totalneto` / `detalleventa`.`cantidad`),2) AS preciofinal,
                        IF(detalleventa.estado = 0 ,'A',IF(detalleventa.estado = 1, 'M', 'E')) as estado
                FROM producto, detalleventa, venta, empresa, cliente
                WHERE `producto`.id = `detalleventa`.`idProducto`
                AND `detalleventa`.`idVenta` = venta.`id`
                AND cliente.id = venta.idCliente
                AND venta.`id` = ?", [$idVenta]);
        return response()->json($dato);
    }

    function productomodista($id) {
        DB::table('detalleventa')
                ->where('idVenta', $id)
                ->update(['estado' => 1]);
        return response()->json($id);
    }

    function entregarcliente($id) {
        DB::table('detalleventa')
                ->where('idVenta', $id)
                ->update(['estado' => 2]);
        return response()->json($id);
    }

    public function imprimirenviomodista($id) {
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
        $pdf = \PDF::loadView('Reporte.enviomodista', compact(['garantia', 'observaciones', 'ciudadcliente', 'empleado', 'cobrarCada', 'codcliente', 'formapago', 'direccion', 'telefonosuc', 'ciudad', 'pais', 'propietario', 'nombre', 'descuento', 'saldo', 'totalVenta', 'hora', 'aCuenta', 'sucursal', 'fecha', 'descripcion', 'results', 'idventa', 'cliente', 'nit', 'correo', 'celular', 'telefono', 'imagen', 'total', 'estadoVenta']));
        return $pdf->setPaper('A4')->setOrientation('portrait')->stream();
    }

    public function imprimirentrega($id) {
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
        $pdf = \PDF::loadView('Reporte.entrega', compact(['garantia', 'observaciones', 'ciudadcliente', 'empleado', 'cobrarCada', 'codcliente', 'formapago', 'direccion', 'telefonosuc', 'ciudad', 'pais', 'propietario', 'nombre', 'descuento', 'saldo', 'totalVenta', 'hora', 'aCuenta', 'sucursal', 'fecha', 'descripcion', 'results', 'idventa', 'cliente', 'nit', 'correo', 'celular', 'telefono', 'imagen', 'total', 'estadoVenta']));
        return $pdf->setPaper('A4')->setOrientation('portrait')->stream();
    }

}
