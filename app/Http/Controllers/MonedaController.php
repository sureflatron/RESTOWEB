<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Moneda;
use App\Http\Requests;

class MonedaController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return "hola";
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
            DB::table('tipomoneda')->insert(['moneda' => $request->moneda, 'bs' => $request->bs, 'eliminado' => 0]);

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
//                  $dato= Moneda::find($id);
        $dato = DB::table('tipomoneda')->select('id', 'moneda', 'bs')->where('id', $id)->get('id', 'moneda', 'bs');
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
        $Actalizado = DB::table('tipomoneda')->where('id', $id)->update(['moneda' => $request->moneda, 'bs' => $request->bs]);
        return response()->json(["mensaje" => $Actalizado]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $eliminado = DB::table('tipomoneda')->where('id', $id)->update(['eliminado' => 1]);
        return response()->json(["mensaje" => "listo"]);
    }

    public function listademoneda() {
        $cargo = DB::table('tipomoneda')->select('moneda', 'bs', 'id')->where('eliminado', 0)->get('nombre', 'bs', 'id');
        return response()->json($cargo);
    }

    public function listarmoneda($id) {
        $cargo = DB::table('tipomoneda')->select('bs')->where('id', $id)->get('bs');
        return response()->json($cargo);
    }

}
