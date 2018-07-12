<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ingredienteproducto;
use DB;
use Session;
use Redirect;
use App\Http\Requests;

class IngredienteproductoController extends Controller
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
DB::table('ingredienteproducto')->insert(['id' => $request->id,'idIngrediente' => $request->idIngrediente,'idUnidadMedida' => $request->idUnidadMedida,'cantidad' => $request->cantidad,'costo' => $request->costo  ]);
return response()->json(["mensaje"=>$request->cantidad]);
           
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
        //
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
        $actua= DB::table('ingredienteproducto')->where('eliminar', $id)->update(['idUnidadMedida' =>$request->unidad,'cantidad' =>$request->cantidad,'costo' =>$request->costo]);
         return response()->json( $actua);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
 DB::table('ingredienteproducto')->where('eliminar', '=', $id)->delete();
             return response()->json( $id);
    }
     
    public function listarparaeditar($id){

$datooo=DB::table('ingredienteproducto')
->select('producto.id as numingredeinte','producto.nombre as elingrediente','unidadmedida.id as numunidada','unidadmedida.abreviatura as launidad','ingredienteproducto.cantidad','ingredienteproducto.eliminar','ingredienteproducto.costo')
->join('producto', 'producto.id', '=', 'ingredienteproducto.idIngrediente')
->join('unidadmedida', 'unidadmedida.id', '=', 'ingredienteproducto.idUnidadMedida')
->where('ingredienteproducto.eliminar',$id)
->get('numingredeinte','elingrediente','numunidada','launidad','cantidad' ,'costo');
 


         return response()->json( $datooo);
    }
    public function listarparaeditars($id){

$datooo=DB::table('ingredienteproducto')
->select('ingrediente.id as numingredeinte','ingrediente.nombre as elingrediente','unidadmedida.id as numunidada','unidadmedida.abreviatura as launidad','ingredienteproducto.cantidad','ingredienteproducto.eliminar','ingredienteproducto.costo')
->join('ingrediente', 'ingrediente.id', '=', 'ingredienteproducto.idIngrediente')
->join('unidadmedida', 'unidadmedida.id', '=', 'ingredienteproducto.idUnidadMedida')
->where('ingredienteproducto.eliminar',$id)
->get('numingredeinte','elingrediente','numunidada','launidad','cantidad' ,'costo');
 


         return response()->json( $datooo);
    }
}
