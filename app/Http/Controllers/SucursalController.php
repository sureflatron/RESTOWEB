<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\sucursal;
use DB;
use Session;
use Redirect;
use App\Http\Requests;

class SucursalController extends Controller {

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
     * Store a newly created resource in storage. nombre: nombre,telefono:telefono,dirrecion:dirrecion,ciudad:ciudad idCiudad
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        DB::table('sucursal')->insert(['nombre' => $request->nombre, 'telefono' => $request->telefono, 'direccion' => $request->dirrecion, 'idCiudad' => $request->ciudad, 'idEmpresa' => $request->empresa]);
        return response()->json($request->all());
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
        $dato = Sucursal::find($id);
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
        $actua = DB::table('sucursal')->where('id', $id)->update(['nombre' => $request->nombre, 'telefono' => $request->telefono, 'direccion' => $request->dirrecion, 'idCiudad' => $request->ciudad, 'idEmpresa' => $request->empresa]);
        return response()->json(["mensaje" => "listo"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $actua = DB::table('sucursal')->where('id', $id)->update(['eliminado' => 1]);
        return response()->json(["mensaje" => "listo"]);
    }

    public function listarsucrusal() {
        $otro = DB::table('sucursal')->select('sucursal.nombre', 'sucursal.id', 'sucursal.direccion', 'sucursal.telefono', 'ciudad.nombre as ciudad')
                ->join('ciudad', 'ciudad.id', '=', 'sucursal.idCiudad')
                ->where('sucursal.eliminado', 0)
                ->get('nombre', 'id', 'direccion', 'telefono', 'ciudad');
        return response()->json($otro);
    }

    public function gestionarcontador() {
        $otro = DB::table('sucursal')->select('id', 'nombre', 'contador')
                        ->where('sucursal.eliminado', 0)->get();
        return response()->json($otro);
        // sucursal.idCiudad=ciudad.id
    }

    public function mostrarcontador($id) {
        $actua = DB::table('sucursal')->select('nombre', 'contador', 'id')->where('id', $id)->get();
        return response()->json($actua);
        // sucursal.idCiudad=ciudad.id
    }

    public function actualizarcontador(Request $request, $id) {
        $actua = DB::table('sucursal')->where('id', $id)->update(['contador' => $request->cantidad]);
        return response()->json($actua);
        // sucursal.idCiudad=ciudad.id
    }

    public function colocarcero(Request $request, $id) {
        $actua = DB::table('sucursal')->where('id', $id)->update(['contador' => 0]);
        return response()->json($actua);
        // sucursal.idCiudad=ciudad.id
    }

    public function listarSucursal($idempleado) {
        $otro = DB::select("select DISTINCT sucursal.id, sucursal.nombre as sucursal FROM sucursal,puntoventa,empleado where puntoventa.idSucursal=sucursal.id and puntoventa.idEmpleado = empleado.id and puntoventa.idEmpleado = $idempleado and sucursal.eliminado =0");
        return response()->json($otro);
    }

    public function sucursales() {
        $sucu = DB::table('sucursal')
                ->select('nombre', 'id')
                ->where('eliminado', 0)
                ->OrderBy('id', 'DESC')
                ->get();
        return response()->json($sucu);
    }

    public function listasucursal() {
        $sucu = DB::table('sucursal')->select('nombre', 'id')->where('eliminado', 0)->get();
        $sucu = DB::select('SELECT id,nombre FROM sucursal where eliminado=0
                order by id DESC
                ');
        return response()->json($sucu);
    }

}
