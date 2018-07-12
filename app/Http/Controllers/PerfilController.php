<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Perfil;
use DB;
use Session;
use Redirect;
use App\Http\Requests;

class PerfilController extends Controller {

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
        DB::table('perfil')->insert(['nombre' => $request->nombre]);
        $ultimo = DB::table('perfil')->select('id')->orderby('id', 'DESC')->take(1)->get('id');
        $perfil;
        foreach ($ultimo as $ultimo) {
            $perfil = $ultimo->id;
        }
        $objetos = DB::table('objeto')->select('id')->where('eliminado', 0)->get('id');
        foreach ($objetos as $objeto) {
            $idobjeto = $objeto->id;
            DB::table('perfilobjeto')->insert(['idPerfil' => $perfil,
                'idObjeto' => $idobjeto,
                'puedeGuardar' => 1,
                'puedeModificar' => 1,
                'puedeEliminar' => 1,
                'puedeListar' => 1,
                'puedeVerReporte' => 1,
                'puedeImprimir' => 1]);
        }
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
        $dato = Perfil::find($id);
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
        $actua = DB::table('perfil')->where('id', $id)->update(['nombre' => $request->nombre]);
        return response()->json(["mensaje" => "listo"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $actua = DB::table('perfil')->where('id', $id)->update(['eliminado' => 1]);
        return response()->json(["mensaje" => "listo"]);
    }

    public function listaperfil() {
        $actua = DB::table('perfil')->select('id', 'nombre')->where('eliminado', 0)->get('id', 'nombre');
        return response()->json($actua);
    }

}
