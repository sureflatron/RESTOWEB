<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ComposicionProducto;
use DB;
use Session;
use Redirect;
use App\Http\Requests;

class ComposicionProductoController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //
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
        $validar = self::validardosveces($request->id, $request->idcomposicion);
        if ($validar == 0) {
            if ($request->ajax()) {
                DB::table('composicionproducto')
                        ->insert(['idProducto' => $request->id,
                            'idcomposicion' => $request->idcomposicion,
                            'cantidad' => $request->cantidad
                ]);
                return response()->json(["mensaje" => "Stock no superado del Producto"]);
            };
        } else {
            return response()->json(["mensaje" => "0"]);
        }
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
        $dato = ComposicionProducto::find($id);
        $datoproducto = DB::table('composicionproducto')->join('producto', 'producto.id', '=', 'composicionproducto.idProducto')
                        ->select('producto.nombre', 'producto.id')->lists('nombre', 'id');

        return view('PanelAdmin.ActualizarCompo', ['dato' => $dato], compact('datoproducto'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        // $usuario=ComposicionProducto::find($id);
        /// $actua= DB::table('composicionproducto')->where('id', $id)->update(['cantidad' =>$request->cantidad,'idProducto' =>$request->dato]);
        $actua = DB::table('composicionproducto')->where('id', $id)->update(['cantidad' => $request->cantidad]);
        return response()->json($actua);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        DB::table('composicionproducto')->where('id', '=', $id)->delete();
        return response()->json($id);
    }

    function composicionyproducto($id) {
        $otro = DB::table('composicionproducto')->select('producto.nombre', 'producto.descripcion', 'composicionproducto.cantidad', 'composicionproducto.id')
                ->join('producto', 'producto.id', '=', 'composicionproducto.idcomposicion')
                ->where('composicionproducto.idProducto', $id)
                ->get('cantidad', 'nombre', 'id', 'descripcion');
        return response()->json($otro);
    }

    function editarcomposicionproducto($id) {
        $otro = DB::table('composicionproducto')->select('producto.nombre', 'composicionproducto.cantidad', 'composicionproducto.id')
                ->join('producto', 'producto.id', '=', 'composicionproducto.idcomposicion')
                ->where('composicionproducto.id', $id)
                ->get('cantidad', 'nombre', 'id');

        return response()->json($otro);
    }

    public function validardosveces($idproducto, $idcomposicion) {
        $datos = 0;
        $results = DB::select("
         SELECT IFNULL(
        (SELECT COUNT(composicionproducto.idcomposicion) 
        FROM composicionproducto 
        WHERE idProducto=? AND idcomposicion=?),0)>0 as repite
        ", [$idproducto, $idcomposicion]);

        foreach ($results as $key => $value) {
            $datos = $value->repite;
        }
        if ($datos > 0) {
            $datos = 1;
        } else {
            $datos = 0;
        }
        return $datos;
    }

}
