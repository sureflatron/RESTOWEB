<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\LibroOrden;
class LibroOrdenController extends Controller
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

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $numeroActual=$request->nroInicio;
        $numero=$numeroActual-1;
       DB::table('libroorden')->insert(['idSucursal' => $request->idSucursal,'NIT' => $request->NIT,'nroAutorizacion' => $request->nroAutorizacion,'nroInicio' => $request->nroInicio,'nroFin' => $request->nroFin,'tipo' => $request->tipo,'fechaInicio' => $request->fechaInicio,'fechaFin' => $request->fechaFin,'estado' => $request->estado,'llave' => $request->llave,'nroActual' => $numero]);
 
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
                  $dato=LibroOrden::find($id);
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

         $actua= DB::table('libroorden')->where('id', $id)->update(['idSucursal' => $request->idSucursal,'NIT' => $request->NIT,'nroAutorizacion' => $request->nroAutorizacion,'nroInicio' => $request->nroInicio,'nroFin' => $request->nroFin,'tipo' => $request->tipo,'fechaInicio' => $request->fechaInicio,'fechaFin' => $request->fechaFin,'estado' => $request->estado,'llave' => $request->llave]);
        return response()->json($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
            $actua= DB::table('libroorden')->where('id', $id)->update(['eliminado' =>1]);
        return response()->json(["mensaje" => "listo"]);
    }

    public function listarlibroorden()
    {
   $productos=DB::table('libroorden')->select('libroorden.id','sucursal.nombre','libroorden.NIT','libroorden.nroAutorizacion','libroorden.nroInicio','libroorden.nroFin','libroorden.tipo','libroorden.fechaInicio','libroorden.fechaFin','libroorden.estado')
->join('sucursal', 'sucursal.id', '=', 'libroorden.idSucursal')
->where('libroorden.eliminado',0)
->get('id','nombre','NIT','nroAutorizacion','nroInicio','nroFin','tipo','fechaInicio','fechaFin','estado');

      return response()->json($productos);
    }

}
//SELECT sucursal.nombre,libroorden.NIT,libroorden.nroAutorizacion,libroorden.nroInicio,libroorden.nroFin,libroorden.tipo,libroorden.fechaInicio,libroorden.fechaFin,libroorden.estado FROM libroorden INNER JOIN sucursal WHERE libroorden.idSucursal=sucursal.id