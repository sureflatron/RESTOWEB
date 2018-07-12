<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Proveedor;

class ProveedorController extends Controller {

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
        DB::table('proveedor')->insert(['nombre' => $request->nombre, 'direccion' => $request->direccion, 'telefono' => $request->telefono, 'paginaWeb' => $request->paginaWeb, 'contactoRef' => $request->contactoRef, 'telefonoContacto' => $request->telefonoContacto, 'idCiudad' => $request->idCiudad, 'correoContato' => $request->correoContato]);

        return response()->json($request->all());
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
        $dato = Proveedor::find($id);
        return response()->json($dato);
    }

    /**
     * Update the specified resource in storage. correoContato
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $actua = DB::table('proveedor')->where('id', $id)->update(['nombre' => $request->nombre, 'direccion' => $request->direccion, 'telefono' => $request->telefono, 'paginaWeb' => $request->paginaWeb, 'contactoRef' => $request->contactoRef, 'telefonoContacto' => $request->telefonoContacto, 'idCiudad' => $request->idCiudad, 'correoContato' => $request->correoContato]);
        return response()->json(["mensaje" => "listo"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $actua = DB::table('proveedor')->where('id', $id)->update(['eliminado' => 1]);
        return response()->json(["mensaje" => "listo"]);
    }

    public function listarproveedor() {
        $nombre = DB::table('proveedor')->select('id', 'nombre', 'direccion', 'contactoRef', 'telefono')->where('eliminado', 0)->get();
        return response()->json($nombre);
    }

    public function listarproveedorCombo() {
        $otro = DB::table('proveedor')->select('nombre', 'id')->where('eliminado', 0)->get('nombre', 'id');
        return response()->json($otro);
    }

}
