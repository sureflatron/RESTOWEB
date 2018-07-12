<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Perfilobjeto;
use App\Http\Requests;

class PerfilobjetoController extends Controller {

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
        DB::table('perfilobjeto')->insert(['idPerfil' => $request->idperfil, 'idObjeto' => $request->idobjeto, 'puedeGuardar' => $request->guardar, 'puedeModificar' => $request->modificar, 'puedeEliminar' => $request->eliminar, 'puedeListar' => $request->listar, 'puedeVerReporte' => $request->verreporte, 'puedeImprimir' => $request->Imprimir]);
        //, 'nombre', 'tipoObjeto', 'urlObjeto', 'habilitado', 'visibleEnMenu', 'idModulo',
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
    public function edit(Request $request, $id) {
//        $dato=Perfilobjeto::find($id);
        $dato = DB::select("SELECT perfilobjeto.id,
                        (SELECT objeto.nombre
                            FROM objeto
                            where objeto.id= perfilobjeto.idObjeto) as nombre,
                        perfilobjeto.idObjeto,
                        perfilobjeto.idPerfil,
                        perfilobjeto.puedeEliminar,
                        perfilobjeto.puedeGuardar,
                        perfilobjeto.puedeImprimir,
                        perfilobjeto.puedeListar,
                        perfilobjeto.puedeModificar,
                        perfilobjeto.puedeVerReporte
                    FROM perfilobjeto
                    WHERE perfilobjeto.id = ?
                   ", [$id]);
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
        $actua = DB::table('Perfilobjeto')->where('id', $id)->update(['puedeGuardar' => $request->guardar, 'puedeModificar' => $request->modificar, 'puedeEliminar' => $request->eliminar, 'puedeListar' => $request->listar, 'puedeVerReporte' => $request->verreporte, 'puedeImprimir' => $request->Imprimir]);
        return response()->json(["actualizado" => $request->all()]);
    }

//idperfil: idperfil,idobjeto:idobjeto,guardar:guardar,modificar:modificar,eliminar:eliminar,listar:listar,verreporte:verreporte,Imprimir:Imprimir
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $actua = DB::table('perfilobjeto')->where('id', $id)->delete();

        return response()->json(["mensaje" => "listo"]);
    }

    public function listarperfilobjeto($id) {
        $perfilobjeto = DB::table('perfilobjeto')->select('perfilobjeto.id as idper', 'perfilobjeto.idPerfil as id', 'perfilobjeto.idObjeto as ids', 'objeto.nombre', 'perfilobjeto.puedeGuardar', 'perfilobjeto.puedeModificar', 'perfilobjeto.puedeEliminar', 'perfilobjeto.puedeListar', 'perfilobjeto.puedeVerReporte', 'perfilobjeto.puedeImprimir')
                ->join('objeto', 'objeto.id', '=', 'perfilobjeto.idObjeto')
                ->where('perfilobjeto.idPerfil', $id)
                ->get('idper', 'id', 'ids', 'nombre', 'puedeGuardar', 'puedeModificar', 'puedeEliminar', 'puedeListar', 'puedeVerReporte', 'puedeImprimir');
        return response()->json($perfilobjeto);
    }

}
