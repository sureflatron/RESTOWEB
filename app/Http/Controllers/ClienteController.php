<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Cliente;
use Carbon\Carbon;
use App\Http\Requests;

class ClienteController extends Controller {

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
        DB::table('cliente')->insert(['nombre' => $request->nombre,
            'direccion' => $request->direccion,
            'telefonoFijo' => $request->telefonoFijo,
            'celular' => $request->celular,
            'correo' => $request->correo,
            'razonSocial' => $request->razonSocial,
            'NIT' => $request->NIT,
            'idCiudad' => $request->idCiudad,
            'Preferencias' => $request->Preferencias,
            'idTipoCliente' => $request->idTipoCliente,
            'idDescuento' => 0,
            'descuentoporcliente' => $request->descuentoporcliente]);
        $ultimo = DB::table('cliente')->select('id', 'nombre')->orderby('id', 'DESC')->take(1)->get('id');
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
        $dato = Cliente::find($id);
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
        $actua = DB::table('cliente')->where('id', $id)->update([
            'nombre' => $request->nombre,
            'direccion' => $request->direccion,
            'telefonoFijo' => $request->telefonoFijo,
            'celular' => $request->celular,
            'correo' => $request->correo,
            'razonSocial' => $request->razonSocial,
            'NIT' => $request->NIT,
            'idCiudad' => $request->idCiudad,
            'Preferencias' => $request->Preferencias,
            'idDescuento' => 0,
            'idTipoCliente' => $request->idTipoCliente,
            'descuentoporcliente' => $request->descuentoporcliente]);
        return response()->json(["mensaje" => "listo"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $actua = DB::table('cliente')->where('id', $id)->update(['eliminado' => 1]);
        return response()->json(["mensaje" => "listo"]);
    }

    public function listarcliente() {
        $otro = DB::select('CALL listarcliente();');
        return response()->json($otro);
    }
    public function listarclientecodigo() {
        $otro = DB::select('CALL listarcliente();');
        return response()->json($otro);
    }

    public function buscarcliente($dato) {
        $results = DB::select("CALL buscarcliente(?);", [$dato]);
        $dato;
        if ($results == []) {
            $dato = 0;
            return response()->json($dato);
        } else {
            return response()->json($results);
        }
    }

    public function agregarcliente($nit, $nombre, $idVenta) {
        $ultimos;
        DB::table('cliente')->insert(['nombre' => $nombre,
            'razonSocial' => $nombre,
            'NIT' => $nit,
            'idTipoCliente' => 1]);
        $ultimo = DB::table('cliente')->select('id')->orderby('id', 'DESC')->take(1)->get('id');

        foreach ($ultimo as $key => $value) {
            $ultimos = $value->id;
        }
        DB::table('venta')->where('id', $idVenta)->update([
            'idCliente' => $ultimos]);
        return response()->json($ultimos);
    }

    public function descuento($id) {
        $results = DB::select("select tipodescuento.descuento from cliente, tipodescuento WHERE cliente.idDescuento = tipodescuento.id and cliente.id = ?", [$id]);
        return response()->json($results);
    }

    public function agregarrecibocliente(Request $request) {
        $idopticarecibo = 0;
        DB::table('opticarecibo')->insert([
            'laboratorio' => $request->laboratorio,
            'idventa' => $request->idventa,
            'CILlejos' => $request->CILLejos,
            'EJElejos' => $request->EJELejos,
            'PRISMAlejos' => $request->PRISMALejos,
            'DIlejos' => $request->DILejos,
            'ESFlejos' => $request->ESFLejos,
            'ESFCerca' => $request->ESFCerca,
            'CILCerca' => $request->CILCerca,
            'EJECerca' => $request->EJECerca,
            'PRISMACerca' => $request->PRISMACerca,
            'DICerca' => $request->DICerca,
            'total' => $request->totaloptica,
            'ORGANICO' => $request->ORGANICO,
            'POLICARBONATO' => $request->POLICARBONATO,
            'HIGHLITE' => $request->HIGHLITE,
            'VIDRIO' => $request->VIDRIO,
            'BLANCO' => $request->BLANCO,
            'ANTIRREFLEX' => $request->ANTIRREFLEX,
            'FOTOCROMATICO' => $request->FOTOCROMATICO,
            'CONTINTE' => $request->CONTINTE,
            'SENCILLA' => $request->SENCILLA,
            'BIFOCAL' => $request->BIFOCAL,
            'FUTUREX' => $request->FUTUREX,
            'PROGRESIVO' => $request->PROGRESIVO,
            'HIGHINDEX' => $request->HIGHINDEX,
            'fecha' => $request->fecha,
            'nombrecliente' => $request->nombrelente,
            'DICercaizquierdo' => $request->DICercaizquierdo,
            'PRISMACercaizquierdo' => $request->PRISMACercaizquierdo,
            'EJECercaizquierdo' => $request->EJECercaizquierdo,
            'CILCercaizquierdo' => $request->CILCercaizquierdo,
            'ESFCercaizquierdo' => $request->ESFCercaizquierdo,
            'DILejosizquierdo' => $request->DILejosizquierdo,
            'PRISMALejosizquierdo' => $request->PRISMALejosizquierdo,
            'EJELejosizquierdo' => $request->EJELejosizquierdo,
            'CILLejosizquierdo' => $request->CILLejosizquierdo,
            'ESFLejosizquierdo' => $request->ESFLejosizquierdo,
            'totalcliente' => $request->totalinterno,
            'idcliente' => $request->idCliente
        ]);
        $ultimo = DB::table('opticarecibo')->select('id')->orderby('id', 'DESC')->take(1)->get('id');
        DB::table('venta')->where('id', $request->idventa)->update(['idCliente' => $request->idCliente, 'fechaentrega' => $request->fechaentrega]);
        foreach ($ultimo as $key => $value) {
            $idopticarecibo = $value->id;
        }
        $precioventas = self::agregardetalleventa($request->totaloptica, $request->idventa, $idopticarecibo);
        return response()->json($precioventas);
    }

    public function agregardetalleventa($totaloptica, $idventa, $idopticarecibo) {
        $actua = DB::table('detalleventa')
                ->insert(['idVenta' => $idventa,
            'idopticarecibo' => $idopticarecibo,
            'idProducto' => 1,
            'cantidad' => 1,
            'precio' => $totaloptica,
            'total' => $totaloptica]);
        return "guardado";
    }

}
