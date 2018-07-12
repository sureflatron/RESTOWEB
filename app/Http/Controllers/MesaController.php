<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Mesa;
use Carbon\Carbon;
use App\Http\Requests;

class MesaController extends Controller
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

        DB::table('mesa')->insert(['numeromesa' => $request->nummesa,'ubicacion' => $request->ubicacion,'capacidad' => $request->capacidad,'estado' => $request->estado,'idSucual' => $request->sucursal]);
 
         
            //
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
           $dato=Mesa::find($id);
        return response()->json($dato);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     *
     */
    public function update(Request $request, $id)
    {
              $actua= DB::table('mesa')->where('id', $id)->update(['numeromesa' => $request->nummesa,'ubicacion' => $request->ubicacion,'capacidad' => $request->capacidad,'estado' => $request->estado,'idSucual' => $request->sucursal]);
       return response()->json($actua);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       DB::table('mesa')->where('id', '=', $id)->update(['eliminado'=>1]);
             return response()->json( $id);
    }

    public function listarmesa()
    {
     $otro=DB::select('CALL listarmesa();' );
 

    return response()->json($otro);
     }

    public function buscarmesa($id)
    {
 
   $otro=DB::select("CALL buscarmesa(?)", [$id]);

    return response()->json($otro);
     }
     // CALL `buscarmesa`(@p0);
    public function Buscarventaconmesa($idventa)
    {
 
   $otro=DB::select("CALL Buscarventaconmesa(?)", [$idventa]);

    return response()->json($otro);
     }

         public function Bucarmesaporventa($idmesa)
    {
 
   $otro=DB::select("CALL Bucarmesaporventa(?)", [$idmesa]);

    return response()->json($otro);
     }

public function Cambiarestadomesa($idmesa)
    {
 
   $otro=DB::select("CALL Cambiarestadomesa(?)", [$idmesa]);

    return response()->json("Mesa A ocupado");
     }
}
 