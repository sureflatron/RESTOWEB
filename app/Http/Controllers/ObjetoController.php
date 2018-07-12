<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Objeto;
use DB;
use Session;
use Redirect;
use App\Http\Requests;

class ObjetoController extends Controller {

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
        DB::table('objeto')->insert(['nombre' => $request->nombre,
            'tipoObjeto' => $request->tipoObjeto,
            'urlObjeto' => $request->urlObjeto,
            'habilitado' => $request->habilitado,
            'visibleEnMenu' => $request->visibleEnMenu,
            'idModulo' => $request->modulo]);
        $ultimo = DB::table('objeto')->select('id')->orderby('id', 'DESC')->take(1)->get('id');
        $objeto;
        foreach ($ultimo as $ultimo) {
            $objeto = $ultimo->id;
        }
        $perfiles = DB::table('perfil')->select('id')->where('eliminado', 0)->get('id');
        foreach ($perfiles as $perfiles) {
            $perfiles = $perfiles->id;
            DB::table('perfilobjeto')->insert(['idPerfil' => $perfiles,
                'idObjeto' => $objeto,
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $dato = Objeto::find($id);
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
        $actua = DB::table('objeto')->where('id', $id)->update(['nombre' => $request->nombre, 'tipoObjeto' => $request->tipoObjeto, 'urlObjeto' => $request->urlObjeto, 'habilitado' => $request->habilitado, 'visibleEnMenu' => $request->visibleEnMenu, 'idModulo' => $request->modulo]);
        return response()->json(["mensaje" => "listo"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $actua = DB::table('objeto')->where('id', $id)->update(['eliminado' => 1]);
        DB::table('perfilobjeto')->where('idObjeto', $id)->delete();
        return response()->json(["mensaje" => "listo"]);
    }

    public function listarobjeto() {
        $actua = DB::table('objeto')->select('objeto.id', 'objeto.nombre', 'objeto.tipoObjeto', 'objeto.habilitado', 'objeto.visibleEnMenu', 'modulo.nombre as nommodulo')
                        ->join('modulo', 'modulo.id', '=', 'objeto.idModulo')->where('objeto.eliminado', 0)->get('id', 'nombre', 'tipoObjeto', 'habilitado', 'visibleEnMenu', 'nommodulo');

        return response()->json($actua);
    }

}
