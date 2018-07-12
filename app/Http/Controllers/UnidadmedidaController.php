<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Unidadmedida;
use DB;
use Session;
use Redirect;
use App\Http\Requests;

class UnidadmedidaController extends Controller
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
        if($request->ajax()){
      Unidadmedida::create($request->all());
          
         return response()->json(["mensaje"=>"Guardado exitoso"]);
           
        };
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
        $dato=Unidadmedida::find($id);
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
         $actua= DB::table('unidadmedida')->where('id', $id)->update(['nombre' =>$request->nombre,'abreviatura' =>$request->abreviatura]);
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
        $eliminar= DB::table('unidadmedida')->where('id', $id)->update(['eliminado' =>1]);
        return response()->json(["mensaje" => "listo"]);
    }
    
    public function listaunidadmedida(){
        $otro=DB::table('unidadmedida')->select('nombre','id','abreviatura')->where('eliminado',0)->get();
        return response()->json($otro);
    }

}
