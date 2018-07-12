<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Cargo;
use App\Http\Requests;

class CargoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return "hola";
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
     DB::table('cargo')->insert(['nombre' => $request->nombre]);
          
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
                  $dato=Cargo::find($id);
            
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
          $Actalizado= DB::table('cargo')->where('id', $id)->update(['nombre' =>$request->nombre]);
          return response()->json(["mensaje" => $Actalizado]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         $eliminado= DB::table('cargo')->where('id', $id)->update(['eliminado' =>1]);
          return response()->json(["mensaje" => "listo"]);
    }

    public function listadecargoempleado(){
 $cargo=DB::table('cargo')->select('nombre','id')->where('eliminado',0)->get('nombre','id');
     return response()->json($cargo);
    }
}
