<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pais;
use DB;
use Session;
use Redirect;
use App\Http\Requests;

class PaisController extends Controller {

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
        DB::table('pais')->insert(['nombre' => $request->nombre]);

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
        $dato = Pais::find($id);
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
        $actua = DB::table('pais')->where('id', $id)->update(['nombre' => $request->nombre]);
        return response()->json(["mensaje" => "listo"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $actua = DB::table('pais')->where('id', $id)->update(['eliminado' => 1]);
        DB::table('ciudad')->where('idPais', $id)->update(['eliminado' => 1]);
        return response()->json(["mensaje" => "listo"]);
    }

    public function listarpais() {
        $otro = DB::table('pais')->select('nombre', 'id')->where('eliminado', 0)->get();
        return response()->json($otro);
    }

    public function GestionarCiudad($id) {
        $idpais = $id;
        $nomPaiss;
        $nombre = DB::table('pais')->select('nombre')->where('id', $idpais)->where('eliminado', 0)->lists('nombre');
        foreach ($nombre as $key => $value) {
            $nomPaiss = $value;
        }


        return view('Sucursal.Ciudad', compact(['idpais', 'nomPaiss']));
    }

}
