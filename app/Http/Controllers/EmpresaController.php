<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use DB;
use Session;
use Carbon\Carbon;
use Redirect;
use Storage;

class EmpresaController extends Controller
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
    //actividad  propietario 
        DB::table('empresa')->insert(['nombre' => $request->nombre,'imagen' => $request->imagen,'web' => $request->web,'correo' => $request->correo,'propietario' => $request->propietario,'actividad' => $request->actividad, 'venderSinStock' => $request->venderSinStock]);
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
        $dato=DB::table('empresa')->select('id','nombre','imagen','web','correo','actividad','propietario','venderSinStock')->where('id',$id)->where('eliminado',0)
        ->get();
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
        $actua= DB::table('empresa')->where('id', $id)->update(['nombre' => $request->nombre,'imagen' => $request->imagen,'web' => $request->web,'correo' => $request->correo,'propietario' => $request->propietario,'actividad' => $request->actividad,'venderSinStock' => $request->venderSinStock]);
        return response()->json(["mensaje" => "listo"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $actua= DB::table('empresa')->where('id', $id)->update(['eliminado' =>1]);
        return response()->json(["mensaje" => "listo"]);
    }

    public function listarEmpresa()
    {
        //  $otro=DB::select("SELECT  id, nombre, imagen, web, correo from empresa  WHERE  eliminado='0'");
        $datos=DB::table('empresa')->select('id','nombre','imagen','web','correo' ,'actividad','propietario','venderSinStock')->where('eliminado',0)
        ->get();
        return response()->json($datos);
    }

}
