<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ingrediente;
use DB;
use Session;
use Redirect;
use App\Http\Requests;

class IngredienteController extends Controller
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
      Ingrediente::create($request->all());
          
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
         $dato=Ingrediente::find($id);
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
      $actua= DB::table('ingrediente')->where('id', $id)->update(['nombre' =>$request->nombre]);
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
    $eliminar= DB::table('ingrediente')->where('id', $id)->update(['eliminado' =>1]);
             return response()->json(["mensaje" => "listo"]);
    }


      public function listaingrediente(){
$otro=DB::table('ingrediente')->select('nombre','id')->where('eliminado',0)->get();
    return response()->json($otro);

    }
      public function unidadyingrediente($id)
    {
$otro=DB::table('ingredienteproducto')->select('ingrediente.nombre','unidadmedida.abreviatura','ingredienteproducto.cantidad','ingredienteproducto.id','ingredienteproducto.eliminar','ingredienteproducto.costo')
->join('ingrediente', 'ingrediente.id', '=', 'ingredienteproducto.idIngrediente')
->join('unidadmedida', 'unidadmedida.id', '=', 'ingredienteproducto.idUnidadMedida')
->where('ingredienteproducto.id',$id)
->get('nombre','abreviatura','cantidad','id','eliminar','costo');

//response()->json($otro);listaconunidadyingredientes
    return $otro;

    }

     public function prueba($id){
    $results = DB::select("SELECT ingrediente.nombre,unidadmedida.abreviatura,ingredienteproducto.cantidad,ingredienteproducto.id,ingredienteproducto.eliminar,ingredienteproducto.costo ,(SELECT sum(ingredienteproducto.costo) from ingredienteproducto WHERE ingredienteproducto.id=?) as total

from ingredienteproducto INNER JOIN ingrediente INNER JOIN unidadmedida WHERE  ingrediente.id=ingredienteproducto.idIngrediente and unidadmedida.id=ingredienteproducto.idUnidadMedida and ingredienteproducto.id=?", [$id,$id]);
      return response()->json($results);
 }
    public function listaconunidadyingredientes($id){
    $results = DB::select("SELECT producto.nombre, producto.descripcion,unidadmedida.abreviatura,ingredienteproducto.cantidad,ingredienteproducto.id,ingredienteproducto.eliminar,ingredienteproducto.costo ,(SELECT sum(ingredienteproducto.costo) 
        from ingredienteproducto WHERE ingredienteproducto.id=?) as total
        from ingredienteproducto 
        INNER JOIN producto 
        INNER JOIN unidadmedida 
        WHERE  producto.id=ingredienteproducto.idIngrediente 
        and unidadmedida.id=ingredienteproducto.idUnidadMedida 
        and ingredienteproducto.id=?", [$id,$id]);
      return response()->json($results);
 }
 
}
 