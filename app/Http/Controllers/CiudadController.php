<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ciudad;
use DB;
use Session;
use Redirect;
use App\Http\Requests;

class CiudadController extends Controller
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
               DB::table('ciudad')->insert(['nombre' => $request->nombre,'idPais' => $request->idPais]);
          
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
          $dato=Ciudad::find($id);
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
      $actua= DB::table('ciudad')->where('id', $id)->update(['nombre' =>$request->nombre ]);
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
         $actua= DB::table('ciudad')->where('id', $id)->update(['eliminado' =>1]);
        return response()->json(["mensaje" => "listo"]);
    }

        public function listarciudad($id)
    {
       $otro=DB::table('ciudad')->select('nombre','id' )->where('eliminado',0)->where('idPais',$id)->get();
    return response()->json($otro);

    }
}
