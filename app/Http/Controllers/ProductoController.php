<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input as Input;
use Illuminate\Http\Request;
use App\Producto;
use DB;
use Excel;
use Session;
use Redirect;
use App\Http\Requests;

class ProductoController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //
    }

    /**
     * Show the form for creating a new resource.listaproductoportipo
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
            Producto::create($request->all());
            return response()->json($request->all());
        };
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
        $productos = DB::select('SELECT
                producto.tipoproducto as tipo,
                producto.idTipoProducto AS idcategoria,
                producto.idSubTipoProducto AS idsubcategoria,
                producto.imagen,
                producto.nombre,
                producto.id,
                producto.modelo,
                producto.estilo,
                producto.corte,
                producto.precioVenta ,
                producto.descripcion ,
                producto.codigoInterno,
                producto.codigoDeBarra ,
                producto.material,
                producto.usado,
                producto.color,
                producto.tamano,
                producto.peso ,
                producto.precioVentaCredito,
                producto.ventadirecta,
                producto.unidadesCaja ,
                producto.stockMin,
                producto.stockMax,
                producto.conStock,
                producto.idMarca AS marca,
                producto.idOrigen AS origen
                FROM producto 
                WHERE producto.eliminado = 0
                AND producto.id = ?', [$id]);
       
        return response()->json($productos);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $actua = DB::table('producto')->where('id', $id)->update(['nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precioVenta' => $request->precioVenta,
            'idTipoProducto' => $request->idTipoProducto,            
            'imagen' => $request->imagen,
            'tipoproducto' => $request->tipoproducto,
            'codigoInterno' => $request->codigoInterno,
            'codigoDeBarra' => $request->codigoDeBarra,
            'idOrigen' => $request->idOrigen,
            'idMarca' => $request->idMarca,
            'material' => $request->material,
            'color' => $request->color,
            'usado' => $request->usado,
            'tamano' => $request->tamano,
            'peso' => $request->peso,
            'unidadesCaja' => $request->unidadesCaja,
            'stockMin' => $request->stockMin,
            'stockMax' => $request->stockMax,
            'modelo' => $request->modelo,
            'estilo' => $request->estilo,
            'corte' => $request->corte,
            'precioVentaCredito' => $request->precioVentaCredito,
            'conStock' => $request->conStock,
             
             'costo_inventario' => $request->costo_inventario,
             'costo_pedido' => $request->costo_pedido                
                ]);
        return response()->json(["Mensaje" => "Exitoso"]);
    }

    /**
     * Remove the specified resource from storage.
     * view('PanelAdmin.Verproducto');
     * @param  int  $id listarporductos
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $actua = DB::table('producto')->where('id', $id)->update(['eliminado' => 1]);
        return response()->json(["Mensaje" => "Exitoso"]);
    }

    public function listarporductos() {
        $productos = DB::select(' 
            SELECT producto.tipoproducto as tipo,
                (select tipoproducto.nombre from tipoproducto where tipoproducto.id = producto.idTipoProducto)
                as categoria,
                (SELECT IFNULL(subtipoproducto.nombre,0)AS subcategoria  FROM subtipoproducto WHERE subtipoproducto.id = producto.idSubTipoProducto
                 ) AS subcategoria,
                producto.nombre,
                producto.id,
                producto.modelo,
                producto.estilo,
                producto.corte,
                producto.precioVenta ,
                producto.descripcion ,
                producto.codigoInterno,
                producto.codigoDeBarra ,
                producto.material,
                producto.usado,
                producto.color,
                producto.tamano,
                producto.peso ,
                producto.unidadesCaja ,
                producto.stockMin,
                producto.stockMax,
                (select marca.nombre from marca where marca.id = producto.idMarca) as marca,
                (select origen.nombre from origen where origen.id = producto.idOrigen) as origen
                FROM producto WHERE producto.eliminado = 0
                
      ');
        return response()->json($productos);
    }

    public function listacomposicion($id) {
        $composicion = DB::table('composicionproducto')->select('composicionproducto.id as idcomposicion', 'producto.nombre', 'composicionproducto.cantidad')
                        ->join('producto', 'producto.id', '=', 'composicionproducto.idProducto')
                        ->where('producto.eliminado', 0)->where('producto.id', $id)->get("idcomposicion", "nombre", "cantidad");
        return response()->json($composicion);
    }

    public function listaringrediente($id) {
        $ingrediente = DB::table('ingredienteproducto')->select('ingredienteproducto.id', 'ingrediente.nombre as ingrediente', 'unidadmedida.abreviatura as unidad', 'producto.nombre')
                ->join('producto', 'producto.id', '=', 'ingredienteproducto.id')
                ->join('unidadmedida', 'unidadmedida.id', '=', 'ingredienteproducto.idUnidadMedida')
                ->join('ingrediente', 'ingrediente.id', '=', 'ingredienteproducto.idIngrediente')
                ->where('ingredienteproducto.id', $id)
                ->where('ingredienteproducto.eliminado', 0)
                ->get("ingrediente", "unidad", "nombre", "id");
        return response()->json($ingrediente);
    }

    public function listaproductoportipo($tipo, $id, $idalmacen, $sucursal) {
        $productoportipo = DB::select("SELECT
            producto.id,
            producto.modelo,
            producto.estilo,
            producto.corte,
            producto.nombre,
            IF(? = 'Contado',productosucursal.precioVenta, productosucursal.precioVentaCredito) as precioVenta,
            producto.imagen,
            (SELECT
    `v_stockalmacensucursal`.`stock`
      FROM
    `v_stockalmacensucursal`
  WHERE v_stockalmacensucursal.`idproducto` = producto.id AND `v_stockalmacensucursal`.`idalmacen` = ?) AS stock
            FROM producto 
            INNER JOIN productosucursal ON producto.id=productosucursal.idproducto  AND productosucursal.idsucursal= ? 
                WHERE producto.eliminado = 0 AND producto.tipoproducto <> 'ingrediente' AND producto.idTipoProducto <> 'servicio' AND producto.ventadirecta = 0
                AND producto.idTipoProducto = ?", [$tipo, $idalmacen, $sucursal, $id]);
        return response()->json($productoportipo);
    }

    public function importExcelProductos() {
        $insert;
        $inserts = '';
        if (Input::hasFile('import_file')) {
            $path = Input::file('import_file')->getRealPath();
            $data = Excel::load($path, function($reader) {
                        
                    })->get();
            $insert = $data;
            foreach ($data as $key => $value) {
                DB::table('producto')->insert(['nombre' => $value->nombre,
                    'descripcion' => $value->descripcion,
                    'precioVenta' => $value->precioventa,
                    'idTipoProducto' => $value->idtipoproducto,
                    'imagen' => '/images/productoavatar.png',
                    'tipoproducto' => 'item',
                    'eliminado' => 0,
                    'created_at' => '0000-0-00 00:00:00',
                    'updated_at' => '0000-0-00 00:00:00',
                    'codigoInterno' => $value->codigointerno,
                    'codigoDeBarra' => $value->codigodebarra,
                    'idOrigen' => $value->idorigen,
                    'idMarca' => $value->idmarca,
                    'material' => $value->material,
                    'usado' => 'NO',
                    'color' => $value->color,
                    'tamano' => $value->tamano,
                    'unidadesCaja' => 0,
                    'stockMin' => 2,
                    'stockMax' => 2,
                    'ventadirecta' => 0,
                    'modelo' => $value->modelo,
                    'estilo' => $value->estilo,
                    'corte' => $value->corte]);
            }
            return redirect('/Productos');
        }
        return redirect('/Productos');
    }

    //======================================================
    //ARCHIVO PARA IMPORTAR EXCEL
    //=========================================================
    public function getDownload() {
        //PDF file is stored under project/public/download/info.pdf
        $file = public_path() . "/datos.csv";

        $headers = array(
            'Content-Type: application/csv',
        );

        return Response()->download($file, 'datos.csv', $headers);
    }

    public function productoresto(Request $request) {
        $actua = DB::table('producto')->where('id', $request->id)->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precioVenta' => $request->precioVenta,
            'idTipoProducto' => $request->idTipoProducto,
            'imagen' => $request->imagen,
            'tipoproducto' => $request->tipoproducto,
            'idOrigen' => $request->idOrigen,
            'idMarca' => $request->idMarca,
            'unidadesCaja' => $request->unidadesCaja,
            'stockMin' => $request->stockMin,
            'stockMax' => $request->stockMax,
            'ventadirecta' => $request->ventadirecta,
            'precioVentaCredito' => $request->precioVentaCredito,
            'conStock' => $request->conStock]);
        return response()->json(["Mensaje" => "Exitoso"]);
    }
    
    public function listarproductosucursal($idpunto) {
        $otro = DB::select
            ("
            SELECT p.id,p.nombre,p.descripcion,p.codigoInterno,
            p.costo_inventario,p.costo_pedido
            FROM producto p
            WHERE p.eliminado=0
            ");
    return response()->json($otro);           
    }
}
