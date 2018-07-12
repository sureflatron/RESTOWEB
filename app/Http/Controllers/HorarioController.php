<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Horario;
use DB;
use Session;
use Redirect;
use App\Http\Requests;

class HorarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
 DB::table('horario')->insert(['horaEntrada' => $request->horaEntrada,'horaSalida' => $request->horaSalida,'idTurno' => $request->id,'dia' => $request->dia]);
          
        return response()->json($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $dato=Horario::find($id);
        return response()->json($dato);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {  

     $actua= DB::table('horario')->where('id', $id)->update(['horaEntrada' =>$request->achoraentrada,'horaSalida' =>$request->achorasalida,'dia' => $request->dia]);
      
        return response()->json(["actualizado" => $request->all()]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
  $actua= DB::table('horario')->where('id', $id)->update(['eliminado' =>1]);
        return response()->json(["mensaje" => "listo"]);
    }

    public function listarhorario($id)
    {
        $horario=DB::table('horario')->select('horario.id','horario.horaEntrada','horario.horaSalida','dia')
        ->join('turno', 'turno.id', '=', 'horario.idTurno')
        ->where('turno.id',$id)->where('horario.eliminado',0)->get('horaEntrada','horaSalida','id','dia');
        return response()->json($horario);
    }


}
