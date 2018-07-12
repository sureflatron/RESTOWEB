<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Turno;
use DB;
use Session;
use Redirect;
use App\Http\Requests;

class TurnoController extends Controller
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
        DB::table('turno')->insert(['nombre' => $request->nombre,'minutosTolerancia' => $request->minutosTolerancia]);
          
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
       $dato=Turno::find($id);
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
      $actua= DB::table('turno')->where('id', $id)->update(['nombre' =>$request->nombre,'minutosTolerancia' =>$request->minutosTolerancia]);
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
  $actua= DB::table('turno')->where('id', $id)->update(['eliminado' =>1]);
        return response()->json(["mensaje" => "listo"]);
    }
    

       public function listaturno()
    {
 $otro=DB::table('turno')->select('nombre','id','minutosTolerancia')->where('eliminado',0)->get();
    return response()->json($otro);
    }


   public function gestionarhorario($id)
    {
        $idturno=$id;
        $turno;
        $nombre=DB::table('turno')->select('nombre')->where('id',$id)->where('eliminado',0)->lists('nombre');
        foreach ($nombre  as $key => $value) {
            $turno=$value;
        }
      
      
        return  view ('Turno.Gestiondehorario', compact(['idturno','turno'  ]));
    }
}
