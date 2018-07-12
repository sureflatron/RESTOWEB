<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SubtipoProducto;
use App\Http\Requests;
use DB;
use Session;
use Carbon\Carbon;
use Redirect;
use Storage;


class SubtipoProductoController extends Controller
{

       public function index() {
        $tipo = DB::table('subtipoproducto')->select('nombre', 'id')->where('eliminado', 0)->get();
        return response()->json($tipo);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

   

    
    public function store(Request $request) {
        if ($request->ajax()) {
            SubtipoProducto::create($request->all());

            return response()->json(["mensaje" => ""]);
        }
        //return Redirect::to('/Categoria/');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
       $dato = SubtipoProducto::find($id);

       return response()->json($dato);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //$usuario = SubtipoProducto::find($id);        
        $actua = DB::table('subtipoproducto')
                ->where('id', $id)
                ->update([
                    'nombre' => $request->nombre,
                    'imagen' => $request->imagen
                        ]);
         return response()->json(["mensaje" => "listo"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        
       $eliminado = DB::table('subtipoproducto')
               ->where('id', $id)
               ->update(['eliminado' => 1]);
        return response()->json(["mensaje" => "listo"]);
    }

 
    
    
    public function listarsubcategoria($id)
    {
       $otro=DB::table('subtipoproducto')
               ->select('nombre','id','imagen' )
               ->where('eliminado',0)
               ->where('idtipoproducto',$id)->get();
    return response()->json($otro);

    }
    
    public function listarsubcategorias($idcategoria)
    {
        $otro = DB::select("
                SELECT subtipoproducto.id,subtipoproducto.nombre
                FROM tipoproducto INNER JOIN subtipoproducto
                ON tipoproducto.id=subtipoproducto.idtipoproducto 
                AND subtipoproducto.eliminado=0 AND tipoproducto.id=$idcategoria");
        //dd($otro);
        /*
           if ($otro==null)
              {             
                $otro= DB::select("
                SELECT tipoproducto.id,tipoproducto.nombre
                FROM tipoproducto 
                WHERE tipoproducto.eliminado=0 AND tipoproducto.id=$idcategoria");
               // echo "esta vacio";
              }
         */
        return response()->json($otro);
    }
    public function listarSubTipoProducto($categoria)
    {
        /*$otro = DB::select("
                select max(distinct(tipoproducto.id)) as  id from tipoproducto 
                inner join subtipoproducto 
                on tipoproducto.id=subtipoproducto.idTipoProducto 
                 where tipoproducto.eliminado=0 and subtipoproducto.eliminado=0 
                 order by tipoproducto.id asc",[$categoria]);
        $categoria="";
        foreach ($otro as $key => $value) 
        {$categoria=$value->id;}*/
        $existe;
                $otro = DB::select("
                SELECT subtipoproducto.id,subtipoproducto.nombre
                FROM subtipoproducto
                WHERE subtipoproducto.eliminado=0 AND subtipoproducto.idTipoProducto=?",[$categoria]);
      foreach ($otro as $key => $value) 
        {$existe=$value->id;}
        
        if(!$existe>0 || $otro==null || $existe==null || $otro=="")
         { $otro=0; }        
         
        return response()->json($otro);
    }
}
