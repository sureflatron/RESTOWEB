<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TipoProducto;
use App\Http\Requests;
use DB;
use Session;
use Carbon\Carbon;
use Redirect;
use Storage;

class TipoProductoController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $tipo = DB::table('tipoproducto')->select('nombre', 'id')->where('eliminado', 0)->get();
        /*return response()->json($tipo);*/
        /*    $tipo = DB::select("
                SELECT DISTINCT(tipoproducto.id),tipoproducto.nombre 
                FROM tipoproducto INNER JOIN subtipoproducto 
                ON tipoproducto.id=subtipoproducto.idTipoProducto 
                WHERE tipoproducto.eliminado=0
                ORDER BY tipoproducto.id ASC
                ");        */
       // $tipo = DB::table('tipoproducto')->select('nombre', 'id')->where('eliminado', 0)->get();
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        if ($request->ajax()) {
            TipoProducto::create($request->all());
            return response()->json(["mensaje" => ""]);
        }
        return Redirect::to('/Categoria/');
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
        $dato = TipoProducto::find($id);

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
        $usuario = TipoProducto::find($id);

        $actua = DB::table('tipoproducto')->where('id', $id)->update(['nombre' => $request->nombre, 'imagen' => $request->imagen]);
        return response()->json(["mensaje" => "listo"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $eliminado = DB::table('tipoproducto')->where('id', $id)->update(['eliminado' => 1]);
        return response()->json(["mensaje" => "listo"]);
    }

    public function listarCategoria() {
        $otro=DB::table('tipoproducto')
                ->select('nombre','id','imagen')
                ->where('eliminado',0)->get();
        return response()->json($otro);
    }

    public function listarCategorias() {
        $otro = DB::select("SELECT DISTINCT tipoproducto.id,
                tipoproducto.nombre,
                tipoproducto.imagen
                from tipoproducto where eliminado = 0");
        return response()->json($otro);
    }

    public function proceduretest() {

        $otro = DB::select("CALL pro_producto(1)");

        return response()->json($otro);
    }
    
      public function GestionarSubcategoriarafa($id) {
        $idcategoria = $id;
        $nomCat;
        $nombre = DB::table('tipoproducto')
                ->select('nombre')->
                where('id', $idcategoria)
                ->where('eliminado', 0)->lists('nombre');
        foreach ($nombre as $key => $value) {
            $nomCat = $value;
        }
        return view('Categoria.Subcategoria', compact(['idcategoria', 'nomCat']));        
        //return view('Sucursal.Ciudad', compact(['idpais', 'nomPaiss']));
        }    

//CALL `pro_producto`(@p0); listarCategoria
//SELECT tipoproducto.id,tipoproducto.nombre,tipoproducto.imagen from tipoproducto INNER JOIN producto WHERE tipoproducto.eliminado=0 and producto.idTipoProducto=tipoproducto.id and producto.tipoproducto='Comida';
}
