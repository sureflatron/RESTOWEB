<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Carbon\Carbon;
use Redirect;
use DB;
use App\Http\Requests;

class DescuentoController extends Controller {

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
        DB::table('tipodescuento')->insert([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'descuento' => $request->descuento,
            'fechaFin' => $request->fechaFin,
            'fechaInicio' => $request->fechaInicio,
            'eliminado' => 0]);
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
        $otro = DB::table('tipodescuento')
                ->select('id', 'nombre', 'descripcion', 'descuento', 'fechaFin', 'fechaInicio')
                ->where('id', $id)
                ->get();
        return response()->json($otro);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $actua = DB::table('tipodescuento')->where('id', $id)->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'descuento' => $request->descuento,
            'fechaFin' => $request->fechaFin,
            'fechaInicio' => $request->fechaInicio]);
        return response()->json(["mensaje" => "listo"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $dato = DB::table('tipodescuento')
                ->where('id', $id)
                ->update(['eliminado' => 1]);
        return response()->json($dato);
    }

    public function listardescuento() {
        $otro = DB::table('tipodescuento')
                ->select('id', 'nombre', 'descripcion', 'descuento', 'fechaFin', 'fechaInicio')
                ->where('eliminado', 0)
                ->get();
        return response()->json($otro);
    }

    public function listardescuentos() {
        $otro = DB::select("SELECT
                tipodescuento.id,
                tipodescuento.nombre,
                tipodescuento.descuento
            FROM tipodescuento
            WHERE eliminado = 0
            AND NOW() BETWEEN tipodescuento.fechaInicio AND tipodescuento.fechaFin");
        return response()->json($otro);
    }

}
