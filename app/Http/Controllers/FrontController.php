<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TipoProducto;
use App\Unidadmedida;
use App\Marca;
use App\Origen;
use App\Http\Requests;
use DB;
use Session;
use Carbon\Carbon;

class FrontController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view("PanelAdmin.index");
    }

    public function abc() {
        return view("PanelAdmin.abc");
    }
    
    public function maxmin() {
        return view("PanelAdmin.maxmin");
    }
    
    public function eqq() {
        return view("PanelAdmin.eqq");
    }
    
    
    public function ReportVentasporusuario() {
        return view("ReporteListado.ReportVentasporusuario");
    }

    public function ReporteKardexInventario() {
        return view("ReporteListado.ReporteKardexInventario");
    }

    public function Reporttodousurios() {
        return view("ReporteListado.Reporttodousurios");
    }

    public function Reportporusuario() {
        return view("ReporteListado.Reportporusuario");
    }

    public function Reportflujousuario() {
        return view("ReporteListado.Reportflujousuario");
    }

    public function Reportflujocompleto() {
        return view("ReporteListado.Reportflujocompleto");
    }

    public function Reportflujoporusuario() {
        return view("ReporteListado.Reportflujoporusuario");
    }

    public function ReportEgreso() {
        return view("ReporteListado.ReportEgreso");
    }

    public function ReportIngreso() {
        return view("ReporteListado.ReportIngreso");
    }

    public function cambiarpasswordusuario($id) {
        $idempleado = $id;
        $nombreempleado;
        $empleado = DB::table('empleado')
                ->select('nombre')
                ->where('eliminado', 0)
                ->where('id', $id)
                ->lists('nombre');
        foreach ($empleado as $key => $value) {
            $nombreempleado = $value;
        }
        return view('Empleado.Cambiarpassword', compact(['nombreempleado', 'idempleado']));
    }

    public function login() {
        return view('inicio');
    }

    public function categoria() {
        return view('Categoria.Categoria');
    }

    public function producto() {
        return view('Producto.Productos');
    }

    public function nuevoproducto() {
        return view('Producto.Addproductos');
    }

    public function Unidadmedida() {
        return view('Unidadmedida.Unidadmedida');
    }

    public function Marca() {
        return view('Marca.Marca');
    }

    public function Origen() {
        return view('Origen.Origen');
    }

    public function liingredientes() {
        return view('Ingrediente.Ingredientes');
    }

    public function vistacargo() {
        return view('Cargo.Cargoempleado');
    }

    public function vistaempleado() {
        return view('Empleado.vistaEmpleado');
    }

    public function nuevoempleado() {
        return view('Empleado.Addempleado');
    }

    public function vistadeventa() {
        return view('Ventas.VerVenta');
    }

    public function editarventas($id) {
        $estado = DB::table('venta')->select('etapa', 'estado', 'fecha', 'idCliente', 'idAlmacen', 'idPuntoVenta')->where('id', $id)->get();
        $clientes = DB::select("SELECT cliente.nombre, cliente.id, cliente.nit, cliente.razonSocial
                FROM cliente WHERE cliente.eliminado = 0");
        $Ciudad = DB::table('ciudad')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        //0=guardado 1=cobrado  2=anulado Gestionarfactura
        $moneda = DB::table('tipomoneda')->select('moneda', 'bs', 'id')->where('eliminado', 0)->get();
        $descuento = DB::table('tipodescuento')->select('id', 'nombre')->lists('nombre', 'id');
        foreach ($moneda as $key => $value) {
            $moneda1 = $value->moneda;
            $bs = $value->bs;
            $idm = $value->id;
        }
        $estados;
        $actual;
        $fecha;
        $idCliente = null;
        $nombre;
        $idalamcen;
        $puntoventa;
        $etapa;
        foreach ($estado as $key => $value) {
            $estados = $value->estado;
            $fecha = $value->fecha;
            $idCliente = $value->idCliente;
            $idalmacen = $value->idAlmacen;
            $puntoventa = $value->idPuntoVenta;
            $etapa = $value->etapa;
        }
        $alm = DB::select("SELECT almacen.nombre FROM almacen WHERE almacen.id = ?", [$idalmacen]);
        $a;
        foreach ($alm as $key => $value) {
            $a = $value->nombre;
        }
        $punt = DB::select("SELECT puntoventa.ventamultialmacen FROM puntoventa WHERE puntoventa.id = ?", [$puntoventa]);
        $p;
        foreach ($punt as $key => $value) {
            $p = $value->ventamultialmacen;
        }
        if ($estados == 1) {
            $actual = "Cobrado";
            return redirect('/listadeventa');
        }if ($estados == 0) {
            $actual = "Guardado";
        }if ($estados == 2) {
            $actual = "Anulado";
            return redirect('/listadeventa');
        }
        if ($estados == 4) {
            $actual = "Proforma";
        }
        if ($estados == 5) {
            $actual = "Credito";
            return redirect('/listadeventa');
        }
        $razon = null;
        $nit = null;
        $cliente = DB::table('cliente')->select('nombre', 'NIT', 'razonSocial')->where('id', $idCliente)->get();
        foreach ($cliente as $key => $value) {
            $nombre = $value->nombre;
            $razon = $value->razonSocial;
            $nit = $value->NIT;
        }
        $tipocliente = DB::table('tipocliente')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        $mesa = DB::table('mesa')->select('numeromesa', 'id')->where('eliminado', 0)->lists('numeromesa', 'id');
        $puntosdeventa = DB:: table('empleado')->select('empleado.nombre', 'puntoventa.id')
                ->join('puntoventa', 'puntoventa.idEmpleado', '=', 'empleado.id')
                ->join('usuario', 'empleado.id', '=', 'usuario.idEmpleado')
                ->where('empleado.eliminado', 0)
                ->where('puntoventa.puedevender', 0)
                ->lists('empleado.nombre', 'puntoventa.id');
        return view('Ventas.EditarVentas', compact('puntosdeventa', 'etapa', 'p', 'a', 'idalmacen', 'nit', 'razon', 'Ciudad', 'id', 'mesa', 'actual', 'fecha', 'idCliente', 'nombre', 'clientes', 'tipocliente', 'moneda1', 'bs', 'descuento', 'idm'));
    }

    public function venta($idventaultimo) {
        $date = Carbon::now();
        $fecha = $date->toDateString();
        $mesa = DB::table('mesa')->select('numeromesa', 'id')->where('eliminado', 0)->lists('numeromesa', 'id');
        $clientes = DB::select("SELECT cliente.nombre, cliente.id, cliente.nit
                FROM cliente WHERE cliente.eliminado = 0");
        $Ciudad = DB::table('ciudad')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        $estado = DB::table('venta')->select('etapa', 'estado', 'idAlmacen', 'idPuntoVenta')->where('id', $idventaultimo)->get();
        $moneda = DB::table('tipomoneda')->select('moneda', 'bs', 'id')->where('eliminado', 0)->get();
        $descuento = DB::table('tipodescuento')->select('id', 'nombre')->lists('nombre', 'id');
        foreach ($moneda as $key => $value) {
            $moneda1 = $value->moneda;
            $bs = $value->bs;
            $idm = $value->id;
        }
        $estados;
        $actual;
        $idCliente = null;
        $idalmacen;
        $puntoventa;
        $etapa;
        //$estado = DB::table('venta')->select('etapa', 'estado', 'idAlmacen', 'idPuntoVenta')->where('id', $idventaultimo)->get();
        foreach ($estado as $key => $value) {
            $estados = $value->estado;
            $idalmacen = $value->idAlmacen;
            $puntoventa = $value->idPuntoVenta;
            $etapa = $value->etapa;
        }
        $alm = DB::select("SELECT almacen.nombre FROM almacen WHERE almacen.id = ?", [$idalmacen]);
        $a;
        foreach ($alm as $key => $value) {
            $a = $value->nombre;
        }
        $punt = DB::select("SELECT puntoventa.ventamultialmacen FROM puntoventa WHERE puntoventa.id = ?", [$puntoventa]);
        $p;
        foreach ($punt as $key => $value) {
            $p = $value->ventamultialmacen;
        }
        if ($estados == 1) {
            $actual = "Cobrado";
            return redirect('/listadeventa');
        }if ($estados == 0) {
            $actual = "Guardado";
        }if ($estados == 2) {
            $actual = "Anulado";
            return redirect('/listadeventa');
        }
        if ($estados == 4) {
            $actual = "Proforma";
        }
        if ($estados == 5) {
            $actual = "Credito";
            return redirect('/listadeventa');
        }
        $razon;
        $nit;
        $nombre;
        $nombre = null;
        $razon = null;
        $nit = null;
        $tipocliente = DB::table('tipocliente')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        $puntosdeventa = DB:: table('empleado')->select('empleado.nombre', 'puntoventa.id')
                ->join('puntoventa', 'puntoventa.idEmpleado', '=', 'empleado.id')
                ->join('usuario', 'empleado.id', '=', 'usuario.idEmpleado')
                ->where('empleado.eliminado', 0)
                ->where('puntoventa.puedevender', 0)
                ->lists('empleado.nombre', 'puntoventa.id');
        return view('Ventas.Ventas', compact(['puntosdeventa', 'etapa', 'p', 'a', 'idalmacen', 'nombre', 'nit', 'razon', 'idm', 'Ciudad', 'idventaultimo', 'fecha', 'mesa', 'actual', 'clientes', 'idCliente', 'tipocliente', 'moneda1', 'bs', 'descuento']));
    }

    public function Gestionarturno() {
        return view('Turno.Gestionarturno');
    }

    public function Gestionarmodulo() {
        return view('Seguridad.GestionModulo');
    }

    public function Gestionarobjeto() {
        return view('Seguridad.GestionObjeto');
    }

    public function Gestionarperfil() {
        return view('Seguridad.GestionPerfil');
    }

    public function Gestionarpuntoventa() {
        return view('Sucursal.Puntoventa');
    }

    public function GestionarPais() {
        return view('Sucursal.Pais');
    }

    public function gestionarinventario() {
        return view('Inventario.GestionarInventario');
    }

    public function gestionaralmacen() {
        $sucursal = DB::table('sucursal')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        $empleado = DB::table('empleado')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        return view('Inventario.Almacen', compact('sucursal', 'empleado'));
    }

    public function gestionarmotivo() {
        return view('Inventario.Motivo');
    }

    public function Gestionarempresa() {
        return view('Empresa.Gestionarempresa');
    }

    public function Gestionarfactura() {
        return view('Motivo.ListaFactura');
    }

    public function nuevoinventario($idventaultimo) {
        $date = Carbon::now();
        $fecha = $date->toDateString();
        $idalmacen;
        $idalmacendestino;
        $estado = DB::table('inventario')->select('idAlmacen', 'idAlmacenDestino')->where('id', $idventaultimo)->get();
        foreach ($estado as $key => $value) {
            $idalmacen = $value->idAlmacen;
            $idalmacendestino = $value->idAlmacenDestino;
        }
        $Almacen = DB::table('almacen')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        $unidad = DB::table('unidadmedida')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        $motivo = DB::table('motivomovimiento')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        $producto = DB::table('producto')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        return view('Inventario.addinventario', compact(['idalmacendestino', 'idalmacen', 'idventaultimo', 'fecha', 'Almacen', 'unidad', 'motivo', 'producto']));
    }

    public function editarinventario($id) {
        $date = Carbon::now();
        $fecha = $date->toDateString();
        $Almacen = DB::table('almacen')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        $idalmacen;
        $idalmacendestino;
        $estado = DB::table('inventario')->select('idAlmacen', 'idAlmacenDestino')->where('id', $id)->get();
        foreach ($estado as $key => $value) {
            $idalmacen = $value->idAlmacen;
            $idalmacendestino = $value->idAlmacenDestino;
        }
        $unidad = DB::table('unidadmedida')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        $motivo = DB::table('motivomovimiento')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        $producto = DB::table('producto')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        return view('Inventario.editarinventario', compact(['idalmacendestino', 'idalmacen', 'id', 'fecha', 'Almacen', 'unidad', 'motivo', 'producto']));
    }

    public function GestionarTipoEgreso() {
        return view('EgresoIngreso.tipoegreso');
    }

    public function GestionarTipoIngreso() {
        return view('EgresoIngreso.tipoingreso');
    }

    public function GestionarEgreso() {
        $sucursal = DB::table('tipoegreso')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        $proveedor = DB::table('proveedor')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        $date = Carbon::now();
        $fecha = $date->toDateString();

        $concepto = DB::table('concepto')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        return view('EgresoIngreso.egreso', compact(['sucursal', 'proveedor', 'fecha', 'concepto']));
    }

    public function GestionarIngreso() {
        $sucursal = DB::table('tipoingreso')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        $date = Carbon::now();
        $fecha = $date->toDateString();

        $concepto = DB::table('concepto')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        return view('EgresoIngreso.ingreso', compact('sucursal', 'fecha', 'concepto'));
    }

//    public function nuevacompra($idventaultimo) {
//        $date = Carbon::now();
//        $fecha = $date->toDateString();
//        $proveedor = DB::table('proveedor')->select('nombre', 'id')->where('eliminado', 0)->orderBy('nombre', 'asc')->lists('nombre', 'id');
//        $unidad = DB::table('unidadmedida')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
//        $almacen = DB::table('almacen')->select('nombre', 'id')->where('eliminado', 0)->orderBy('nombre', 'asc')->lists('nombre', 'id');
//        return view('Compras.addCompras', compact(['idventaultimo', 'fecha', 'proveedor', 'unidad', 'almacen']));
//    }

    public function nuevacompra($idventaultimo) {
        $date = Carbon::now();
        $fecha = $date->toDateString();
        $proveedor = DB::table('proveedor')->select('nombre', 'id')->where('eliminado', 0)->orderBy('nombre', 'asc')->lists('nombre', 'id');
        $unidad = DB::table('unidadmedida')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        //$almacen = DB::select("SELECT idAlmacen FROM compra WHERE id=?",[$idventaultimo]);
        //$idalmacen=$almacen[0]->idAlmacen;        
        $almacen = DB::table('almacen')->select('nombre', 'id')->where('eliminado', 0)->orderBy('nombre', 'asc')->lists('nombre', 'id');
        $concepto = DB::table('concepto')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');        
        return view('Compras.addCompras', compact(['idventaultimo', 'fecha', 'proveedor', 'unidad', 'almacen', 'concepto']));
    }

    public function editarcompra($id) {
        $date = Carbon::now();
        $fecha = $date->toDateString();
        $almacen = DB::table('almacen')->select('nombre', 'id')->where('eliminado', 0)->orderBy('nombre', 'asc')->lists('nombre', 'id');
        $proveedor = DB::table('proveedor')->select('nombre', 'id')->where('eliminado', 0)->orderBy('nombre', 'asc')->lists('nombre', 'id');
        return view('Compras.EditarCompras', compact(['id', 'fecha', 'proveedor', 'almacen']));
    }

    public function GestionarProveedor() {
        $sucursal = DB::table('ciudad')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        return view('Compras.Proveedor', compact('sucursal'));
    }

    public function Gestionarcompras() {
        return view('Compras.compra');
    }

    public function Gestionarlibroorden() {
        $sucursal = DB::table('sucursal')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        return view('Seguridad.Gestionarlibroorden', compact('sucursal'));
    }

    public function Nuevolibroorden() {
        $sucursal = DB::table('sucursal')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        return view('Seguridad.Nuevolibroorden', compact('sucursal'));
    }

    public function Addproveedor() {
        $sucursal = DB::table('ciudad')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        return view('Compras.Addproveedor', compact('sucursal'));
    }

    public function addingreso() {
        $date = Carbon::now();
        $fecha = $date->toDateString();
        $sucursal = DB::table('tipoingreso')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');

        $concepto = DB::table('concepto')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        return view('EgresoIngreso.addingreso', compact('sucursal', 'fecha', 'concepto'));
    }

    public function addegreso() {
        $date = Carbon::now();
        $fecha = $date->toDateString();
        $concepto = DB::table('concepto')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        $sucursal = DB::table('tipoegreso')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        $proveedor = DB::table('Proveedor')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        return view('EgresoIngreso.addegreso', compact('sucursal', 'proveedor', 'fecha', 'concepto'));
    }

    public function Gestionarusuario($id) {
        $idempleado = $id;
        $nombreempleado;
        $empleado = DB::table('empleado')->select('nombre')->where('eliminado', 0)->where('id', $id)->lists('nombre');
        foreach ($empleado as $key => $value) {
            $nombreempleado = $value;
        }
        $perfil = DB::table('perfil')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        $sucursal = DB::table('sucursal')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        $almacen = DB::table('almacen')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        return view('Empleado.Usuario', compact(['nombreempleado', 'idempleado', 'perfil', 'sucursal', 'almacen']));
    }

    public function GestionarSucursal() {
        $Ciudad = DB::table('ciudad')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        $empresa = DB::table('empresa')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        return view('Sucursal.Sucursal', compact('Ciudad', 'empresa'));
    }

    public function Gestionarperfilobjeto($id) {
        $idproducto = $id;
        $producto;
        $nombre = DB::table('perfil')->select('nombre')->where('id', $id)->where('eliminado', 0)->lists('nombre');
        foreach ($nombre as $key => $value) {
            $producto = $value;
        }
        $objeto = DB::table('objeto')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        return view('Seguridad.Gestionperfilobjeto', compact(['idproducto', 'producto', 'objeto']));
    }

    public function vistaingrediente($id) {
        $idproducto = $id;
        $producto;
        $nombre = DB::table('producto')->select('nombre')->where('id', $id)->where('eliminado', 0)->lists('nombre');
        foreach ($nombre as $key => $value) {
            $producto = $value;
        }
        $unidads = DB::select("SELECT unidadmedida.abreviatura, unidadmedida.id FROM unidadmedida WHERE unidadmedida.eliminado = 0");
        $unidad = DB::table('unidadmedida')->select('abreviatura', 'id')->where('eliminado', 0)->lists('abreviatura', 'id');
        $ingrediente = DB::select("SELECT producto.nombre, producto.id FROM producto WHERE producto.eliminado = 0 AND producto.tipoproducto = 'ingrediente'");
        return view('Producto.Ingpro', compact(['idproducto', 'producto', 'unidad', 'ingrediente', 'unidads']));
    }

    public function GestionarMesa() {
        $Sucursal = DB::table('sucursal')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        return view('Mesa.Mesa', compact('Sucursal'));
    }

    public function GestionarMapamesa() {
        return view('Mesa.Mapa');
    }

    public function Ingpoducto() {
        $producto = DB::table('producto')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        $ingrediente = DB::table('ingrediente')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        $unidad = DB::table('unidadmedida')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        return view('Producto.Ingpro', compact(['producto', 'ingrediente', 'unidad']));
    }

    public function composicionprodcuto($id) {
        $idproducto = $id;
        $nombreproducto;
        $nombre = DB::table('producto')
                ->select('nombre')
                ->where('id', $id)
                ->lists('nombre');
        foreach ($nombre as $key => $value) {
            $nombreproducto = $value;
        }
        $productos = DB::select("SELECT producto.nombre, producto.id FROM producto WHERE producto.eliminado = 0 AND producto.tipoproducto <> 'ingrediente' AND producto.tipoproducto <> 'servicio' ");
        return view('Producto.ActualizarCompo', compact(['productos', 'nombreproducto', 'idproducto']));
    }

    public function comboproducto($id) {
        $idproducto = $id;
        $nombreproducto;
        $nombre = DB::table('producto')
                ->select('nombre')
                ->where('id', $id)
                ->lists('nombre');
        foreach ($nombre as $key => $value) {
            $nombreproducto = $value;
        }
        $productos = DB::select("SELECT producto.nombre, producto.id FROM producto WHERE producto.eliminado = 0 AND producto.tipoproducto <> 'ingrediente' AND producto.tipoproducto <> 'servicio' ");
        return view('Producto.ActualizarCombo', compact(['productos', 'nombreproducto', 'idproducto']));
    }

    public function GestionarReporte() {
        return view('Reporte.Gestionarreporte');
    }

    public function reporte() {
        $productos = DB::table('producto')->select('nombre', 'id', 'precioVenta')->where('eliminado', 0)->get('nombre', 'id', 'precioVenta');
        $pdf = \PDF::loadView('Reporte.producto', compact('productos'));
        return $pdf->download('producto.pdf');
    }

    public function reporteusuario() {
        $productos = DB::table('empleado')->select('empleado.nombre', 'empleado.genero', 'empleado.docIdentidad', 'cargo.nombre as cargo', 'empleado.id')
                ->join('cargo', 'cargo.id', '=', 'empleado.idCargo')
                ->where('empleado.eliminado', 0)
                ->get('nombre', 'genero', 'docIdentidad', 'cargo', 'id');
        $pdf = \PDF::loadView('Reporte.usuario', compact('productos'));
        return $pdf->stream();
    }

    public function reportecompra() {
        return view('Reporte.Compra');
    }

    public function Autorizaciones($id) {
        $productos = DB::table('perfil')->select('perfilobjeto.puedeGuardar', 'perfilobjeto.puedeModificar', 'perfilobjeto.puedeEliminar', 'perfilobjeto.puedeListar', 'perfilobjeto.puedeVerReporte', 'perfilobjeto.puedeImprimir', 'objeto.urlObjeto')
                ->join('perfilobjeto', 'perfilobjeto.idPerfil', '=', 'perfil.id')
                ->join('objeto', 'objeto.id', '=', 'perfilobjeto.idObjeto')
                ->join('modulo', 'modulo.id', '=', 'objeto.idModulo')
                ->where('perfil.id', $id)
                ->get();
        return response()->json($productos);
    }

    public function Descuentos() {
        return view("Descuento.Listadescuento");
    }

    public function Ventacruzada() {
        $producto = DB::table('producto')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        return view("ReporteListado.ReporteVentaCruzada", compact('producto'));
    }

    public function ReportRaking() {
        return view("ReporteListado.ReporteRakingimporte");
    }

    public function vistadeventares() {
        $idempleado = Session::get('idempleado');
        $idpuntoventa = Session::get('idpuntoventa');
        $datos = DB::select("CALL listarventas(?,?)", [ $idempleado, $idpuntoventa]);
        return view('Ventas.VerVentares', compact('datos'));
    }

    public function editarventasres($id) {
        $fechas;
        $estado = DB::table('venta')->select('etapa', 'estado', 'fecha', 'idCliente', 'idAlmacen', 'idPuntoVenta')->where('id', $id)->get();
        $clientes = DB::select("SELECT cliente.nombre, cliente.id, cliente.nit, cliente.razonSocial
                FROM cliente WHERE cliente.eliminado = 0");
        $Ciudad = DB::table('ciudad')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        $moneda = DB::table('tipomoneda')->select('moneda', 'bs', 'id')->where('eliminado', 0)->get();
        $descuento = DB::table('tipodescuento')->select('id', 'descuento')->lists('descuento', 'id');
        foreach ($moneda as $key => $value) {
            $moneda1 = $value->moneda;
            $bs = $value->bs;
            $idm = $value->id;
        }
        $estados;
        $actual;
        $idCliente = null;
        $idalmacen;
        $puntoventa;
        $etapa;
        foreach ($estado as $key => $value) {
            $estados = $value->estado;
            $fechas = $value->fecha;
            $idCliente = $value->idCliente;
            $idalmacen = $value->idAlmacen;
            $puntoventa = $value->idPuntoVenta;
            $etapa = $value->etapa;
        }
        if ($estados == 1) {
            $actual = "Cobrado";
            return redirect('/listadeventares');
        }if ($estados == 0) {
            $actual = "Guardado";
        }if ($estados == 2) {
            $actual = "Anulado";
            return redirect('/listadeventares');
        }
        if ($estados == 3) {
            $actual = "Programado";
        }
        if ($estados == 5) {
            $actual = "Credito";
            return redirect('/listadeventares');
        }
        $nombre;
        $razon = null;
        $nit = null;
        $cliente = DB::table('cliente')->select('nombre', 'NIT', 'razonSocial')->where('id', $idCliente)->get();
        foreach ($cliente as $key => $value) {
            $nombre = $value->nombre;
            $razon = $value->razonSocial;
            $nit = $value->NIT;
        }
        $alm = DB::select("SELECT almacen.nombre FROM almacen WHERE almacen.id = ?", [$idalmacen]);
        $a;
        foreach ($alm as $key => $value) {
            $a = $value->nombre;
        }
        $punt = DB::select("SELECT puntoventa.ventamultialmacen FROM puntoventa WHERE puntoventa.id = ?", [$puntoventa]);
        $p;
        foreach ($punt as $key => $value) {
            $p = $value->ventamultialmacen;
        }
        $fecha = Carbon::parse($fechas)->format('d/m/Y');
        $tipocliente = DB::table('tipocliente')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        $mesa = DB::table('mesa')->select('numeromesa', 'id')->where('eliminado', 0)->lists('numeromesa', 'id');
        $descuento = DB::table('tipodescuento')->select('id', 'descuento')->lists('descuento', 'id');
        $puntosdeventa = DB:: table('empleado')->select('empleado.nombre', 'puntoventa.id')
                        ->join('puntoventa', 'puntoventa.idEmpleado', '=', 'empleado.id')
                        ->where('empleado.eliminado', 0)->lists('empleado.nombre', 'puntoventa.id');
        return view('Ventas.EditarVentares', compact('idm', 'puntosdeventa', 'idalmacen', 'etapa', 'p', 'a', 'descuento', 'tipocliente', 'Ciudad', 'nit', 'razon', 'nombre', 'id', 'mesa', 'actual', 'fecha', 'idCliente', 'clientes'));
    }

    public function ventares($idventaultimo) {
        $date = Carbon::now();
        $fecha = $date->toDateString();
        $mesa = DB::table('mesa')->select('numeromesa', 'id')->where('eliminado', 0)->lists('numeromesa', 'id');
        $clientes = DB::select("SELECT cliente.nombre, cliente.id, cliente.nit
                FROM cliente WHERE cliente.eliminado = 0");
        $Ciudad = DB::table('ciudad')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        $estado = DB::table('venta')->select('etapa', 'estado', 'idAlmacen', 'idPuntoVenta')->where('id', $idventaultimo)->get();
        $moneda = DB::table('tipomoneda')->select('moneda', 'bs', 'id')->where('eliminado', 0)->get();
        $descuento = DB::table('tipodescuento')->select('id', 'descuento')->lists('descuento', 'id');
        foreach ($moneda as $key => $value) {
            $moneda1 = $value->moneda;
            $bs = $value->bs;
            $idm = $value->id;
        }
        $puntoventa;
        $estados;
        $actual;
        $idCliente = null;
        $idalmacen;
        $etapa;
        foreach ($estado as $key => $value) {
            $estados = $value->estado;
            $idalmacen = $value->idAlmacen;
            $puntoventa = $value->idPuntoVenta;
            $etapa = $value->etapa;
        }
        $alm = DB::select("SELECT almacen.nombre FROM almacen WHERE almacen.id = ?", [$idalmacen]);
        $a = null;
        foreach ($alm as $key => $value) {
            $a = $value->nombre;
        }
        if ($estados == 1) {
            $actual = "Cobrado";
            return redirect('/listadeventares');
        }if ($estados == 0) {
            $actual = "Guardado";
        }if ($estados == 2) {
            $actual = "Anulado";
            return redirect('/listadeventares');
        }if ($estados == 5) {
            $actual = "Credito";
            return redirect('/listadeventares');
        }
        $punt = DB::select("SELECT puntoventa.ventamultialmacen FROM puntoventa WHERE puntoventa.id = ?", [$puntoventa]);
        $p;
        foreach ($punt as $key => $value) {
            $p = $value->ventamultialmacen;
        }
        $razon;
        $nit;
        $nombre;
        $nombre = null;
        $razon = null;
        $nit = null;
        $tipocliente = DB::table('tipocliente')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        $puntosdeventa = DB:: table('empleado')->select('empleado.nombre', 'puntoventa.id')
                        ->join('puntoventa', 'puntoventa.idEmpleado', '=', 'empleado.id')
                        ->where('empleado.eliminado', 0)->lists('empleado.nombre', 'puntoventa.id');
        return view('Ventas.Ventasres', compact(['puntosdeventa', 'idalmacen', 'etapa', 'p', 'a', 'nombre', 'nit', 'razon', 'idm', 'Ciudad', 'idventaultimo', 'fecha', 'mesa', 'actual', 'clientes', 'idCliente', 'tipocliente', 'moneda1', 'bs', 'descuento']));
    }

    public function Reportelibroventa() {
        $sucursales = DB::table('sucursal')->select('nombre', 'id')->where('eliminado', 0)->orderBy('id', 'desc')->lists('nombre', 'id');
        return view("ReporteListado.ReporteLibroVenta", compact(['sucursales']));
    }

    public function Credito() {
        return view('Credito.Credito');
    }

    public function listarproformas() {
        return view('Proforma.proforma');
    }

    public function listaralquiler() {
        return view('Alquiler.alquiler');
    }

    public function gestionarcontador() {
        return view("Sucursal.Contador");
    }

    public function CompraCredito() {
        return view("Compras.Listarcredito");
    }

    public function listarcreditocompra() {
        $idempleado = Session::get('idempleado');
        $idpuntoventa = Session::get('idpuntoventa');
        $datos = DB::select("SELECT Compra.id ,
        Compra.fecha,
        Compra.hora,
        empleado.nombre,
        (SELECT SUM(pago.pagado) AS cuotasPagadas
        FROM (SELECT SUM(cobroacuota.`importe`) AS pagado, cuentaacobrar.`id`, cuentaacobrar.`idCompra`
            FROM cobroacuota, cuentaacobrar
            WHERE cobroacuota.`idCuentaaCobrar` = cuentaacobrar.`id`
            GROUP BY cuentaacobrar.`id`) AS pago
        WHERE pago.idCompra = Compra.`id`
        ) AS pgado,
        Compra.total,
   proveedor.nombre as cliente,
        Compra.saldoACobrar as saldoacobrar,
        Compra.aCuenta as aCuenta
        FROM Compra 
        INNER JOIN puntoventa
        INNER JOIN empleado
  INNER JOIN proveedor on proveedor.id=compra.idProveedor
        WHERE Compra.idpuntoventa=puntoventa.id 
                   AND puntoventa.idEmpleado=empleado.id
                   AND empleado.id=?
                   AND puntoventa.id=?
                   AND Compra.eliminado=0
                   AND Compra.formaPago = 'Credito'
        ORDER BY id DESC", [$idempleado, $idpuntoventa]);
        return response()->json($datos);
    }

    public function EstadoInventario() {
        return view("ReporteListado.ReporteEstadoInventario");
    }
    
     
    public function analisisABCreport() {
        return view("ReporteListado.ReporteanalisisABC");
    }
    
    public function eqqreport() {
        return view("ReporteListado.Reporteeqq");
    }
    //analisisABCreport
    

    public function Banco() {
        return view("Banco.Banco");
    }

    public function CuentaBancaria() {
        $banco = DB::table('banco')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        return view('Banco.CuentaBancaria', compact('banco'));
    }

    public function Concepto() {
        return view('Banco.Concepto');
    }

    public function TipoCliente() {
        return view('TipoCliente.TipoCliente');
    }

    public function GestionarTipoCliente() 
    {
        $tipocliente = DB::table('tipocliente')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        $Ciudad = DB::table('ciudad')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        $descuento = DB::table('tipodescuento')->select('id', 'nombre')->lists('nombre', 'id');
        return view('Cliente.Cliente', compact('tipocliente', 'Ciudad', 'descuento'));
    }

    public function GestionarMoneda() {
        return view("Moneda.Moneda");
    }

    public function CompraEfectivo() {
        return view("ReporteListado.CompraEfectivo");
    }

    public function CompraCredito1() {
        return view("ReporteListado.CompraCredito1");
    }

    public function ReporteMovimientoInventario() {
        return view("ReporteListado.ReportMovimientoInventario");
    }

    public function Reporttodousuriosporusuario() {
        return view("ReporteListado.Reporttodoporusurios");
    }

    public function ReportIngresoPorUsuario() {
        return view("ReporteListado.ReportIngresoPorUsuario");
    }

    public function ReportEgresoPorusuario() {
        return view("ReporteListado.ReportEgresoPorUsuario");
    }

    public function vistadeventaopt() {
        $idempleado = Session::get('idempleado');
        $idpuntoventa = Session::get('idpuntoventa');
        $datos = DB::select("CALL listarventas(?,?)", [ $idempleado, $idpuntoventa]);
        return view('Ventas.VerVentasopt', compact('datos'));
    }

    public function editarventasopt($id) {
        $estado = DB::table('venta')->select('etapa', 'estado', 'fecha', 'idCliente', 'idAlmacen', 'idPuntoVenta')->where('id', $id)->get();
        $clientes = DB::select("SELECT cliente.nombre, cliente.id, cliente.nit, cliente.razonSocial
                FROM cliente WHERE cliente.eliminado = 0");
        $Ciudad = DB::table('ciudad')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        //0=guardado 1=cobrado  2=anulado Gestionarfactura
        $moneda = DB::table('tipomoneda')->select('moneda', 'bs', 'id')->where('eliminado', 0)->get();
        $descuento = DB::table('tipodescuento')->select('id', 'descuento')->lists('descuento', 'id');
        foreach ($moneda as $key => $value) {
            $moneda1 = $value->moneda;
            $bs = $value->bs;
            $idm = $value->id;
        }
        $estados;
        $actual;
        $fecha;
        $idCliente = null;
        $nombre;
        $idalamcen;
        $puntoventa;
        $etapa;
        foreach ($estado as $key => $value) {
            $estados = $value->estado;
            $fecha = $value->fecha;
            $idCliente = $value->idCliente;
            $idalmacen = $value->idAlmacen;
            $puntoventa = $value->idPuntoVenta;
            $etapa = $value->etapa;
        }
        $alm = DB::select("SELECT almacen.nombre FROM almacen WHERE almacen.id = ?", [$idalmacen]);
        $a;
        foreach ($alm as $key => $value) {
            $a = $value->nombre;
        }
        $punt = DB::select("SELECT puntoventa.ventamultialmacen FROM puntoventa WHERE puntoventa.id = ?", [$puntoventa]);
        $p;
        foreach ($punt as $key => $value) {
            $p = $value->ventamultialmacen;
        }
        if ($estados == 1) {
            $actual = "Cobrado";
        }if ($estados == 0) {
            $actual = "Guardado";
        }if ($estados == 2) {
            $actual = "Anulado";
        }
        if ($estados == 4) {
            $actual = "Proforma";
        }
        if ($estados == 5) {
            $actual = "Credito";
        }
        $razon = null;
        $nit = null;
        $nombre = null;
        $cliente = DB::table('cliente')->select('nombre', 'NIT', 'razonSocial')->where('id', $idCliente)->get();
        foreach ($cliente as $key => $value) {
            $razon = $value->razonSocial;
            $nit = $value->NIT;
            $nombre = $value->nombre;
        }
        $tipocliente = DB::table('tipocliente')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        $mesa = DB::table('mesa')->select('numeromesa', 'id')->where('eliminado', 0)->lists('numeromesa', 'id');
        $puntosdeventa = DB:: table('empleado')->select('empleado.nombre', 'puntoventa.id')
                        ->join('puntoventa', 'puntoventa.idEmpleado', '=', 'empleado.id')
                        ->where('empleado.eliminado', 0)->lists('empleado.nombre', 'puntoventa.id');
        return view('Ventas.EditarVentaopt', compact('puntosdeventa', 'etapa', 'p', 'a', 'idalmacen', 'nit', 'razon', 'Ciudad', 'id', 'mesa', 'actual', 'fecha', 'idCliente', 'nombre', 'clientes', 'tipocliente', 'moneda1', 'bs', 'descuento', 'idm'));
    }

    public function ventaopt($idventaultimo) {
        $date = Carbon::now();
        $fecha = $date->toDateString();
        $mesa = DB::table('mesa')->select('numeromesa', 'id')->where('eliminado', 0)->lists('numeromesa', 'id');
        $clientes = DB::select("SELECT cliente.nombre, cliente.id, cliente.nit
                FROM cliente WHERE cliente.eliminado = 0");
        $Ciudad = DB::table('ciudad')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        $estado = DB::table('venta')->select('etapa', 'estado', 'idAlmacen', 'idPuntoVenta')->where('id', $idventaultimo)->get();
        $moneda = DB::table('tipomoneda')->select('moneda', 'bs', 'id')->where('eliminado', 0)->get();
        $descuento = DB::table('tipodescuento')->select('id', 'descuento')->lists('descuento', 'id');
        foreach ($moneda as $key => $value) {
            $moneda1 = $value->moneda;
            $bs = $value->bs;
            $idm = $value->id;
        }
        $estados;
        $actual;
        $idCliente = null;
        $idalmacen;
        $puntoventa;
        $etapa;
        foreach ($estado as $key => $value) {
            $estados = $value->estado;
            $idalmacen = $value->idAlmacen;
            $puntoventa = $value->idPuntoVenta;
            $etapa = $value->etapa;
        }
        $alm = DB::select("SELECT almacen.nombre FROM almacen WHERE almacen.id = ?", [$idalmacen]);
        $a;
        foreach ($alm as $key => $value) {
            $a = $value->nombre;
        }
        $punt = DB::select("SELECT puntoventa.ventamultialmacen FROM puntoventa WHERE puntoventa.id = ?", [$puntoventa]);
        $p;
        foreach ($punt as $key => $value) {
            $p = $value->ventamultialmacen;
        }
        if ($estados == 1) {
            $actual = "Cobrado";
        }if ($estados == 0) {
            $actual = "Guardado";
        }if ($estados == 2) {
            $actual = "Anulado";
        }if ($estados == 5) {
            $actual = "Credito";
        }
        $nombre = null;
        $razon = null;
        $nit = null;
        $tipocliente = DB::table('tipocliente')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        $puntosdeventa = DB:: table('empleado')->select('empleado.nombre', 'puntoventa.id')
                        ->join('puntoventa', 'puntoventa.idEmpleado', '=', 'empleado.id')
                        ->where('empleado.eliminado', 0)->lists('empleado.nombre', 'puntoventa.id');
        return view('Ventas.Ventasopt', compact(['puntosdeventa', 'etapa', 'p', 'a', 'idalmacen', 'nombre', 'nit', 'razon', 'idm', 'Ciudad', 'idventaultimo', 'fecha', 'mesa', 'actual', 'clientes', 'idCliente', 'tipocliente', 'moneda1', 'bs', 'descuento']));
    }

    public function ReportVentasComisionActual() {
        return view("ReporteListado.ReportVentasPorComissionActual");
    }

    public function ReportVentasComisionPorUsuario() {
        return view("ReporteListado.ReportVentasPorComissionPorUsuario");
    }

    public function ReportGarantiaAlquilerActual() {
        return view("ReporteListado.ReportGarantiaAlquilerActual");
    }

    public function ReportGarantiaAlquilerPorUsuario() {
        return view("ReporteListado.ReportGarantiaAlquilerPorUsuario");
    }

    public function detallecomprasactual() {
        return view("ReporteListado.ReportDetalleCompraActual");
    }

    public function detallecomprasporusuario() {
        return view("ReporteListado.ReportDetalleCompraPorUsuario");
    }

    public function productoresto() {
        return view('Producto.ProductosResto');
    }

    public function nuevoproductoresto() {
        return view('Producto.Addproductosresto');
    }

    public function alquiler($idventaultimo) {
        $id = $idventaultimo;
        $estado = DB::table('venta')->select('etapa', 'estado', 'fecha', 'idCliente', 'idAlmacen', 'idPuntoVenta')->where('id', $id)->get();
        $etapa;
        $estados = $estado[0]->estado;
        $idalmacen = $estado[0]->idAlmacen;
        $puntoventa = $estado[0]->idPuntoVenta;
        $etapa = $estado[0]->etapa;
        $fecha = $estado[0]->fecha;
        $tipomoneda = DB::table('tipomoneda')->select('moneda', 'bs', 'id')->where('eliminado', 0)->get();
        $moneda = $tipomoneda[0]->moneda;
        $bs = $tipomoneda[0]->bs;
        $alm = DB::select("SELECT almacen.nombre FROM almacen WHERE almacen.id = ?", [$idalmacen]);
        $almacen = $alm[0]->nombre;
        $clientes = DB::select("SELECT cliente.nombre, cliente.id, cliente.nit
                FROM cliente WHERE cliente.eliminado = 0");
        $idCliente = null;
        $Ciudad = DB::table('ciudad')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        $tipocliente = DB::table('tipocliente')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        $descuento = DB::table('tipodescuento')->select('id', 'descuento')->lists('descuento', 'id');
        $punt = DB::select("SELECT puntoventa.ventamultialmacen FROM puntoventa WHERE puntoventa.id = ?", [$puntoventa]);
        $p = $punt[0]->ventamultialmacen;
        return view('Alquiler.nuevoalquiler', compact('p', 'descuento', 'id', 'fecha', 'moneda', 'bs', 'almacen', 'idalmacen', 'clientes', 'idCliente', 'Ciudad', 'tipocliente'));
    }

    public function editaralquiler($idventaultimo) {
        $id = $idventaultimo;
        $estado = DB::table('venta')->select('etapa', 'estado', 'fecha', 'idCliente', 'idAlmacen', 'idPuntoVenta')->where('id', $id)->get();
        $etapa;
        $estados = $estado[0]->estado;
        $idalmacen = $estado[0]->idAlmacen;
        $puntoventa = $estado[0]->idPuntoVenta;
        $etapa = $estado[0]->etapa;
        $fecha = $estado[0]->fecha;
        $idCliente = $estado[0]->idCliente;
        $tipomoneda = DB::table('tipomoneda')->select('moneda', 'bs', 'id')->where('eliminado', 0)->get();
        $moneda = $tipomoneda[0]->moneda;
        $bs = $tipomoneda[0]->bs;
        $alm = DB::select("SELECT almacen.nombre FROM almacen WHERE almacen.id = ?", [$idalmacen]);
        $almacen = $alm[0]->nombre;
        $clientes = DB::select("SELECT cliente.nombre, cliente.id, cliente.nit
                FROM cliente WHERE cliente.eliminado = 0");
        $razon = null;
        $nit = null;
        $nombre = null;
        $cliente = DB::table('cliente')->select('nombre', 'NIT', 'razonSocial')->where('id', $idCliente)->get();
        foreach ($cliente as $key => $value) {
            $razon = $value->razonSocial;
            $nit = $value->NIT;
            $nombre = $value->nombre;
        }
        $Ciudad = DB::table('ciudad')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        $tipocliente = DB::table('tipocliente')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        $descuento = DB::table('tipodescuento')->select('id', 'descuento')->lists('descuento', 'id');
        $punt = DB::select("SELECT puntoventa.ventamultialmacen FROM puntoventa WHERE puntoventa.id = ?", [$puntoventa]);
        $p = $punt[0]->ventamultialmacen;
        return view('Alquiler.editaralquiler', compact('p', 'descuento', 'id', 'fecha', 'moneda', 'bs', 'almacen', 'idalmacen', 'clientes', 'idCliente', 'Ciudad', 'tipocliente'));
    }

    //Danny Cambios
    public function GestionarGastoCompra() {
        return view('Compras.GastoCompra');
    }

    public function VistaGastoCompra($idCompra) {
        $concepto = DB::table('concepto')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        $concepto1 = ['' => "Seleccione una Forma de Pago"];
//        $concepto1[] = $concepto;
        $concepto = array_merge($concepto1, $concepto);
//        dd($concepto);

        $gasto = DB::table('tipogastoscompra')->select('nombre', 'idTipoGasto')->where('eliminado', 0)->lists('nombre', 'idTipoGasto');

        $date = Carbon::now();
        $fecha = $date->toDateString();
        $proveedor = DB::table('Proveedor')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        if (empty($gasto)) {
            $gasto = ['' => "Seleccione una opcion"];
        }

        return view("Compras.planillaGastoCompra", compact(['fecha', 'idCompra', 'concepto', 'gasto', 'proveedor']));
    }

    public function ReportVentasanuladasyeliminadas() {
        return view("ReporteListado.ReportVentasanuladasyeliminadas");
    }

    public function ReportVentaACredito() {
        $clientes = DB::select("SELECT cliente.nombre, cliente.id, cliente.nit
                FROM cliente WHERE cliente.eliminado = 0");
        return view("ReporteListado.ReportVentaACredito", compact(['clientes']));
    }

    public function cambiarContraseniaDeUsuario($id) {
        $idempleado = $id;
        $nombreempleado;
        $empleado = DB::table('empleado')
                ->select('nombre')
                ->where('eliminado', 0)
                ->where('id', $id)
                ->lists('nombre');
        foreach ($empleado as $key => $value) {
            $nombreempleado = $value;
        }
        return view('Empleado.cambiarContrasenia', compact(['nombreempleado', 'idempleado']));
    }

    public function reportflujoingresosegresos() {
        return view("ReporteListado.ReportFlujoIngresosEgresos");
    }

    public function ReportDetalleVentaBelleMarie() {
        return view("ReporteListado.ReportDetalleVentaBelleMarie");
    }

    public function page404() {
        return view("errors.page-404");
    }
    
    
    
    ////////////////////////////////
    //////////VISAL PROFORMA//////////////////////
    ////////////////////////////////
    public function proforma($idventaultimo) {
        $date = Carbon::now();
        $fecha = $date->toDateString();
        $mesa = DB::table('mesa')->select('numeromesa', 'id')->where('eliminado', 0)->lists('numeromesa', 'id');
        $clientes = DB::select("SELECT cliente.nombre, cliente.id, cliente.nit
                FROM cliente WHERE cliente.eliminado = 0");
        $Ciudad = DB::table('ciudad')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        $estado = DB::table('proforma')->select('etapa', 'estado', 'idAlmacen', 'idPuntoVenta')->where('id', $idventaultimo)->get();
        $moneda = DB::table('tipomoneda')->select('moneda', 'bs', 'id')->where('eliminado', 0)->get();
        $descuento = DB::table('tipodescuento')->select('id', 'descuento')->lists('descuento', 'id');
        foreach ($moneda as $key => $value) {
            $moneda1 = $value->moneda;
            $bs = $value->bs;
            $idm = $value->id;
        }
        $estados;
        $actual;
        $idCliente = null;
        $idalmacen;
        $puntoventa;
        $etapa;
        foreach ($estado as $key => $value) {
            $estados = $value->estado;
            $idalmacen = $value->idAlmacen;
            $puntoventa = $value->idPuntoVenta;
            $etapa = $value->etapa;
        }
        $alm = DB::select("SELECT almacen.nombre FROM almacen WHERE almacen.id = ?", [$idalmacen]);
        $a;
        foreach ($alm as $key => $value) {
            $a = $value->nombre;
        }
        $punt = DB::select("SELECT puntoventa.ventamultialmacen FROM puntoventa WHERE puntoventa.id = ?", [$puntoventa]);
        $p;
        foreach ($punt as $key => $value) {
            $p = $value->ventamultialmacen;
        }


        //cambiar esto!!
        if ($estados == 1) {
            $actual = "Cobrado";
            return redirect('/listadeproforma');
        }if ($estados == 0) {
            $actual = "Guardado";
        }if ($estados == 2) {
            $actual = "Anulado";
            return redirect('/listadeproforma');
        }
        if ($estados == 4) {
            $actual = "Proforma";
        }
        if ($estados == 5) {
            $actual = "Credito";
            return redirect('/listadeproforma');
        }
        $razon;
        $nit;
        $nombre;
        $nombre = null;
        $razon = null;
        $nit = null;
        $tipocliente = DB::table('tipocliente')->select('nombre', 'id')->where('eliminado', 0)->lists('nombre', 'id');
        $puntosdeventa = DB:: table('empleado')->select('empleado.nombre', 'puntoventa.id')
                        ->join('puntoventa', 'puntoventa.idEmpleado', '=', 'empleado.id')
                        ->where('empleado.eliminado', 0)->lists('empleado.nombre', 'puntoventa.id');
        return view('Ventas.Proformas', compact(['puntosdeventa', 'etapa', 'p', 'a', 'idalmacen', 'nombre', 'nit', 'razon', 'idm', 'Ciudad', 'idventaultimo', 'fecha', 'mesa', 'actual', 'clientes', 'idCliente', 'tipocliente', 'moneda1', 'bs', 'descuento']));
    }
    
    public function vistadeproforma() {
        return view('Ventas.VerProforma');
    }

}
