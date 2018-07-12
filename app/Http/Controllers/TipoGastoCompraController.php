<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use DB;

class TipoGastoCompraController extends Controller {

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
        if ($request->ajax()) {
            DB::table('tipogastoscompra')->insert(['nombre' => $request->nombre, 'eliminado' => 0]);
            return response()->json(["mensaje" => "Guardado exitoso"]);
        }
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
        $dato = DB::table('tipogastoscompra')->select('nombre', 'idTipoGasto')->where('idTipoGasto', $id)->get('nombre', 'idTipoGasto');
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
        $Actalizado = DB::table('tipogastoscompra')->where('idTipoGasto', $id)->update(['nombre' => $request->nombre]);
        return response()->json(["mensaje" => $Actalizado]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $eliminado = DB::table('tipogastoscompra')->where('idTipoGasto', $id)->update(['eliminado' => 1]);
        return response()->json(["mensaje" => "listo"]);
    }

    public function listarGastoCompra() {
        $banco = DB::table('tipogastoscompra')->select('nombre', 'idTipoGasto')->where('eliminado', 0)->get('nombre', 'idTipoGasto');
        return response()->json($banco);
    }

}
