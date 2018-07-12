<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use DB;

class BancoController extends Controller {

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
    public function store(Request $request) {
        if ($request->ajax()) {
            DB::table('banco')->insert(['nombre' => $request->nombre, 'eliminado' => 0]);
            return response()->json(["mensaje" => "Guardado exitoso"]);
        };
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
        $dato = DB::table('banco')->select('nombre', 'id')->where('id', $id)->get('nombre', 'id');
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
        $Actalizado = DB::table('banco')->where('id', $id)->update(['nombre' => $request->nombre]);
        return response()->json(["mensaje" => $Actalizado]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $eliminado = DB::table('banco')->where('id', $id)->update(['eliminado' => 1]);
        return response()->json(["mensaje" => "listo"]);
    }

    public function listabanco() {
        $banco = DB::table('banco')->select('nombre', 'id')->where('eliminado', 0)->get('nombre', 'id');
        return response()->json($banco);
    }

}
