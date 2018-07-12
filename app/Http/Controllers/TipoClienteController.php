<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TipoCliente;
use DB;
use Session;
use Redirect;
use App\Http\Requests;

class TipoClienteController extends Controller {

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
        DB::table('tipocliente')->insert(['nombre' => $request->nombre, 'abreviatura' => $request->abreviatura, 'eliminado' => 0]);
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
        $dato = TipoCliente::find($id);
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
        $actua = DB::table('tipocliente')->where('id', $id)->update(['nombre' => $request->nombre, 'abreviatura' => $request->abreviatura]);
        return response()->json(["mensaje" => "listo"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $eliminar = DB::table('tipocliente')->where('id', $id)->update(['eliminado' => 1]);
        return response()->json(["mensaje" => "listo"]);
    }

    public function listaTipoCliente() {
        $otro = DB::table('tipocliente')->select('id', 'nombre', 'abreviatura')->where('eliminado', 0)->get();
        return response()->json($otro);
    }

}
