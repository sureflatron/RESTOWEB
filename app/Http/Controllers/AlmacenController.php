<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Redirect;
use DB;
use App\Almacen;
use App\Http\Requests;

class AlmacenController extends Controller {

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
        DB::table('almacen')->insert(['nombre' => $request->nombre, 'idsucursal' => $request->sucursal, 'idEmpleado' => $request->responsable]);


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
        $dato = Almacen::find($id);
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
        $actua = DB::table('almacen')->where('id', $id)->update(['nombre' => $request->nombre, 'idsucursal' => $request->sucursal, 'idEmpleado' => $request->responsable]);
        return response()->json(["mensaje" => "listo"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $actua = DB::table('almacen')->where('id', $id)->update(['eliminado' => 1]);
        return response()->json(["mensaje" => "listo"]);
    }

    public function listaralmacen() {
        $results = DB::select("select almacen.id, almacen.nombre,sucursal.nombre as sucursal,  empleado.nombre as nombreempleado FROM almacen,empleado,sucursal where almacen.idEmpleado = empleado.id and almacen.eliminado = 0 and almacen.idsucursal = sucursal.id");

//         $results=DB::table('almacen')->select('almacen.nombre','almacen.id','sucursal.nombre as sucursal' )
//         ->join('sucursal','sucursal.id','=','almacen.idsucursal')
//         ->where('almacen.eliminado',0)->get();
        return response()->json($results);
    }

    public function listarAlmacenCombo() {
        $otro = DB::select("select almacen.id, almacen.nombre,sucursal.nombre as sucursal FROM almacen,sucursal where almacen.eliminado = 0 and almacen.idsucursal = sucursal.id");
        return response()->json($otro);
    }

    public function listaralmacensucursal($idpunto) {
        $otro = DB::select("select
                        almacen.`id`,
                        almacen.`nombre`
                from almacen, `puntoventa`, sucursal
                where puntoventa.`idSucursal` = sucursal.`id` 
                and almacen.`idsucursal` = sucursal.`id` 
                and puntoventa.`id` = ?", [$idpunto]);
    return response()->json($otro);           
    }
    
    public function listaralmacenesmaxmin() {
        $otro = DB::select("select
                        almacen.`id`,
                        almacen.`nombre`
                from almacen
                where almacen.eliminado=0");
        return response()->json($otro);             }
    
    public function listaralmaccendelasucursal($id){
        $otro = DB::select("SELECT DISTINCT
                        almacen.`id`,
                        almacen.`nombre`
                FROM almacen, sucursal
                WHERE almacen.`idsucursal` = sucursal.`id` 
                AND almacen.eliminado = 0 
                AND sucursal.`id` =  ?", [$id]);
        return response()->json($otro);
    }
    
    public function listarAlmacenS($sucursal) {
        $otro = DB::select("select almacen.id, almacen.nombre,sucursal.nombre as sucursal FROM almacen,sucursal where almacen.eliminado = 0 and almacen.idsucursal = sucursal.id and sucursal.id=$sucursal");
        return response()->json($otro);
    }
}
