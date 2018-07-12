<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tipoingreso;
use DB;
use Session;
use Redirect;
use App\Http\Requests;

class TipoingresoController extends Controller
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
          DB::table('tipoingreso')->insert(['nombre' => $request->nombre]);
 
       
  
 
         
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
            $dato=Tipoingreso::find($id);
            
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
            $actua= DB::table('tipoingreso')->where('id', $id)->update(['nombre' =>$request->nombre]);
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
  $eliminado= DB::table('tipoingreso')->where('id', $id)->update(['eliminado' =>1]);
          return response()->json(["mensaje" => "listo"]);
    }
      public function listatipoingreso()
    {

          $otro=DB::table('tipoingreso')->select('nombre','id')->where('eliminado',0)->get();
    
        return response()->json($otro);
         //  return "ingreso";
    }
}
