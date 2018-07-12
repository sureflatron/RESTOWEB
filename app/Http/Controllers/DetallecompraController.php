<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Detallecompra;
use DB;
use App\Http\Requests;

class DetallecompraController extends Controller {

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
     * @param  \Illuminate\Http\Request  $request unidadmedida
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        if ($request->ajax()) {
            $validar = self::validardosveces($request->idingrediente, $request->idcompra);
            $str=null;
               $productointerfaz=$request->idingrediente;
               $cantidadinterfaz=$request->cantidad;
               
               $idalmacen=null;$stockproductoalma=null;$stockmax=null;$stockmin=null;
               //para sacar el almacen de donde guardara el producto
               $almacen = DB::select("SELECT compra.idAlmacen FROM compra 
                                    WHERE compra.id=?", [$request->idcompra]);    
               foreach ($almacen as $key => $value) 
                       {$idalmacen = $value->idAlmacen;} 
                    
               //para sacar el stock del prod actual en dicho almacen        
            $stockprod = DB::select("SELECT IFNULL(SUM(v_stockalmacensucursal.stock),0) AS cantidad
                       FROM v_stockalmacensucursal 
                        WHERE v_stockalmacensucursal.idproducto=? 
                        AND v_stockalmacensucursal.idalmacen =?", [$productointerfaz, $idalmacen]);
                  foreach ($stockprod as $key => $value) 
                       {$stockproductoalma = $value->cantidad;} 
               //para sacar la existencia maxima de seguridad y restringirlo con eso
                        $anioactual=date("Y");
                  $productomaxmin= DB::select("SELECT Emn AS stockMin,Emx AS stockMax,IFNULL(COUNT(id),0) AS existelaotratabla
                                    FROM productominmaxalmacen
                                    WHERE idProducto=? AND idAlmacen=?  AND anio=?
                            ",[$productointerfaz,$idalmacen,$anioactual]);
                  $existelaotratabla=null;
                  foreach ($productomaxmin as $key => $value) 
                    {$existelaotratabla=$value->existelaotratabla;
                     $stockmax=$value->stockMax;
                     $stockmin=$value->stockMin;
                    }
                    if($existelaotratabla==0)
                    {
                        $stockMaxproducto=DB::select("SELECT producto.id,producto.stockMin,producto.stockMax
                                         FROM producto WHERE producto.id=?",[$productointerfaz]); 
                    foreach ($stockMaxproducto as $key => $value) 
                       {$stockmax = $value->stockMax;$stockmin=$value->stockMin;}
                    }  
                    
                    $sumastockcantidad= $cantidadinterfaz + $stockproductoalma;                    
            if (($validar == 0) && ($stockmax>=$sumastockcantidad))
                {
                DB::table('detallecompra')->insert([
                    'idcompra' => $request->idcompra,
                    'idProducto' => $request->idingrediente,
                    'cantidad' => $request->cantidad,
                    'costo' => $request->costo,
                    'total' => $request->total,
                    'idUnidadMedida' => $request->unidadmedida]);
                // $otro=DB::table('compra')->select('idProveedor')->where('id',  $request->idcompra)->lists('idProveedor');
                $dato;
                $numero;
                $results = DB::select("SELECT
                    SUM(detallecompra.total) AS total
                    FROM detallecompra
                    INNER JOIN compra
                    WHERE compra.id=detallecompra.idcompra 
                    AND compra.id=?", [$request->idcompra]);
                foreach ($results as $key => $value) {
                    $dato = $value;
                }
                foreach ($dato as $keys => $values) {
                    $numero = $values;
                }
                DB::table('compra')->where('id', $request->idcompra)->update(['total' => $numero]);
                if($stockmax==$sumastockcantidad)
                return response()->json(["mensaje" => "Producto agregado con exito ; StockMax a tope no puede comprar mas productos"]);
                else 
                return response()->json(["mensaje" => "Producto agregado con exito a la Compra"]);
                 } else {
                if($validar==1)
                return response()->json(["mensaje" => "El producto ya esta en la compra. Seleccione Otro"]);
                else {
                     $stockMax= "$stockmax";   
                     $stockproductoalma="$stockproductoalma";
                return response()->json(["mensaje" => "El producto sobrepaso su existencia max.de Seguridad(".$stockMax.") su stock actual es de ".$stockproductoalma."  Disminuya su compra"]);        
                   //  return response()->json(["mensaje" => "El producto sobrepaso su existencia max.".$str." de Disminuya su compra"]);        
                     }              
                 }
        }
    }

    public function validardosveces($idProducto, $idcompra) {
        $datos = 0;
        $results = DB::select("SELECT count(detallecompra.idProducto) as repite
                FROM detallecompra
                INNER JOIN compra on compra.id = detallecompra.idcompra and compra.estado = 0
                WHERE detallecompra.idProducto = ? and compra.id = ?
                GROUP BY detallecompra.idProducto
                HAVING count(*) >0", [$idProducto, $idcompra]);
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $dato = DB::table('detallecompra')->select('detallecompra.id', 'detallecompra.idProducto', 'detallecompra.cantidad', 'detallecompra.costo', 'producto.nombre')
                        ->join('producto', 'producto.id', '=', 'detallecompra.idProducto')
                        ->where('detallecompra.id', $id)->get('id', 'idProducto', 'cantidad', 'costo');
        return response()->json($dato);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $actua = DB::table('detallecompra')
                ->where('id', $id)
                ->update([
            'cantidad' => $request->cantidad,
            'costo' => $request->costo,
            'total' => $request->total]);
        $results = DB::select("SELECT 
                SUM(detallecompra.total) AS total 
                FROM detallecompra
                INNER JOIN compra
                WHERE compra.id = detallecompra.idcompra
                AND compra.id=?", [$request->idcompra]);
        $dato;
        $numero;
        foreach ($results as $key => $value) {
            $dato = $value;
        }
        foreach ($dato as $keys => $values) {
            $numero = $values;
        }
        DB::table('compra')->where('id', $request->idcompra)->update(['total' => $numero]);
        //  return response()->json(["mensaje" => "listo"]);
        return response()->json($numero);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $iddelacompra = "";
        $iddelacompras = "";
        $total = "";
        $laconsulta = DB::table('detallecompra')->where('id', $id)->delete();
        $results2 = DB::select("SELECT detallecompra.idcompra from detallecompra WHERE detallecompra.id=?", [$id]);
        foreach ($results2 as $key => $value) {
            $iddelacompras = $value->idcompra;
        }
        $results = DB::select("SELECT detallecompra.idcompra,(SELECT sum(detallecompra.total) from detallecompra  WHERE detallecompra.idcompra=?) as total 
                  from detallecompra 
                  INNER JOIN producto   
                  WHERE  detallecompra.idProducto=producto.id 
                  and detallecompra.idcompra=?", [$iddelacompras, $iddelacompras]);
        foreach ($results as $key => $value) {
            $iddelacompra = $value->idcompra;
            $total = $value->total;
        }
        $dato = DB::table('compra')->where('id', $iddelacompra)->update(['total' => $total]);
        return response()->json($iddelacompras);
    }

    public function elimnars(Request $request) {
        $iddelacompra = "";
        $laconsulta = DB::table('detallecompra')->where('id', $request->idetallecompra)->delete();
        $results = DB::select("SELECT detallecompra.idcompra,(SELECT sum(detallecompra.total) from detallecompra  WHERE detallecompra.idcompra=?) as total 
                  from detallecompra 
                  INNER JOIN producto   
                  WHERE  detallecompra.idProducto=producto.id 
                  and detallecompra.idcompra=?", [$request->idcompras, $request->idcompras]);
        $total = "";
        foreach ($results as $key => $value) {
            $total = $value->total;
        }
        $dato = DB::table('compra')->where('id', $request->idcompras)->update(['total' => $total]);
        return response()->json($results);
    }
    
    

    public function listarcontotal($id) {
        $results = DB::select(
                        "SELECT detallecompra.id,producto.nombre,producto.descripcion,(select marca.nombre from marca where producto.idMarca = marca.id) as marca, producto.color, producto.tamano as talla,detallecompra.cantidad,detallecompra.costo,
    (SELECT sum(detallecompra.total)
        from detallecompra 
          WHERE detallecompra.idcompra=?) as total,
    detallecompra.total as subtotal 
    from detallecompra 
    INNER JOIN producto
    WHERE  detallecompra.idProducto=producto.id and detallecompra.idcompra=?", [$id, $id]);
        return response()->json($results);
    }

    public function pruebas($id) {
        $productos = DB::table('detallecompra')->select('id', 'idingrediente', 'cantidad', 'costo')->where('id', $id)->get();
        return response()->json($productos);
    }
    
    protected function validarstockmaximocompra($idingrediente,$idcompra,$cantidad)
        {
        $cantidadminima_DATO;
        $stockalmaproducto2_DATO;
        $stockMax2producto_DATO;$stockMin2producto_DATO;
        $stockParcial_DATO;      
                                                                          
         $idalmacen_OBJ=DB::select("SELECT compra.idAlmacen FROM compra where id=?",[$idcompra]);          
         $idalmacen_DATO=$idalmacen_OBJ[0]->idAlmacen;
                //////////////
                  $anioactual=date("Y");
                  $productomaxmin= DB::select("SELECT Emn AS stockMin,Emx AS stockMax,IFNULL(COUNT(id),0) AS existelaotratabla
                                    FROM productominmaxalmacen
                                    WHERE idProducto=? AND idAlmacen=?  AND anio=?
                            ",[$idingrediente,$idalmacen_DATO,$anioactual]);
                  $existelaotratabla=null;
                  foreach ($productomaxmin as $key => $value) 
                    {$existelaotratabla=$value->existelaotratabla;
                     $stockMax2producto_DATO=$value->stockMax;
                     $stockMin2producto_DATO=$value->stockMin;
                    }
                    if($existelaotratabla==0)
                    {
                 $stockMinproducto_OBJ = DB::select("SELECT pp.id,pp.stockMax,pp.stockMin
                                    FROM  producto pp 
                                    WHERE pp.id=?", [$idingrediente]);
                  foreach ($stockMinproducto_OBJ as $key => $value) 
                 { $stockMax2producto_DATO = $value->stockMax;  
                   $stockMin2producto_DATO=$value->stockMin; }  
                    }
                
               ///////////////
         
         $stockalmaproducto_OBJ=DB::select("SELECT v_stockalmacensucursal.stock as cantidad
                       FROM v_stockalmacensucursal
                       WHERE v_stockalmacensucursal.idproducto = ? 
                       AND v_stockalmacensucursal.idalmacen = ?", [$idingrediente,$idalmacen_DATO]);                    
                    foreach ($stockalmaproducto_OBJ as $key => $value) 
                       {$stockalmaproducto2_DATO = $value->cantidad;}
                       
        $stockParcial_DATO=$stockalmaproducto2_DATO+$cantidad;
                    if($stockParcial_DATO>=$stockMax2producto_DATO)
                    {
                        $stockMax2producto_DATO=(string)$stockMax2producto_DATO;
                        $stockParcial_DATO=(string)$stockParcial_DATO;
                        $nombre_OBJ=DB::select("SELECT pp.nombre
                FROM  producto pp 
                WHERE pp.id=?",[$idingrediente]);
                        $nombre2_DATO=$nombre_OBJ[0]->nombre;
                       return response()->json(["mensaje" => "Compra del producto: ".$nombre2_DATO."("."stockParcial: ".$stockParcial_DATO.")quedo por encima o igual de su stock Max. de Seguridad("."StockMax: ".$stockMax2producto_DATO.") No Puede Comprar mas productos Que su Stock Max"]);
                    }else
                    {
                          return response()->json(["mensaje" => "ninguno"]);
                    }
        }
        
       public function eliminarordencompra(Request $request) {
        $iddelacompra = "";
        $laconsulta = DB::table('detallecompra')->where('idcompra', $request->idcompras)->delete();
        
        /*$results = DB::select("SELECT detallecompra.idcompra,(SELECT sum(detallecompra.total) from detallecompra  WHERE detallecompra.idcompra=?) as total 
                  from detallecompra 
                  INNER JOIN producto   
                  WHERE  detallecompra.idProducto=producto.id 
                  and detallecompra.idcompra=?", [$request->idcompras, $request->idcompras]);
        $total = "";
        foreach ($results as $key => $value) {
            $total = $value->total;
        }*/
        $dato = DB::table('compra')->where('id', $request->idcompras)->update(['eliminado' => 1]);
        return response()->json(["mensaje" => "eliminado"]);
    }

}
