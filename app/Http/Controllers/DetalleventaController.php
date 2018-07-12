<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Detalleventa;
use DB;
use Session;
use Redirect;
use App\Http\Requests;
use DateTime;

class DetalleventaController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        
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
        $cantPed=$request->cantidad;//interface
        $prodPed=$request->idProducto;//interface
        $almaPed=$request->idAlmacen;//interface
        $ventaPed=$request->idVenta;//interface
        $combocombo;//para asignaciones en el for de composicion
        $comboprod;//para asignaciones en el for de composicion
        $combocant;//para asignaciones en el for de composicion
        $stockprodcombo;//objet
        $stockprodcombodat;//data
        $canthis;//objet
        $canthisdat;//data
        $bool=false;
        $cantidaddetalle=0;
        $true = false; $true2 = 1; $varcomposicion2 = 0;
        $varingrediente2 = null; $varitem2 = null;$idventaaa=$request->idVenta;        
        //Empresa vende con o sin Stock
        $venderconstocks = DB::Select("select * from empresa");
        $venderstock = $venderconstocks[0]->venderSinStock;
        //El producto se vende con o sin Stock
        $venderporductoconstocks = DB::select("SELECT producto.conStock FROM producto WHERE producto.id = ?", [$request->idProducto]);
        $venderprostock = $venderporductoconstocks[0]->conStock;
        //Validar si el producto ya fue a gregado a la venta previamente
        $validar = self::validardosveces($request->idProducto, $request->idVenta);
        //Almacen de la venta
        $almacen = $request->idAlmacen;
        $productooo=$request->idProducto;
        $cantidaddd=$request->cantidad;

        //Precio de Venta del Producto obtenido de la interfaz
        $precioventanuevo = $request->precioventa;
        //Obtener el precio de venta del producto de la tabla dependiendo el tipo de venta, Contado-Credito
        $precioventas = self::precioventaproducto($request->idProducto, $request->tipoVenta);
        //precio de venta del producto
        $precioventa = $precioventas[0]->precioVenta;
        //tipo de producto item,servicio,combo
        $tipoproducto = $precioventas[0]->tipoproducto;
        //Stock del producto del almacen seleccionado
        $stocks = self::obtenerstock($request->idProducto, $request->idAlmacen);
        if ($stocks == 0) {
            $stock = 0;
        } else {
            $stock = $stocks[0]->stock;
        }
        //Validar que la cantidad sea mayor a 0
        if ($request->cantidad <= 0) {
            return response()->json(["mensaje" => "La Cantidad debe ser mayor a 0"]);
        }
        //Obtener descuento por defecto del cliente
        $descuentocliente = DB::select("SELECT cliente.idDescuento,
                                              tipodescuento.descuento AS porcentaje
                                        FROM cliente, tipodescuento 
                                        WHERE tipodescuento.id = cliente.idDescuento 
                                            AND cliente.id = ?", [$request->idCliente]);
        //Inicializacion de las variables con los datos del descuento del producto
        $idtipoDescuento = 1;
        $porcentajedescuento = 0;
        $importedescuento = 0;
        foreach ($descuentocliente as $key => $value) {
            $idtipoDescuento = $value->idDescuento;
            $porcentajedescuento = $value->porcentaje;
        }
        //validar si el producto ya esta en el detalle de la vnta
        if ($validar == 0) {
            //precio de venta nuevo del producto
            $precioventanuevo = $request->precioventa;
            //consultar si el nuevo precio de venta es null, en ese caso el precio se reemplaza por el actual
            if ($precioventanuevo == "null") {
                $precioventanuevo = $precioventa;
            }
            //consultar si el precio de venta nuevo es mayor al actualen ese caso no le permite vender
            if ($precioventanuevo > $precioventa) {
                return response()->json(["mensaje" => "El precio que inserto excede al precio actual del producto"]);
            }
            //consultar si el nuevo precio de venta es mayor a 0
            if ($precioventa == $precioventanuevo) {
                
            } else if ($precioventanuevo <= 0) {
                return response()->json(["mensaje" => "El precio de venta del producto debe ser mayor a 0"]);
            }
            //consultar si el tipo de producto es un combo
            if ($tipoproducto == "combo") {
            $combos = DB::select('SELECT composicionproducto.idProducto,
                    composicionproducto.idcomposicion,
                    composicionproducto.cantidad
                    FROM composicionproducto 
                    WHERE idProducto=?', [$request->idProducto]);
                if($venderstock==0 || $venderprostock==0) // el 0 significa vender con stock es decir que haya control
                {    if($stock < $cantPed)
                        {$mensaje = "Cantidad No Disponible. Solo hay " . $stock . " unidades de COMBO";
                        return response()->json(["mensaje" => $mensaje]);
                        }else //preguntamos por los productos del combo
                        {
                          foreach ($combos as $key => $value) 
                            { $combocombo = $value->idProducto; $comboprod = $value->idcomposicion;
                                  $combocant  = $value->cantidad;   $stockproductocomposicion = 0;
                                  $prodvenderstock = DB::select("SELECT producto.conStock FROM producto WHERE producto.id = ?", [$comboprod]);
                                  $prodstockbool = $prodvenderstock[0]->conStock;
                                if($venderconstocks==0 || $prodstockbool==0)//si esta controlado por $venderconstocks or $prodstockbool
                                  {
                                 $stockprodcombo=self::obtenerstock($comboprod, $almaPed);
                                  if ($stockprodcombo == 0) 
                                      { $stockprodcombodat = 0;} 
                                 else {$stockprodcombodat = $stockprodcombo[0]->stock;}
                                 
                             $cantidadproductodetalle= DB::select("SELECT IFNULL(sum(dd.cantidad),0)as cantidad
                             FROM detalleventa dd WHERE dd.idVenta=? and dd.idProducto=?",
                                [$ventaPed,$comboprod]);                                          
                                 foreach ($cantidadproductodetalle as $key => $value) 
                                 {$cantidaddetalle = $value->cantidad;}
                                 ///
                                $canthis = DB::select('SELECT IFNULL(SUM(cantidadpedida),0)as cantidad
                                FROM composicionproductodetalleventa 
                                WHERE idcomposicion=? AND idventa=? AND eliminado=1', [$comboprod, $ventaPed]);                                                                   
                                        $totalsumadoindividual = 0;
                                    foreach ($canthis as $key => $value)
                                    {$canthisdat = $value->cantidad;}
                                    
                                    if ($canthisdat > 0 && $cantidaddetalle>0) // 
                                    {
                                    $totalsumadoindividual = $canthisdat +$cantidaddetalle +($combocant * $cantPed);    
                                    }else
                                     {
                                         if ($canthisdat > 0 )
                                         {
                                         $totalsumadoindividual= $canthisdat + ($combocant * $cantPed);     
                                         }else{
                                              if($cantidaddetalle > 0)
                                              {
                                         $totalsumadoindividual= $cantidaddetalle + ($combocant * $cantPed);             
                                              }
                                              else 
                                              {
                                         $totalsumadoindividual= $combocant * $cantPed;                      
                                              }
                                         }                                         
                                     }                                             
                                    
                                   if ($stockprodcombodat < $totalsumadoindividual) 
                                        {$bool=true; $true2 = 0;$varcomposicion2 = $comboprod; break;}
                                        else
                                    {  $now = new DateTime();
                                       $actua2 = DB::table('composicionproductodetalleventa')->insert([
                                      'idventa' => $ventaPed,  'idproducto' => $prodPed,  'idcomposicion' => $comboprod,
                                      'cantidadoriginal' => $combocant,'cantidadpedida' => $combocant * $cantPed,
                                      'costooriginal' => $precioventa,'costocompartido' => 0,'fechahora' => $now, 
                                      'eliminado' => 1, 'idalmacen' => $almaPed]);   
                                    }
                                  }else //NO esta controlado por $venderconstocks or $prodstockbool insertalo de pecho
                                  {
                                       $now = new DateTime();
                                       $actua2 = DB::table('composicionproductodetalleventa')->insert([
                                      'idventa' => $ventaPed,  'idproducto' => $prodPed,  'idcomposicion' => $comboprod,
                                      'cantidadoriginal' => $combocant,'cantidadpedida' => $combocant * $cantPed,
                                      'costooriginal' => $precioventa,'costocompartido' => 0,'fechahora' => $now, 
                                      'eliminado' => 1, 'idalmacen' => $almaPed]);   
                                  }
                             }
                             if($true2==0)
                             {
                   $productonombre = DB::select('SELECT producto.nombre FROM producto WHERE id=?', [$varcomposicion2]);
                   $productonombre2 = null;
                   foreach ($productonombre as $key => $value) 
                       { $productonombre2 = $value->nombre; }                       
                    DB::table('composicionproductodetalleventa')->where('idventa', $ventaPed)->where('idproducto', $prodPed)->delete();
                    return response()->json(["mensaje" => "Producto " . $productonombre2 . " quedo Sin stocks "]);
                             }
                        }                        
                } //final de if($venderstock )
                else {//preguntamos por los productos del combo
                    foreach ($combos as $key => $value) 
                            { $combocombo = $value->idProducto; $comboprod = $value->idcomposicion;
                                  $combocant  = $value->cantidad;   $stockproductocomposicion = 0;
                                  
                                  $prodvenderstock = DB::select("SELECT producto.conStock FROM producto WHERE producto.id = ?", [$comboprod]);
                                  $prodstockbool = $prodvenderstock[0]->conStock;
                                if($venderconstocks==0 || $prodstockbool==0)//si esta controlado por $venderconstocks or $prodstockbool
                                  {
                      $stockprodcombo = DB::select('SELECT v_stockalmacensucursal.stock as cantidad
                       FROM v_stockalmacensucursal
                       WHERE v_stockalmacensucursal.idproducto = ? AND v_stockalmacensucursal.idalmacen = ?', [$comboprod, $almaPed]);
                    foreach ($stockprodcombo as $key => $value) 
                       {$stockprodcombodat = $value->cantidad;}                       
                    $cantidadproductodetalle= DB::select("SELECT IFNULL(sum(dd.cantidad),0)as cantidad
                             FROM detalleventa dd WHERE dd.idVenta=? and dd.idProducto=?",
                                [$ventaPed,$comboprod]);                      
                    
                        foreach ($cantidadproductodetalle as $key => $value) 
                        {$cantidaddetalle = $value->cantidad;}
                     //   if($cantidaddetalle==null)$cantidaddetalle=0;                        
                                $canthis = DB::select('SELECT IFNULL(sum(cantidadpedida),0)as cantidad
                                FROM composicionproductodetalleventa 
                                WHERE idcomposicion=? AND idventa=? AND eliminado=1', [$comboprod, $ventaPed]);                                                                   
                                        $totalsumadoindividual = 0;
                                    foreach ($canthis as $key => $value)
                                    {$canthisdat = $value->cantidad;}
                                    
                                    if ($canthisdat > 0 && $cantidaddetalle>0) // 
                                    {
                                    $totalsumadoindividual = $canthisdat +$cantidaddetalle +($combocant * $cantPed);    
                                    }else
                                     {
                                         if ($canthisdat > 0 )
                                         {
                                         $totalsumadoindividual= $canthisdat + ($combocant * $cantPed);     
                                         }else{
                                              if($cantidaddetalle > 0)
                                              {
                                         $totalsumadoindividual= $cantidaddetalle + ($combocant * $cantPed);             
                                              }
                                              else 
                                              {
                                         $totalsumadoindividual= $combocant * $cantPed;                      
                                              }
                                         }                                         
                                     }                                                                       
                                   if ($stockprodcombodat < $totalsumadoindividual) 
                                        {$bool=true; $true2 = 0;$varcomposicion2 = $comboprod; break;}
                                    else
                                    {  $now = new DateTime();
                                       $actua2 = DB::table('composicionproductodetalleventa')->insert([
                                      'idventa' => $ventaPed,  'idproducto' => $prodPed,  'idcomposicion' => $comboprod,
                                      'cantidadoriginal' => $combocant,'cantidadpedida' => $combocant * $cantPed,
                                      'costooriginal' => $precioventa,'costocompartido' => 0,'fechahora' => $now, 
                                      'eliminado' => 1, 'idalmacen' => $almaPed]);   
                                    }
                                  }else //NO esta controlado por $venderconstocks or $prodstockbool insertalo de pecho
                                  {
                                       $now = new DateTime();
                                       $actua2 = DB::table('composicionproductodetalleventa')->insert([
                                      'idventa' => $ventaPed,  'idproducto' => $prodPed,  'idcomposicion' => $comboprod,
                                      'cantidadoriginal' => $combocant,'cantidadpedida' => $combocant * $cantPed,
                                      'costooriginal' => $precioventa,'costocompartido' => 0,'fechahora' => $now, 
                                      'eliminado' => 1, 'idalmacen' => $almaPed]);   
                                  }
                             }
                             if($true2==0)
                             {
                        $productonombre = DB::select('SELECT producto.nombre FROM producto WHERE id=?', [$varcomposicion2]);
                        $productonombre2 = null;
                          foreach ($productonombre as $key => $value) 
                          { $productonombre2 = $value->nombre; }                       
                         DB::table('composicionproductodetalleventa')->where('idventa', $ventaPed)->where('idproducto', $prodPed)->delete();
                         return response()->json(["mensaje" => "Producto " . $productonombre2 . " quedo Sin stocks "]);
                             }
                     }// final del else sin control 
                    if($bool==false)
                    {
                        //calcular el importe total del detalle de la venta
                    $totalimporte = $precioventa * $request->cantidad;
                    if ($precioventa !== $precioventanuevo) {
                        //Calcula el importe y el porcentaje de descuento en el caso de que se cambio el precio de venta
                        $idtipoDescuento = 1;
                        $importedescuento = $precioventa - $precioventanuevo;
                        $porcentajedescuento = ($importedescuento * 100) / $totalimporte;
                    } else {
                        //Calcula el importe de descuento en caso de que se aplique un porcentaje 
                        $importedescuento = ($precioventa * $porcentajedescuento) / 100;
                    }
                    //Calcular el total neto
                    $totalneto = $totalimporte - $importedescuento;
                    //Insertar al detalle de la venta
                    $actua = DB::table('detalleventa')
                            ->where('idVenta', $request->idVenta)
                            ->insert(['idVenta' => $request->idVenta,
                        'idProducto' => $request->idProducto,
                        'cantidad' => $request->cantidad,
                        'precio' => $precioventa,
                        'total' => $totalimporte,
                        'idtipodescuento' => $idtipoDescuento,
                        'porcentajedescuento' => $porcentajedescuento,
                        'importedescuento' => $importedescuento,
                        'totalneto' => $totalneto,
                        'estado' => 2]);
                    return response()->json(["mensaje" => "Producto Agregado con Exito a la Venta"]);
                    }
                     
            } else {
                if ($tipoproducto == "comida") 
                 {
                $comidas = DB::select('SELECT ingredienteproducto.id,ingredienteproducto.idIngrediente,ingredienteproducto.cantidad,ingredienteproducto.costo
                                   FROM ingredienteproducto 
                                   WHERE id=?', [$request->idProducto]);
                    if($venderstock==0 || $venderprostock==0)
                    { 
                        if($stock<$cantPed)
                        {$mensaje = "Cantidad No Disponible. Solo hay " . $stock . " unidades de COMBO";
                        return response()->json(["mensaje" => $mensaje]);
                        }else //preguntamos por los productos del combo
                        {
                          foreach ($comidas as $key => $value) 
                            { $combocombo = $value->id; $comboprod = $value->idIngrediente;
                                  $combocant  = $value->cantidad;   $stockproductocomposicion = 0;
                                  $prodvenderstock = DB::select("SELECT producto.conStock FROM producto WHERE producto.id = ?", [$comboprod]);
                                  $prodstockbool = $prodvenderstock[0]->conStock;
                                if($venderconstocks==0 || $prodstockbool==0)//si esta controlado por $venderconstocks or $prodstockbool
                                  {
                                 $stockprodcombo=self::obtenerstock($comboprod, $almaPed);
                                  if ($stockprodcombo == 0) 
                                      { $stockprodcombodat = 0;} 
                                 else {$stockprodcombodat = $stockprodcombo[0]->stock;}
                                 ///
                                $canthis = DB::select('SELECT IFNULL(sum(cantidadpedida),0)as cantidad
                                FROM ingredienteproductodetalleventa 
                                WHERE idingrediente=? AND idventa=? AND eliminado=1', [$comboprod, $ventaPed]);                                                                   
                $cantidadproductodetalle= DB::select("SELECT IFNULL(sum(dd.cantidad),0)as cantidad
                             FROM detalleventa dd WHERE dd.idVenta=? and dd.idProducto=?",
                                [$ventaPed,$comboprod]);  
                        foreach ($cantidadproductodetalle as $key => $value) 
                        {$cantidaddetalle = $value->cantidad;}                                
                                        $totalsumadoindividual = 0;
                                    foreach ($canthis as $key => $value)
                                    {$canthisdat = $value->cantidad;}

                                    if ($canthisdat > 0 && $cantidaddetalle>0)// 
                                    {
                                    $totalsumadoindividual = $canthisdat +$cantidaddetalle +($combocant * $cantPed);    
                                    }else
                                     {
                                         if ($canthisdat > 0 )
                                         {
                                         $totalsumadoindividual= $canthisdat + ($combocant * $cantPed);     
                                         }else {
                                              if($cantidaddetalle > 0)
                                              {
                                         $totalsumadoindividual= $cantidaddetalle + ($combocant * $cantPed);             
                                              }
                                              else 
                                              {
                                         $totalsumadoindividual= ($combocant * $cantPed);                      
                                              }
                                         }                                         
                                     }
                                    
                                    
                                   if ($stockprodcombodat < $totalsumadoindividual) 
                                        {$bool=true; $true2 = 0;$varcomposicion2 = $comboprod; break;}
                                        else
                                    {  $now = new DateTime();
                                       $actua2 = DB::table('ingredienteproductodetalleventa')->insert([
                                      'idventa' => $ventaPed,  'idproducto' => $prodPed,  'idingrediente' => $comboprod,
                                      'cantidadoriginal' => $combocant,'cantidadpedida' => $combocant * $cantPed,
                                      'costooriginal' => $precioventa,'costocompartido' => 0,'fechahora' => $now, 
                                      'eliminado' => 1, 'idalmacen' => $almaPed]);   
                                    }
                                  }else //NO esta controlado por $venderconstocks or $prodstockbool insertalo de pecho
                                  {
                                       $now = new DateTime();
                                       $actua2 = DB::table('ingredienteproductodetalleventa')->insert([
                                      'idventa' => $ventaPed,  'idproducto' => $prodPed,  'idingrediente' => $comboprod,
                                      'cantidadoriginal' => $combocant,'cantidadpedida' => $combocant * $cantPed,
                                      'costooriginal' => $precioventa,'costocompartido' => 0,'fechahora' => $now, 
                                      'eliminado' => 1, 'idalmacen' => $almaPed]);   
                                  }
                             }
                             if($true2==0)
                             {
                   $productonombre = DB::select('SELECT producto.nombre FROM producto WHERE id=?', [$varcomposicion2]);
                   $productonombre2 = null;
                   foreach ($productonombre as $key => $value) 
                           {$productonombre2 = $value->nombre; }                       
                    DB::table('ingredienteproductodetalleventa')->where('idventa', $ventaPed)->where('idproducto', $prodPed)->delete();
                    return response()->json(["mensaje" => "Producto " . $productonombre2 . " quedo Sin stocks "]);
                             }
                        }    
                    } //fin if control constock COMIDA
                    else // si no hay control de la comida ahora serian sus ingredientes preguntarlos si hay o no control
                    {   foreach ($comidas as $key => $value) 
                            { $combocombo = $value->id; $comboprod = $value->idingrediente;
                                  $combocant  = $value->cantidad;   $stockproductocomposicion = 0;
                                  $prodvenderstock = DB::select("SELECT producto.conStock FROM producto WHERE producto.id = ?", [$comboprod]);
                                  $prodstockbool = $prodvenderstock[0]->conStock;
                                if($venderconstocks==0 || $prodstockbool==0)//si esta controlado por $venderconstocks or $prodstockbool
                                  {
                                 $stockprodcombo=self::obtenerstock($comboprod, $almaPed);
                                  if ($stockprodcombo == 0) 
                                      { $stockprodcombodat = 0;} 
                                 else {$stockprodcombodat = $stockprodcombo[0]->stock;}
                                 ///
                    $cantidadproductodetalle= DB::select("SELECT IFNULL(dd.cantidad,0)as cantidad FROM detalleventa dd WHERE dd.idVenta=? and dd.idProducto=?",
                                [$ventaPed,$comboprod]);  
                        foreach ($cantidadproductodetalle as $key => $value) 
                        {$cantidaddetalle = $value->cantidad;}
                                $canthis = DB::select('SELECT IFNULL(sum(cantidadpedida),0)as cantidad
                                FROM ingredienteproductodetalleventa 
                                WHERE idingrediente=? AND idventa=? AND eliminado=1', [$comboprod, $ventaPed]);                                                                   
                                        $totalsumadoindividual = 0;
                                    foreach ($canthis as $key => $value)
                                    {$canthisdat = $value->cantidad;}
                                    
                                    if ($canthisdat > 0 && $cantidaddetalle>0) // 
                                    {
                                    $totalsumadoindividual = $canthisdat +$cantidaddetalle +($combocant * $cantPed);    
                                    }else
                                     {
                                         if ($canthisdat > 0 )
                                         {
                                         $totalsumadoindividual= $canthisdat + ($combocant * $cantPed);     
                                         }else {
                                              if($cantidaddetalle > 0)
                                              {
                                         $totalsumadoindividual= $cantidaddetalle + ($combocant * $cantPed);             
                                              }
                                              else 
                                              {
                                         $totalsumadoindividual= ($combocant * $cantPed);                      
                                              }
                                         }                                         
                                     }
                                    
                                    
                                   if ($stockprodcombodat < $totalsumadoindividual) 
                                        {$bool=true; $true2 = 0;$varcomposicion2 = $comboprod; break;}
                                        else
                                    {  $now = new DateTime();
                                       $actua2 = DB::table('ingredienteproductodetalleventa')->insert([
                                      'idventa' => $ventaPed,  'idproducto' => $prodPed,  'idingrediente' => $comboprod,
                                      'cantidadoriginal' => $combocant,'cantidadpedida' => $combocant * $cantPed,
                                      'costooriginal' => $precioventa,'costocompartido' => 0,'fechahora' => $now, 
                                      'eliminado' => 1, 'idalmacen' => $almaPed]);   
                                    }
                                  }else //NO esta controlado por $venderconstocks or $prodstockbool insertalo de pecho
                                  {
                                       $now = new DateTime();
                                       $actua2 = DB::table('ingredienteproductodetalleventa')->insert([
                                      'idventa' => $ventaPed,  'idproducto' => $prodPed,  'idingrediente' => $comboprod,
                                      'cantidadoriginal' => $combocant,'cantidadpedida' => $combocant * $cantPed,
                                      'costooriginal' => $precioventa,'costocompartido' => 0,'fechahora' => $now, 
                                      'eliminado' => 1, 'idalmacen' => $almaPed]);   
                                  }
                             }
                             if($true2==0)
                             {
                   $productonombre = DB::select('SELECT producto.nombre FROM producto WHERE id=?', [$varcomposicion2]);
                   $productonombre2 = null;
                   foreach ($productonombre as $key => $value) 
                       { $productonombre2 = $value->nombre; }                       
                    DB::table('ingredienteproductodetalleventa')->where('idventa', $ventaPed)->where('idproducto', $prodPed)->delete();
                    return response()->json(["mensaje" => "Producto " . $productonombre2 . " quedo Sin stocks "]);
                             }   
                       }                    
                   if($bool==false)
                    { //calcular el importe total del detalle de la venta
                    $totalimporte = $precioventa * $request->cantidad;
                    if ($precioventa !== $precioventanuevo) {
                        //Calcula el importe y el porcentaje de descuento en el caso de que se cambio el precio de venta
                        $idtipoDescuento = 1;
                        $importedescuento = $precioventa - $precioventanuevo;
                        $porcentajedescuento = ($importedescuento * 100) / $totalimporte;
                    } else {
                        //Calcula el importe de descuento en caso de que se aplique un porcentaje 
                        $importedescuento = ($precioventa * $porcentajedescuento) / 100;
                    }
                    //Calcular el total neto
                    $totalneto = $totalimporte - $importedescuento;
                    //Insertar al detalle de la venta
                    $actua = DB::table('detalleventa')
                            ->where('idVenta', $request->idVenta)
                            ->insert(['idVenta' => $request->idVenta,
                        'idProducto' => $request->idProducto,
                        'cantidad' => $request->cantidad,
                        'precio' => $precioventa,
                        'total' => $totalimporte,
                        'idtipodescuento' => $idtipoDescuento,
                        'porcentajedescuento' => $porcentajedescuento,
                        'importedescuento' => $importedescuento,
                        'totalneto' => $totalneto,
                        'estado' => 2]);
                    return response()->json(["mensaje" => "Producto Agregado con Exito a la Venta"]);
                    }
                 }//fin if comida
                 else///AQUI CUALQUIERA ITEM O SERVICIO POR SEPARADO DIRECTO AL DETALLEVENTA
                     {
                    //consultar sila empresa vende productos con stock
                    if ($venderstock == 0 || $venderprostock == 0)                                                       
                     { // 0 es se vende con stock dice la empresa o el producto
                      //consultar si el producto se vende con stock
                    $cantidadproducto = 0; $iddelproducto = 0; $stockproductodd = 0;
                    
                     $productonombre = DB::select('
                     SELECT IFNULL (composicionproductodetalleventa.idproducto,0) AS idproducto, IFNULL(composicionproductodetalleventa.idcomposicion,0) AS idcomposicion,
                        IFNULL(SUM(cantidadpedida),0) AS cantidadpedida
                     FROM composicionproductodetalleventa 
                     WHERE idcomposicion=? AND idventa=? AND eliminado=1'
                             , [$request->idProducto, $request->idVenta]);
                    foreach ($productonombre as $key => $value) {
                       $idproductos=$value->idproducto;
                       $idcomposicions=$value->idcomposicion;
                       $cantidadpedidas=$value->cantidadpedida;
                    }
                    if (($idproductos!=0)   || ($idcomposicions!=0)   || ($cantidadpedidas>0) ) 
                        {
                        foreach ($productonombre as $key => $value) {
                            $cantidadproducto = $value->cantidadpedida; $iddelproducto = $value->idcomposicion;
                                   // $stocks = self::obtenerstock($request->idProducto, $request->idAlmacen);
        
                            $datosdd = DB::select('SELECT v_stockalmacensucursal.stock as cantidad
                              FROM v_stockalmacensucursal
                              WHERE v_stockalmacensucursal.idproducto = ? 
                              AND v_stockalmacensucursal.idalmacen = ?', [$iddelproducto, $almacen]);
                            
                            foreach ($datosdd as $key => $value) 
                            {  $stockproductodd = $value->cantidad;  }
                            
                            //hay stock en la suma de la (cantidadinsertada + cantidaddelcombo) de ese producto ITEM
                            if ($stockproductodd < ($cantidadproducto + $request->cantidad)) 
                                { $varitem2 = $request->idProducto; 
                                  $true = true;  break; 
                                }
                        }
                    if ($true == true) {
                   $productonombre = DB::select('SELECT producto.nombre FROM producto  WHERE id=?', [$varitem2]);
                   $productonombre2 = null;
                   foreach ($productonombre as $key => $value)
                       {
                       $productonombre2 = $value->nombre;
                       }
                    // DB::table('ingredienteproductodetalleventa')->where('idventa', $idventass)->delete();
                   return response()->json(["mensaje" => "Producto " . $productonombre2 . "sin stock; el Combo anterior acabo su stock"]);
                                 }
                     }else {
                         $datosdd = DB::select('SELECT v_stockalmacensucursal.stock as cantidad
                              FROM v_stockalmacensucursal
                              WHERE v_stockalmacensucursal.idproducto = ? 
                              AND v_stockalmacensucursal.idalmacen = ?', [$productooo,$almacen]);
                            foreach ($datosdd as $key => $value) 
                            { $stockproductodd = $value->cantidad;  }
                            
                              if ($stockproductodd <  $cantidaddd) 
                                {  $varitem2 = $request->idProducto; 
                                 $true = true; 
                                }
                if($true==true)
                       {
            $productonombre = DB::select('SELECT producto.nombre FROM producto  WHERE id=?', [$varitem2]);
            $productonombre2 = null;
                    foreach ($productonombre as $key => $value)
                       {$productonombre2 = $value->nombre; }
                    // DB::table('ingredienteproductodetalleventa')->where('idventa', $idventass)->delete();
                         return response()->json(["mensaje" => "Producto " . $productonombre2 . "sin stock;"]); 
                       }   
                      }                    
                    //calcular el importe total del detalle de la venta
                    $totalimporte = $precioventa * $request->cantidad;
                    if ($precioventa !== $precioventanuevo) {
                        //Calcula el importe y el porcentaje de descuento en el caso de que se cambio el precio de venta
                        $idtipoDescuento = 1;
                        $importedescuento = $precioventa - $precioventanuevo;
                        $porcentajedescuento = ($importedescuento * 100) / $totalimporte;
                    } else {
                        //Calcula el importe de descuento en caso de que se aplique un porcentaje 
                        $importedescuento = ($precioventa * $porcentajedescuento) / 100;
                    }
                    //Calcular el total neto
                    $totalneto = $totalimporte - $importedescuento;
                    //Insertar al detalle de la venta
                    $actua = DB::table('detalleventa')
                            ->where('idVenta', $request->idVenta)
                            ->insert(['idVenta' => $request->idVenta,
                        'idProducto' => $request->idProducto,
                        'cantidad' => $request->cantidad,
                        'precio' => $precioventa,
                        'total' => $totalimporte,
                        'idtipodescuento' => $idtipoDescuento,
                        'porcentajedescuento' => $porcentajedescuento,
                        'importedescuento' => $importedescuento,
                        'totalneto' => $totalneto,
                        'estado' => 2]);
                    
                    return response()->json(["mensaje" => "Producto Agregado con Exito a la Venta"]);//AKI LO CORTA Y NO ENTRA al otro if  XD DD 
                    
                 } else {
                        if(($venderstock == 1) &&  ($venderprostock == 1))
                        { //calcular el importe total del detalle de la venta
                    $totalimporte = $precioventa * $request->cantidad;
                    if ($precioventa !== $precioventanuevo) {
                        //Calcula el importe y el porcentaje de descuento en el caso de que se cambio el precio de venta
                        $idtipoDescuento = 1;
                        $importedescuento = $precioventa - $precioventanuevo;
                        $porcentajedescuento = ($importedescuento * 100) / $totalimporte;
                    } else {
                        //Calcula el importe de descuento en caso de que se aplique un porcentaje 
                        $importedescuento = ($precioventa * $porcentajedescuento) / 100;
                    }
                    //Calcular el total neto
                    $totalneto = $totalimporte - $importedescuento;
                    //Insertar al detalle de la venta
                    $actua = DB::table('detalleventa')
                            ->where('idVenta', $request->idVenta)
                            ->insert(['idVenta' => $request->idVenta,
                        'idProducto' => $request->idProducto,
                        'cantidad' => $request->cantidad,
                        'precio' => $precioventa,
                        'total' => $totalimporte,
                        'idtipodescuento' => $idtipoDescuento,
                        'porcentajedescuento' => $porcentajedescuento,
                        'importedescuento' => $importedescuento,
                        'totalneto' => $totalneto,
                        'estado' => 2]);                    
                    return response()->json(["mensaje" => "Producto Agregado con Exito a la Venta"]);//AKI LO CORTA Y NO ENTRA al otro if  XD DD                                             
                          }
                     }                 
                }
            }
            return response()->json(["mensaje" => "Producto agregado con exito a la Venta"]);
        } else {
            return response()->json(["mensaje" => "El producto ya esta en la venta, edite la cantidad si lo decea"]);
        }
    }


    public function validardosveces($idProducto, $idVenta) {
        $datos = 0;
        $results = DB::select("SELECT
                count(detalleventa.idProducto) as repite
            FROM detalleventa
            INNER JOIN venta on venta.id=detalleventa.idVenta 
                AND venta.estado=0
            WHERE detalleventa.idProducto=? 
                AND  venta.id=?
            GROUP BY detalleventa.idProducto
            HAVING count(*) >0", [$idProducto, $idVenta]);
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
        $dato = DB::table('detalleventa')->select(
                        'detalleventa.cantidad', 'detalleventa.id', 'producto.nombre', 'producto.imagen', 'producto.descripcion', 'detalleventa.totalneto', 'detalleventa.precio', 'detalleventa.idProducto', 'detalleventa.total')
                ->where('detalleventa.id', $id)
                ->join('producto', 'producto.id', '=', 'detalleventa.idProducto')
                ->get();
        return response()->json($dato);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
//
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $porcentajedescuento;
        $subtotal;
        $descuentos = DB::select("select tipodescuento.descuento
                      from tipodescuento
                      where tipodescuento.id = ?
                ", [$request->iddescuento]);
        foreach ($descuentos as $key => $value) {
            $porcentajedescuento = $value->descuento;
        }
        $totdetalle = DB::select("select detalleventa.total
                      from detalleventa
                      where detalleventa.id = ?
                ", [$id]);
        foreach ($totdetalle as $key => $value) {
            $subtotal = $value->total;
        }
        $importe = ($subtotal * $porcentajedescuento) / 100;
        $tottalneto = $subtotal - $importe;

        $actua = DB::table('detalleventa')
                ->where('id', $id)
                ->update(['idtipodescuento' => $request->iddescuento, 'porcentajedescuento' => $porcentajedescuento, 'importedescuento' => $importe,
            'totalneto' => $tottalneto]);
        return response()->json(["actualizado" => $request->all()]);
    }

    public function actualizarcantidad(Request $request) {
        //Seleccionar si la empresa vende con o sin stock
        $venderconstocks = DB::Select("select * from empresa");
        $venderstock = $venderconstocks[0]->venderSinStock;
        //Obtener los datos del detalle de la venta
        $cantidad = $results = DB::select("
                        SELECT detalleventa.cantidad,
                        detalleventa.importedescuento,
                        detalleventa.porcentajedescuento,
                        detalleventa.idProducto,
                        detalleventa.idVenta,
                        detalleventa.precio
                from detalleventa
                WHERE detalleventa.id = ? ", [$request->id]);
        $importedescuento = $cantidad[0]->importedescuento;
        $porcentajedescuento = $cantidad[0]->porcentajedescuento;
        $idproducto = $cantidad[0]->idProducto;
        
        $idVenta = $cantidad[0]->idVenta;
        $precioventa = $cantidad[0]->precio;
        //El producto se vende con o sin Stock
        $venderporductoconstocks = DB::select("SELECT producto.conStock FROM producto WHERE producto.id = ?", [$idproducto]);
        $venderprostock = $venderporductoconstocks[0]->conStock;
        //
        $cant = $request->cantidad;
        $almacen=$request->idAlmacen;
        $stocks = self::obtenerstock($idproducto, $request->idAlmacen);
        if ($stocks == 0 ) {
            $stock = 0;
        } else {
            $stock = $stocks[0]->stock;
                }
        if ($cant < 0) {
            $mensaje = "La Cantidad debe ser mayor a 0";
            return response()->json(["mensaje" => $mensaje]);
        }
        if ($venderstock == 0 || $venderprostock == 0) {            
                if ($stock < $cant) {
                    $mensaje = "Cantidad No Disponible. Solo hay " . $stock . " unidades";
                    return response()->json(["mensaje" => $mensaje]);
                       }            
                }
        $precioventas = self::obtenertipoproducto($idproducto);       
        $tipoproducto = $precioventas[0]->tipoproducto;
        $bool = false;
        if ($tipoproducto == "combo") {
            $productosdelcombo = DB::select("SELECT composicionproducto.cantidad,
                                                    composicionproducto.idcomposicion as idcomboproducto
                                                FROM composicionproducto
                                                WHERE composicionproducto.idProducto = ?", [$idproducto]);
            foreach ($productosdelcombo as $value) {
                $idproductocombo = $value->idcomboproducto;
                $cantidadproducto = $value->cantidad;
                $venderporductoconstocks = DB::select("SELECT producto.conStock FROM producto WHERE producto.id = ?", [$idproductocombo]);
                $venderprostock = $venderporductoconstocks[0]->conStock;
                $stocks = self::obtenerstock($idproductocombo, $request->idAlmacen);
                
                    $cantidadproductodetalle= DB::select("SELECT IFNULL(sum(dd.cantidad),0)as cantidad
                             FROM detalleventa dd WHERE dd.idVenta=? and dd.idProducto=?",
                                [$idVenta,$idproductocombo]);                      
                    
                        foreach ($cantidadproductodetalle as $key => $value) 
                        {$cantidaddetalle = $value->cantidad;}
                     //   if($cantidaddetalle==null)$cantidaddetalle=0;                        
                                $canthis = DB::select('SELECT IFNULL(sum(cantidadpedida),0)as cantidad
                                FROM composicionproductodetalleventa 
                                WHERE idcomposicion=? AND idventa=? AND eliminado=1 and idproducto<> ?', 
                                [$idproductocombo, $idVenta ,$idproducto]);                                                                   
                                        $totalsumadoindividual = 0;
                                    foreach ($canthis as $key => $value)
                                    {$canthisdat = $value->cantidad;}                                    
                                    if ($canthisdat > 0 && $cantidaddetalle>0) // 
                                    {
                                    $totalsumadoindividual = $canthisdat +$cantidaddetalle +($cantidadproducto * $cant);    
                                    }else
                                     {
                                         if ($canthisdat > 0 )
                                         {
                                         $totalsumadoindividual= $canthisdat + ($cantidadproducto * $cant);     
                                         }else{
                                              if($cantidaddetalle > 0)
                                              {
                                         $totalsumadoindividual= $cantidaddetalle + ($cantidadproducto * $cant);             
                                              }
                                              else 
                                              {
                                         $totalsumadoindividual= ($cantidadproducto * $cant);                      
                                              }
                                         }                                         
                                     }                                                                      
              //  $cantidad = $cantidadproducto * $cant;
                $stock2;
                if ($stocks == 0) {
                    $stock2 = 0;
                } else {
                    $stock2 = $stocks[0]->stock;
                }
                if ($venderstock == 0 || $venderprostock == 0) {                    
                        if ($stock2 < $totalsumadoindividual) {
                            $bool = true;
                            break;
                        }                    
                  }
            }
        } else
            if ($tipoproducto == "comida") {
 $productosdelcombo = DB::select("SELECT ingredienteproducto.id,ingredienteproducto.idIngrediente,ingredienteproducto.cantidad,ingredienteproducto.costo
                                            FROM ingredienteproducto WHERE id=?", [$idproducto]);
            foreach ($productosdelcombo as $value) {
                $idproductocombo = $value->idIngrediente;
                $cantidadproducto = $value->cantidad;
                $venderporductoconstocks = DB::select("SELECT producto.conStock FROM producto WHERE producto.id = ?", [$idproductocombo]);
                $venderprostock = $venderporductoconstocks[0]->conStock;
                $stocks = self::obtenerstock($idproductocombo, $request->idAlmacen);
                
                   $cantidadproductodetalle= DB::select("SELECT IFNULL(sum(dd.cantidad),0)as cantidad
                             FROM detalleventa dd WHERE dd.idVenta=? and dd.idProducto=?",
                                [$idVenta,$idproductocombo]);                      
                    
                        foreach ($cantidadproductodetalle as $key => $value) 
                        {$cantidaddetalle = $value->cantidad;} //va ser cero siempre por q no aparce en el detalleventa un ingrediente
                     //   if($cantidaddetalle==null)$cantidaddetalle=0;                        
                                $canthis = DB::select('SELECT IFNULL(sum(cantidadpedida),0)as cantidad
                                FROM ingredienteproductodetalleventa 
                                WHERE idingrediente=? AND idventa=? AND eliminado=1 AND idproducto<>?', 
                                        [$idproductocombo, $idVenta,$idproducto]);                                                                   
                                        $totalsumadoindividual = 0;
                                    foreach ($canthis as $key => $value)
                                    {$canthisdat = $value->cantidad;}                                                                        
                                    if ($canthisdat > 0 && $cantidaddetalle>0) // 
                                    {
                                    $totalsumadoindividual = $canthisdat +$cantidaddetalle +($cantidadproducto * $cant);    
                                    }else
                                     {
                                         if ($canthisdat > 0 )
                                         {
                                         $totalsumadoindividual= $canthisdat + ($cantidadproducto * $cant);     
                                         }else{
                                              if($cantidaddetalle > 0)
                                              {
                                         $totalsumadoindividual= $cantidaddetalle + ($cantidadproducto * $cant);             
                                              }
                                              else 
                                              {
                                         $totalsumadoindividual= ($cantidadproducto * $cant);                      
                                              }
                                         }                                         
                                     }              
                $stock3;
                if ($stocks == 0) {
                    $stock3 = 0;
                } else {
                    $stock3 = $stocks[0]->stock;
                }
                if ($venderstock == 0 || $venderprostock == 0) {                    
                        if ($stock3 < $totalsumadoindividual) {
                            $bool = true;
                            break;
                        }                    
                  }
            }
        } else {// eres un item 
                if($tipoproducto=='item')                              
            if ($venderstock == 0 || $venderprostock == 0)                                                       
                     {                
                     $cantidaddentro = 0; $idproductos = 0;$idcomposicions=0; $stockproductodd = 0;   
                     $varitem2=0;              
                    $datosdd = DB::select('SELECT v_stockalmacensucursal.stock as cantidad
                    FROM v_stockalmacensucursal
                    WHERE v_stockalmacensucursal.idproducto = ? 
                    AND v_stockalmacensucursal.idalmacen = ?', [$idproducto, $almacen]);
                    foreach ($datosdd as $key => $value) 
                         { $stockproductodd = $value->cantidad;  }                                                                     
                     $productonombre = DB::select('
                     SELECT IFNULL (composicionproductodetalleventa.idproducto,0) AS idproducto,
                     IFNULL(composicionproductodetalleventa.idcomposicion,0) AS idcomposicion,
                        IFNULL(SUM(cantidadpedida),0) AS cantidadpedida
                     FROM composicionproductodetalleventa 
                     WHERE idcomposicion=? AND idventa=? AND eliminado=1'
                             , [$idproducto, $idVenta]);                     
                    foreach ($productonombre as $key => $value) {
                       $idproductos=$value->idproducto;
                       $idcomposicions=$value->idcomposicion;
                       $cantidaddentro=$value->cantidadpedida;
                    }                        
// este IF de abajo; es para saber si ese producto tb pertenece a algun combo y contar su cantidad en la venta de ese instante
                    if (($idproductos!=0)   || ($idcomposicions!=0)   || ($cantidaddentro!=0) ) 
                       { //hay stock en la suma de la (cantidadinsertada + cantidaddelcombo) de ese producto ITEM
                            if ($stockproductodd < ($cantidaddentro + $request->cantidad)) 
                                { $varitem2 = $request->idProducto; 
                                 $bool = true;                                 
                                }                                                       
                       }else {                       
                              if ($stockproductodd < $request->cantidad)
                                {  $varitem2 = $request->idProducto; 
                                   $bool = true;
                                }                         
                         }                    
                  } 
        }
        if ($bool) {
            if ($tipoproducto == "comida") {
                $mensaje = "Los productos que componen la" . $tipoproducto . " no tiene la cantidad suficiente";
            } else {
                if($tipoproducto=='combo'){
                $mensaje = "Los productos que componen el" . $tipoproducto . " no tiene la cantidad suficiente";    
                }else {
                 $mensaje = "El producto " . $tipoproducto . " no tiene la cantidad suficiente";      
                }               
            }
            return response()->json(["mensaje" => $mensaje]);
        } else {
            if ($tipoproducto == "combo") {
$productosdelcombo = DB::select("SELECT composicionproducto.cantidad,composicionproducto.idcomposicion as idcomboproducto
                          FROM composicionproducto
                          WHERE composicionproducto.idProducto = ?", [$idproducto]);
                foreach ($productosdelcombo as $value) {
                    $idproductocombo = $value->idcomboproducto;
                    $cantidadproducto = $value->cantidad;
                    $cantidad = $cantidadproducto * $cant;
                    DB::table('composicionproductodetalleventa')
                            ->where('idventa',$idVenta)
                            ->where('idproducto',$idproducto)
                            ->where('idcomposicion', $idproductocombo)
                            ->update(['cantidadpedida' => $cantidad]);
                }
            } else {
                    if ($tipoproducto=='comida')
                    {
$productosdelcombo = DB::select("SELECT ingredienteproducto.id,ingredienteproducto.idIngrediente, ingredienteproducto.cantidad,ingredienteproducto.costo
                                  FROM ingredienteproducto WHERE id = ?", [$idproducto]);
                foreach ($productosdelcombo as $value) {
                    $idproductoingrediente = $value->idIngrediente;
                    $cantidadproducto = $value->cantidad;
                    $cantidad = $cantidadproducto * $cant;
                    DB::table('ingredienteproductodetalleventa')
                            ->where('idventa',$idVenta)
                            ->where('idproducto',$idproducto)                            
                            ->where('idingrediente', $idproductoingrediente)
                            ->update(['cantidadpedida' => $cantidad]);
                         }   
                    } 
              }
        }        
        $totalimporte = $precioventa * $cant;
        $importedescuento = ($totalimporte * $porcentajedescuento) / 100;
        $totalneto = ($totalimporte - $importedescuento);
        DB::table('detalleventa')
                ->where('id', $request->id)
                ->update(['cantidad' => $cant,
                    'total' => $totalimporte,'importedescuento' => $importedescuento,'totalneto' => $totalneto]);        
            return response()->json(["mensaje" => "Cantidad Actualizada Exitosamente"]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id) {
        $idprod = DB::select((' 
            SELECT idProducto
            FROM detalleventa 
            WHERE id=?
             '), [$id]);
        $idproducto = $idprod[0]->idProducto;
        $idventa = $request->idventa;
        $datos = DB::select((' 
            SELECT tipoproducto
            FROM producto 
            WHERE id=?
             '), [$idproducto]);
        $tipoproducto2 = null;
        foreach ($datos as $key => $value) {
            $tipoproducto2 = $value->tipoproducto;
        }
        if ($tipoproducto2 == "combo") {
            DB::table('composicionproductodetalleventa')
                    ->where('idventa', $idventa)
                    ->where('idproducto', $idproducto)
                    ->delete();
        } else if ($tipoproducto2 == "comida") {
            DB::table('ingredienteproductodetalleventa')
                    ->where('idventa', $idventa)
                    ->where('idproducto', $idproducto)
                    ->delete();
        }
        DB::table('detalleventa')->where('id', $id)->delete();
        return response()->json($request->all());
    }

    public function precioventaproducto($id, $tipoventa) {
        $idsucursal = Session::get('idsucursal');
        $results = DB::select("SELECT 
            IF(? = 'Contado',productosucursal.precioVenta, productosucursal.precioVentaCredito) as precioVenta,
            producto.tipoproducto
                from producto
                INNER JOIN productosucursal ON producto.id = productosucursal.idproducto  AND productosucursal.idsucursal= ? 
                WHERE producto.id=?", [ $tipoventa, $idsucursal, $id]);
        return $results;
    }

    public function obtenertipoproducto($id) {
        $results = DB::select("SELECT producto.tipoproducto FROM producto WHERE producto.id = ?", [$id]);
        return $results;
    }

    public function productocondetalledeventa($idventa) {
//and detalleventa.idVenta=1 
        $results = DB::select("
            SELECT
            producto.nombre,
            producto.descripcion,
            producto.color,
            producto.imagen,
            producto.tipoproducto as tipo,
            producto.tamano as talla,
            (select marca.nombre
                from marca
                where producto.idMarca = marca.id) as marca,
            detalleventa.precio as precioVenta,
            detalleventa.cantidad,
            detalleventa.idVenta,
            detalleventa.idProducto,
            detalleventa.id,
            detalleventa.totalneto,
            detalleventa.importedescuento,
            detalleventa.porcentajedescuento,
            (detalleventa.precio * detalleventa.cantidad) as subtotal ,
            (SELECT  (sum((detalleventa.precio*detalleventa.cantidad) - detalleventa.importedescuento ))
                FROM detalleventa
                INNER JOIN producto  
                INNER JOIN venta 
                WHERE detalleventa.idVenta=venta.id 
                AND detalleventa.idProducto=producto.id 
                AND detalleventa.idVenta = ? ) AS total,
                venta.descuentocliente as descuentocliente,
             (SELECT tipodescuento.descuento FROM tipodescuento WHERE venta.idTipoDescuento=tipodescuento.id)  AS descuentoventa,
             (select tipodescuento.id from tipodescuento where venta.idTipoDescuento=tipodescuento.id)  as tipodescuentoventa
            FROM detalleventa
            INNER JOIN producto
            INNER JOIN venta 
            WHERE detalleventa.idVenta=venta.id 
            AND detalleventa.idProducto=producto.id 
            AND venta.id=?", [$idventa, $idventa]);
        return response()->json($results);
    }

    public function obtenerstock($id, $idalmacen) {
        $si = false;
        $results = DB::select("SELECT IFNULL(v_stockalmacensucursal.stock,0) as stock
            FROM v_stockalmacensucursal
            WHERE v_stockalmacensucursal.idproducto = ? and v_stockalmacensucursal.idalmacen = ?
                ", [$id, $idalmacen]);
        foreach ($results as $key => $value) {
            $si = true;
        }
        if ($si == true) {
            return $results;
        } else {
            return 0;
        }
    }

    public function obteneralmacen($id) {
        $Results = DB::Select("SELECT venta.idAlmacen WHERE venta.id = ?", [$id]);
        return $results;
    }
    
    
    public function obtenerporductoscombo($iddetalleVenta) {
        $producto = DB::select("  
                SELECT detalleventa.idProducto,producto.tipoproducto AS tipo 
                FROM detalleventa INNER JOIN producto ON producto.id=detalleventa.idProducto
                WHERE detalleventa.id=?                                     
                   ", [$iddetalleVenta]);
        
        foreach ($producto as $key => $value){ $idproducto = $value->idProducto; $tipo=$value->tipo; }
         if($tipo=='combo')
         {
         $dato = DB::select("SELECT c.idcomposicion as idcontenido,p.nombre,c.cantidad,p.imagen 
            FROM composicionproducto c,producto p
          WHERE c.idcomposicion=p.id AND c.idProducto=?", [$idproducto]);
        
         }else {
             if($tipo=='comida')
             {
           $dato= DB::select("
          SELECT g.idingrediente as idcontenido,p.nombre,g.cantidad,p.imagen 
          FROM ingredienteproducto g ,producto p
          WHERE g.idIngrediente=p.id AND g.id=?", [$idproducto]);  
             }
           
         }
        return response()->json($dato);     
    }
    protected function validarstockminimo($producto,$almacen,$cantidad)
    {   $cantidadminima_DATO;
        $stockalmaproducto2_DATO;
        $stockParcial_DATO;        
        $stockMinproducto_OBJ = DB::select("SELECT stockMin
                    FROM producto WHERE id= ?", [$producto]);
        $stockMin2producto_DATO=$stockMinproducto_OBJ[0]->stockMin;
        
        $stockalmaproducto_OBJ=DB::select("SELECT v_stockalmacensucursal.stock as cantidad
                       FROM v_stockalmacensucursal
                       WHERE v_stockalmacensucursal.idproducto = ? 
                       AND v_stockalmacensucursal.idalmacen = ?", [$producto, $almacen]);                    
                    foreach ($stockalmaproducto_OBJ as $key => $value) 
                       {$stockalmaproducto2_DATO = $value->cantidad;}                                              
        $stockParcial_DATO=$stockalmaproducto2_DATO-$cantidad;
                    if($stockParcial_DATO<=$stockMin2producto_DATO)
                    {
                        $stockMin2producto_DATO=(string)$stockMin2producto_DATO;
                        $stockParcial_DATO=(string)$stockParcial_DATO;
                        $nombre_OBJ=DB::select("SELECT nombre FROM producto WHERE id=?",[$producto]);
                        $nombre2_DATO=$nombre_OBJ[0]->nombre;
                       return response()->json(["mensaje" => "Stock del producto: ".$nombre2_DATO."("."stockParcial: ".$stockParcial_DATO.")quedo por debajo o igual de su stock Min. de Seguridad("."StockMin: ".$stockMin2producto_DATO.") Necesita Comprar mas productos"]);
                    }else
                    {
                          return response()->json(["mensaje" => "ninguno"]);
                    }
                  
    }  
    protected function validarstockminimoactualiza($id,$almacen,$cantidad)
        {
         $cantidadminima_DATO;
        $stockalmaproducto2_DATO;
        $stockParcial_DATO;        
        $stockMinproducto_OBJ = DB::select("SELECT pp.stockMin
                FROM detalleventa dd INNER JOIN producto pp ON dd.idProducto=pp.id
                WHERE dd.id=?", [$id]);
        $stockMin2producto_DATO=$stockMinproducto_OBJ[0]->stockMin;   
        
        $idproducto_OBJ = DB::select("SELECT pp.id
                FROM detalleventa dd INNER JOIN producto pp ON dd.idProducto=pp.id
                WHERE dd.id=?", [$id]);
        $idproducto_DATO=$idproducto_OBJ[0]->id; 
        
        $stockalmaproducto_OBJ=DB::select("SELECT v_stockalmacensucursal.stock as cantidad
                       FROM v_stockalmacensucursal
                       WHERE v_stockalmacensucursal.idproducto = ? 
                       AND v_stockalmacensucursal.idalmacen = ?", [$idproducto_DATO, $almacen]);                    
                    foreach ($stockalmaproducto_OBJ as $key => $value) 
                       {$stockalmaproducto2_DATO = $value->cantidad;}                                              
        $stockParcial_DATO=$stockalmaproducto2_DATO-$cantidad;
                    if($stockParcial_DATO<=$stockMin2producto_DATO)
                    {
                        $stockMin2producto_DATO=(string)$stockMin2producto_DATO;
                        $stockParcial_DATO=(string)$stockParcial_DATO;
                        $nombre_OBJ=DB::select("SELECT pp.nombre
                FROM detalleventa dd INNER JOIN producto pp ON dd.idProducto=pp.id
                WHERE dd.id=?",[$id]);
                        $nombre2_DATO=$nombre_OBJ[0]->nombre;
                       return response()->json(["mensaje" => "Stock del producto: ".$nombre2_DATO."("."stockParcial: ".$stockParcial_DATO.")quedo por debajo o igual de su stock Min. de Seguridad("."StockMin: ".$stockMin2producto_DATO.") Necesita Comprar mas productos"]);
                    }else
                    {
                          return response()->json(["mensaje" => "ninguno"]);
                    }
        }
        
        
        

}


