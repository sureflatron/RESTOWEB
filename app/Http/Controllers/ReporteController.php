<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use Maatwebsite\Excel\Facades\Excel;
use JasperPHP\JasperPHP;
use Session;

class ReporteController extends Controller {

    public function destroy($id) {
        $pdf = \PDF::loadView('Reporte.usuario', compact('productos'));
        return $pdf->stream();
    }

    public function prueba() {
        return view('Reporte.Pruebaventas');
    }

    public function generarpdf($productos) {
        $pdf = \PDF::loadView('Reporte.cierrecaja', compact('productos'));
        return $pdf->download('ventas.pdf');
    }

//**************************reportusuarioactual*****************************************Generando reporte listadevestas Ventas************************************************************************
    public function reportusuarioactual($Fechainicio, $fechafin, $idEmpleado) {
        $mensaje;
        $totales;
        $productos = DB::select("CALL listadoventasconempleado(?, ?,?);", [$Fechainicio, $fechafin, $idEmpleado]);
        if ($productos == []) {
            $mensaje = "no se encontro Datos";
            return view('errors.503');
        } else {
            return view('ReporteHTML.ReporteVentaUsuarioActual', compact('Fechainicio', 'fechafin', 'idEmpleado'));
        }
    }

    public function reportusuario($Fechainicio, $fechafin) {
        $mensaje;
        $totales;
        $productos = DB::select("CALL listadevestas(?, ?);", [$Fechainicio, $fechafin]);
        if ($productos == []) {
            $mensaje = "no se encontro Datos";
            return view('errors.503');
        } else {
            return view('ReporteHTML.ReporteVentaUsuario', compact('Fechainicio', 'fechafin'));
        }
    }

    public function cargardatos($Fechainicio, $fechafin, $idEmpleado) {
        $productos = DB::select("CALL   listadoventasconempleado(?, ?,?);", [$Fechainicio, $fechafin, $idEmpleado]);
        return response()->json($productos);
    }

    public function cargardatostotalesventas($Fechainicio, $fechafin) {
        $productos = DB::select("CALL listadevestas(?, ?);", [$Fechainicio, $fechafin]);
        return response()->json($productos);
    }

//*******************************************************************Generando Flujo de caja************************************************************************ 
//    public function Flujocajasuarioactual($Fechainicio, $fechafin, $idEmpleado) {
//        $contarjeta;
//        $egreso;
//        $ingreso;
//        $contarjeta = DB::select("CALL sumadetarjeta(?, ?, ?);", [$Fechainicio, $fechafin, $idEmpleado]);
//        $importeegreso = DB::select("CALL listarsumaegreso(?, ?, ?);", [$Fechainicio, $fechafin, $idEmpleado]);
//        $importeingreso = DB::select("CALL listarsumaingreso(?, ?, ?);", [$Fechainicio, $fechafin, $idEmpleado]);
//        foreach ($contarjeta as $key => $value) {
//            $contarjeta = $value->importe;
//        }
//        foreach ($importeegreso as $key => $value) {
//            $egreso = $value->importe;
//        }
//        foreach ($importeingreso as $key => $value) {
//            $ingreso = $value->importe;
//        }
//        $totalsumado = $ingreso - $egreso;
//        $total = $totalsumado + $contarjeta;
//        return view('ReporteHTML.ReporteFlujoCajaUsuarioActual', compact(['Fechainicio', 'fechafin', 'idEmpleado', 'totalsumado', 'total', 'contarjeta', 'egreso', 'ingreso']));
//    }

    public function cargaringreso($Fechainicio, $fechafin, $idEmpleado) {
        $ventas = DB::select("CALL listarsumaventaconingresos(?, ?, ?);", [$Fechainicio, $fechafin, $idEmpleado]);
        return response()->json($ventas);
    }

    public function totalingreso($Fechainicio, $fechafin, $idEmpleado) {
        $importeingreso = DB::select("CALL listarsumaingreso(?, ?, ?);", [$Fechainicio, $fechafin, $idEmpleado]);
        return response()->json($importeingreso);
    }

    public function cargaregresos($Fechainicio, $fechafin, $idEmpleado) {
        $compras = DB::select("CALL listarsumconcomprasegreso(?, ?, ?);", [$Fechainicio, $fechafin, $idEmpleado]);
        return response()->json($compras);
    }

    public function totalegreso($Fechainicio, $fechafin, $idEmpleado) {
        $importeegreso = DB::select("CALL listarsumaegreso(?, ?, ?);", [$Fechainicio, $fechafin, $idEmpleado]);
        return response()->json($importeegreso);
    }

//*******************************************************************Generando Flujo de caja sumadetarjeta************************************************************************
//egresosumasinempleado
    public function Flujocajausuario($Fechainicio, $fechafin) {
        $contarjeta;
        $egreso;
        $ingreso;
        $contarjeta = DB::select("CALL tarjetasinempleado(?, ?);", [$Fechainicio, $fechafin]);
        $importeegreso = DB::select("CALL egresosumasinempleado(?, ?);", [$Fechainicio, $fechafin]);
        $importeingreso = DB::select("CALL sumasinempleado(?, ?);", [$Fechainicio, $fechafin]);
        foreach ($contarjeta as $key => $value) {
            $contarjeta = $value->importe;
        }
        foreach ($importeegreso as $key => $value) {
            $egreso = $value->importe;
        }
        foreach ($importeingreso as $key => $value) {
            $ingreso = $value->importe;
        }
        $totalsumado = $ingreso - $egreso;
        $total = $totalsumado + $contarjeta;
        return view('ReporteHTML.ReporteFlujoCajatotal', compact(['Fechainicio', 'fechafin', 'idEmpleado', 'totalsumado', 'total']));
    }

    public function cargaringresototal($Fechainicio, $fechafin) {
        $ventas = DB::select("CALL sinempleado(?, ?);", [$Fechainicio, $fechafin]);
        return response()->json($ventas);
    }

    public function totalingresototal($Fechainicio, $fechafin) {
        $importeingreso = DB::select("CALL sumasinempleado(?, ?);", [$Fechainicio, $fechafin]);
        return response()->json($importeingreso);
    }

//
    public function cargaregresostotal($Fechainicio, $fechafin) {
        $compras = DB::select("CALL sumasinempleadoegre(?, ?);", [$Fechainicio, $fechafin]);
        return response()->json($compras);
    }

    public function totalegresototal($Fechainicio, $fechafin) {
        $importeegreso = DB::select("CALL egresosumasinempleado(?, ?);", [$Fechainicio, $fechafin]);
        return response()->json($importeegreso);
    }

    public function totaltarjeta($Fechainicio, $fechafin) {
        $contarjeta = DB::select("CALL tarjetasinempleado(?, ?);", [$Fechainicio, $fechafin]);
        return response()->json($contarjeta);
    }

//*******************************************************************Generando Egreso************************************************************************

    public function reporteEgresoCompra($Fechainicio, $fechafin) {
        $ingreso;
        $importeegreso = DB::select("CALL egresosumasinempleado(?, ?);", [$Fechainicio, $fechafin]);
        foreach ($importeegreso as $key => $value) {
            $ingreso = $value->importe;
        }
        return view('ReporteHTML.ReporteEgreso', compact(['Fechainicio', 'fechafin', 'ingreso']));
    }

    public function egresocompras($Fechainicio, $fechafin) {
        $compras = DB::select("CALL sumasinempleadoegre(?, ?);", [$Fechainicio, $fechafin]);
        return response()->json($compras);
    }

//*******************************************************************Generando Excel Ventas************************************************************************

    public function ReporteVentassinusuario($Fechainicio, $fechafin) {
        $products = DB::select("CALL listadevestas(?, ?);", [$Fechainicio, $fechafin]);
        Excel::create('ReporteVentassinusuario', function($ReporteVentassinusuario) use($products) {
            $ReporteVentassinusuario->sheet('Ventas Totales', function($sheet)use($products) {
                $sheet->prependRow(array('idVenta', 'fecha', 'formaPago', 'cliente', 'sucursal', 'total'));
                foreach ($products as $key => $value) {
                    $sheet->rows(array(array($value->idventa,
                            $value->fecha,
                            $value->formaPago,
                            $value->cliente,
                            $value->sucursal,
                            $value->total)
                    ));
                }
            });
        })->export('xls');
    }

///prueba
    public function ReporteVentasusuario($Fechainicio, $fechafin, $idEmpleado) {
        $products = DB::select("CALL  listadoventasconempleado(?, ?,?);", [$Fechainicio, $fechafin, $idEmpleado]);
        Excel::create('ReporteVentasusuario', function($ReporteVentasusuario) use($products) {
            $ReporteVentasusuario->sheet('Ventas por usario', function($sheet)use($products) {
                $sheet->prependRow(array('idVenta', 'fecha', 'formaPago', 'cliente', 'sucursal', 'total'));
                foreach ($products as $key => $value) {
                    $sheet->rows(array(array($value->idventa,
                            $value->fecha,
                            $value->formaPago,
                            $value->cliente,
                            $value->sucursal,
                            $value->total)));
                }
            });
        })->export('xls');
    }

//*******************************************************************Generando Excel Flujo************************************************************************
    public function Reporteflujocajaempleado($Fechainicio, $fechafin, $idEmpleado) {
        $ventas = DB::select("CALL listarsumaventaconingresos(?, ?, ?);", [$Fechainicio, $fechafin, $idEmpleado]);
        $compras = DB::select("CALL listarsumconcomprasegreso(?, ?, ?);", [$Fechainicio, $fechafin, $idEmpleado]);
        $contarjeta = DB::select("CALL sumadetarjeta(?, ?, ?);", [$Fechainicio, $fechafin, $idEmpleado]);
        Excel::create('ReporteFlujoCajaporusuario', function($Reporteflujocajaempleado) use($ventas, $contarjeta, $compras) {
            $Reporteflujocajaempleado->sheet('Ventas', function($sheet)use($ventas, $contarjeta) {
                $sheet->prependRow(array('Tipo', 'ID', 'Fecha', 'Hora', 'Empleado', 'importe'));
                foreach ($ventas as $key => $value) {
                    $sheet->rows(array(array($value->Tipo,
                            $value->id,
                            $value->fecha,
                            $value->hora,
                            $value->empleado,
                            $value->importe)));
                }
                $sheet->cell('G2', function($cell) use ($contarjeta) {
                    foreach ($contarjeta as $key => $value) {
                        $cell->setValue($value->importe);
                    }
                });
            });
            $Reporteflujocajaempleado->sheet('Compras', function($sheet2)use($compras) {
                $sheet2->prependRow(array('Tipo', 'ID', 'Fecha', 'Hora', 'Empleado', 'importe'));
                foreach ($compras as $key => $value) {
                    $sheet2->rows(array(array($value->Tipo,
                            $value->id,
                            $value->fecha,
                            $value->hora,
                            $value->empleado,
                            $value->importe)
                    ));
                }
            });
        })->export('xls');
    }

    public function Reporteflujocajasinempleado($Fechainicio, $fechafin) {
        $ventas = DB::select("CALL sinempleado(?, ?);", [$Fechainicio, $fechafin]);
        $compras = DB::select("CALL sumasinempleadoegre(?, ?);", [$Fechainicio, $fechafin]);
        $contarjeta = DB::select("CALL tarjetasinempleado(?, ?);", [$Fechainicio, $fechafin]);
        Excel::create('ReporteFlujoCajaporusuario', function($Reporteflujocajasinempleado) use($ventas, $contarjeta, $compras) {
            $Reporteflujocajasinempleado->sheet('Ventas', function($sheet)use($ventas, $contarjeta) {
                $sheet->prependRow(array('Tipo', 'ID', 'Fecha', 'Hora', 'Empleado', 'importe', 'TotalTarjeta'));
                foreach ($ventas as $key => $value) {
                    $sheet->rows(array(array($value->Tipo,
                            $value->id,
                            $value->fecha,
                            $value->hora,
                            $value->empleado,
                            $value->importe)));
                }
                $sheet->cell('G2', function($cell) use ($contarjeta) {
                    foreach ($contarjeta as $key => $value) {
                        $cell->setValue($value->importe);
                    }
                });
            });
            $Reporteflujocajasinempleado->sheet('Compras', function($sheet2)use($compras) {
                $sheet2->prependRow(array('Tipo', 'ID', 'Fecha', 'Hora', 'Empleado', 'importe'));
                foreach ($compras as $key => $value) {
                    $sheet2->rows(array(array($value->Tipo,
                            $value->id,
                            $value->fecha,
                            $value->hora,
                            $value->empleado,
                            $value->importe)));
                }
//importeingreso
            });
        })->export('xls');
    }

//----------------------------------------------------------------------------------------------------------------------------------
    public function descargarreporteegresocompras($Fechainicio, $fechafin) {
        $compras = DB::select("CALL sumasinempleadoegre(?, ?);", [$Fechainicio, $fechafin]);
        Excel::create('Compras y Egreso', function($descargarreporteegresocompras) use($compras) {
            $descargarreporteegresocompras->sheet('Ventas por usario', function($sheet)use($compras) {
                $sheet->prependRow(array('idVenta', 'fecha', 'formaPago', 'cliente', 'sucursal', 'total'));
                foreach ($compras as $key => $value) {
                    $sheet->rows(array(array($value->Tipo,
                            $value->id,
                            $value->fecha,
                            $value->hora,
                            $value->empleado,
                            $value->importe)));
                }
            });
        })->export('xls');
    }

//    public function ReporteKardexInventario($fechaInicio, $fechaFin, $idProducto, $idAlmacen) {
//        $mensaje;
//        $totales;
//        $productos = DB::select("CALL kardexinventario(?, ?, ?);", [$idProducto, $fechaInicio, $fechaFin]);
//        if ($productos == []) {
//            $mensaje = "no se encontro Datos";
//            return view('errors.503');
//        } else {
//            return view('ReporteHTML.ReporteKardex', compact('fechaInicio', 'fechaFin', 'idProducto', 'idAlmacen'));
//        }
//    }

    public function ReporteDatosKardex($fechaInicio, $fechaFin, $idProducto, $idAlmacen) {
        $productos = DB::select("CALL kardexinventario(?, ?, ?);", [$idProducto, $fechaInicio, $fechaFin]);
        return response()->json($productos);
    }

    public function descargarreportekardex($fechaInicio, $fechaFin, $idProducto, $idAlmacen) {
        $compras = DB::select("CALL kardexinventario(?, ?, ?);", [$idProducto, $fechaInicio, $fechaFin]);
        Excel::create('Kardex de Inventario', function($descargarreportekardex)
                use($compras) {
            $descargarreportekardex->sheet('Kardex', function($sheet)use($compras) {
                $sheet->prependRow(array('fecha', 'id transaccion', 'origen transaccion', 'ingresos', 'egresos'));
                foreach ($compras as $key => $value) {
                    $sheet->rows(array(
                        array($value->fecha,
                            $value->transaccion,
                            $value->origen,
                            $value->ingresos,
                            $value->egresos)));
                }
            });
        })->export('xls');
    }

    public function ReportVentacruzdas($id) {
        $producto;
        $nombre = DB::table('producto')->select('nombre')->where('id', $id)->where('eliminado', 0)->lists('nombre');
        foreach ($nombre as $key => $value) {
            $producto = $value;
        }
        return view('ReporteHTML.ReportVentaCruzadas', compact(['id', 'producto']));
    }

    public function Reportkingproducto($Fechainicio, $fechafin) {
        return view('ReporteHTML.ReporteRaking', compact(['Fechainicio', 'fechafin']));
    }

    public function CargarReportVentacruzdas($id) {
        $productos = DB::select("CALL  Ventacruzadas(?);", [$id]);
        return response()->json($productos);
    }

    public function ReportRakingimporte($Fechainicio, $fechafin) {
        return view('ReporteHTML.ReporteRakingImporte', compact(['Fechainicio', 'fechafin']));
    }

    public function ReportRakingtotal($Fechainicio, $fechafin) {
        return view('ReporteHTML.ReporteRakingtotal', compact(['Fechainicio', 'fechafin']));
    }

    public function Datorakingproducto($Fechainicio, $fechafin) {
        $productos = DB::select("CALL  rakingproducto(?, ?);", [$Fechainicio, $fechafin]);
        return response()->json($productos);
    }

    public function DatoRakingtotal($Fechainicio, $fechafin) {
        $productos = DB::select("CALL  rakingProductocantidad(?, ?);", [$Fechainicio, $fechafin]);
        return response()->json($productos);
    }

    public function DatoRakingimporte($Fechainicio, $fechafin) {
        $productos = DB::select("CALL rakingProductototal(?, ?);", [$Fechainicio, $fechafin]);
        return response()->json($productos);
    }

    public function descargarVentacruzada($id) {
        $products = DB::select("CALL Ventacruzadas(?);", [$id]);
        $producto;
        $nombre = DB::table('producto')->select('nombre')->where('id', $id)->where('eliminado', 0)->lists('nombre');
        foreach ($nombre as $key => $value) {
            $producto = $value;
        }
        Excel::create('Venta cruzadas', function($descargarVentacruzada) use($products, $producto) {
            $descargarVentacruzada->sheet($producto, function($sheet)use($products) {
                $sheet->prependRow(array('Producto', 'Cantidad'));
                foreach ($products as $key => $value) {
                    $sheet->rows(array(array($value->nombre, $value->Cantidad)));
                }
            });
        })->export('xls');
    }

    public function descargarRakingimporte($Fechainicio, $fechafin) {
        $products = DB::select("CALL rakingProductocantidad(?, ?);", [$Fechainicio, $fechafin]);
        Excel::create('Ranking por totales Vendido', function($descargarRakingtotal) use($products) {
            $descargarRakingtotal->sheet('Ventas Totales', function($sheet)use($products) {
                $sheet->prependRow(array('Producto', 'Cantidad Vendida', 'ImporteTotal', 'Sucursal'));
                foreach ($products as $key => $value) {
                    $sheet->rows(array(array($value->Producto,
                            $value->Cantidadvendida,
                            $value->ImporteTotal,
                            $value->Sucursal)
                    ));
                }
            });
        })->export('xls');
    }

    public function descargarRakingtotal($Fechainicio, $fechafin) {
        $products = DB::select("CALL rakingProductototal(?, ?);", [$Fechainicio, $fechafin]);
        Excel::create('RAking por Total', function($descargarRakingtotal) use($products) {
            $descargarRakingtotal->sheet('Ventas Totales', function($sheet)use($products) {
                $sheet->prependRow(array('Producto', 'Cantidad Vendida', 'ImporteTotal', 'Sucursal'));
                foreach ($products as $key => $value) {
                    $sheet->rows(array(array($value->Producto,
                            $value->Cantidadvendido,
                            $value->ImporteTotal,
                            $value->Sucursal)
                    ));
                }
            });
        })->export('xls');
    }

    public function reporlibroventaexcel($Fechainicio, $fechafin, $idSucursal) {
        $products = DB::select("CALL reportelibroventa(?,?,?);", [$Fechainicio, $fechafin, $idSucursal]);
        Excel::create('rptLibroVentaEstandar' . $Fechainicio . 'al' . $fechafin, function($reporlibroventaexcel) use($products, $Fechainicio, $fechafin) {
            $reporlibroventaexcel->sheet('rptLibroVentaEstandar', function($sheet)use($products, $Fechainicio, $fechafin) {
                $sheet->prependRow(1, array(
                    'Libro de Ventas ' . $Fechainicio . ' al ' . $fechafin
                ));
                $sheet->mergeCells('A1:p1');
                $sheet->cells('A1:P1', function($cells) {
                    $cells->setFontFamily('Arial');
                    $cells->setFontWeight('bold');
                    $cells->setFontSize(16);
                });
                $sheet->prependRow(2, array('Nº', 'FECHA DE FACTURA', 'Nº DE FACTURA', 'Nº DE AUTORIZACIÓN', 'ESTADO', 'NIT / CI CLIENTE', 'NOMBRE O RAZÓN SOCIAL', 'IMPORTE TOTAL DE LA VENTA'
                    , 'IMPORTE ICE / IEHD / TASAS', 'EXPORTACIONES Y OPERACIONES EXENTAS', 'VENTAS GRAVADAS A TASA CERO', 'SUBTOTAL', 'DESCUENTOS, BONIFICACIONES Y REBAJAS OTORGADAS', 'IMPORTE BASE PARA DEBITO FISCAL', 'DÉBITO FISCAL', 'CÓDIGO DE CONTROL'));
                $sheet->cells('A2:P2', function($cells) {
                    $cells->setFontFamily('Arial');
                    $cells->setFontWeight('bold');
                    $cells->setFontSize(6);
                });
                $sheet->setAutoFilter('A2:P2');
                $sheet->setSize(array(
                    'A1' => array(
                        'width' => 50
                    )
                ));
                $sheet->setHorizontalCentered(false);
                $sheet->setverticalCentered(false);
                $sheet->setAutoSize(true);
                $contador = 0;
                foreach ($products as $key => $value) {
                    $contador++;
                    $totaliva = $value->total * 0.13;
                    $sheet->rows(array(array($contador,
                            $value->fecha,
                            $value->nroFactura,
                            $value->nroAutorizacion,
                            $value->estado,
                            $value->NIT,
                            $value->razonSocial,
                            $value->total,
                            $value->tasa,
                            $value->exportaciones,
                            $value->ventascero,
                            $value->total,
                            $value->DESCUENTOS,
                            $value->total,
                            $totaliva, $value->codigoControl)));
                }
            });
        })->export('xlsx');
    }

    function reporteUsuarioActualPorUsuario($Fechainicio, $fechafin, $idempleado, $id, $exte, $almacen) {
        $output = public_path() . '/report/' . 'ListadoVentas';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/listadoVentasPorUsuario.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $idempleado, "id" => $id, "almacen" => $almacen), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ListadoVentas.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteUsuario($Fechainicio, $fechafin, $idempleado, $exte, $almacen) {
        $output = public_path() . '/report/' . 'ListadoVentas';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/listadoVentasPorSucursal.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $idempleado, "id" => 0, "almacen" => $almacen), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ListadoVentas.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteUsuarioActualPorUsuarioNoAlmacen($Fechainicio, $fechafin, $idempleado, $id, $exte) {
        $output = public_path() . '/report/' . 'ListadoVentas';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/listadoVentasPorUsuarioNoAlmacen.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $idempleado, "id" => $id), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ListadoVentasDe' . $Fechainicio . "Al" . $fechafin . "." . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteDetalleVenta($Fechainicio, $fechafin, $idempleado1, $idusuario1, $exte, $sucursal1) {
        $output = public_path() . '/report/' . 'DetalleVentas';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/ventaDetalleVenta.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $idusuario1, "sucursal" => $sucursal1, "idusuario" => $idempleado1), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'DetalleVentasDe' . $Fechainicio . 'Al' . $fechafin . '.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteDetalleVentaNoAlmacen($Fechainicio, $fechafin, $idempleado, $idusuario, $exte) {
        $output = public_path() . '/report/' . 'DetalleVentas';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/ventaDetalleVentaNoalmacen.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $idempleado, "idusuario" => $idusuario), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'DetalleVentasDe' . $Fechainicio . 'Al' . $fechafin . '.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteDetalleVentaSinSucursalSinEmpleadoSinSucursal($Fechainicio, $fechafin, $idempleado, $exte) {
        $output = public_path() . '/report/' . 'DetalleVentas';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/ventaDetalleVentaNoAlmacenNoSucursalSinEmpleado.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $idempleado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'DetalleVentas.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function ventaDetalleVentaConSucursalSinAlmacenSinEmpleado($Fechainicio, $fechafin, $idempleado, $exte, $sucursal) {
        $output = public_path() . '/report/' . 'DetalleVentas';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/ventaDetalleVentaConSucursalSinAlmacenSinEmpleado.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $idempleado, "sucursal" => $sucursal), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'DetalleVentas.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reportDetalleVentaConSucursalConEmpleadoConAlmacen($Fechainicio, $fechafin, $idusuario, $idempleado, $exte, $sucursal, $almacen) {
        $output = public_path() . '/report/' . 'DetalleVentas';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/ventaDetalleVentaConSucursaConAlmacenConEmpleado.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idusuario" => $idempleado, "idempleado" => $idusuario, "sucursal" => $sucursal, "almacen" => $almacen), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'DetalleVentas.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteUsuarioActual($Fechainicio, $fechafin, $idempleado, $exte) {
        $output = public_path() . '/report/' . 'ListadoVentas';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/ventasPorUsuario/listadoVentas.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $idempleado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ListadoVentas.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteEgresoCompraConEmpleado($Fechainicio, $fechafin, $usuariologueado, $empleado, $exte) {
        $output = public_path() . '/report/' . 'ReporteEgreso';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/listadoEgresoConEmpleadoSinSucursal.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $usuariologueado, "idusuario" => $empleado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ReporteEgreso.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteEgresoCompraSinEmpleado($Fechainicio, $fechafin, $usuariologueado, $exte) {
        $output = public_path() . '/report/' . 'ReporteEgreso';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/listadoEgresoSinEmpleadoSinSucursal.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $usuariologueado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ReporteEgreso.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteEgreso($Fechainicio, $fechafin, $usuariologueado, $idempleado, $exte) {
        $output = public_path() . '/report/' . 'ReporteEgreso';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/listadoEgresoConEmpleadoSinSucursal.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idusuario" => $idempleado, "idempleado" => $usuariologueado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ReporteEgreso.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteIngreso($Fechainicio, $fechafin, $usuarioLogeado, $idempleado, $exte) {
        $output = public_path() . '/report/' . 'ReporteIngreso';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/listadoIngresoConEmpleadoSinSucursal.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $usuarioLogeado, "idusuario" => $idempleado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ReporteIngreso.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteIngresoSinEmpleado($Fechainicio, $fechafin, $usuarioLogeado, $exte) {
        $output = public_path() . '/report/' . 'ReporteIngreso';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/listadoIngresoSinEmpleado.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $usuarioLogeado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ReporteIngreso.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    public function ReporteKardexInventario($Fechainicio, $fechafin, $idproducto, $idEmpleado, $exte) {
        $output = public_path() . '/report/' . 'ReporteKardexInventario';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/kardexInventario.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idproducto" => $idproducto, "idempleado" => $idEmpleado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ReporteKardexInventario.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    public function reportKardexInventarioConSucursal($Fechainicio, $fechafin, $idproducto, $idEmpleado, $exte, $sucursal) {
        $output = public_path() . '/report/' . 'ReporteKardexInventario';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/kardexInventarioConSucursal.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idproducto" => $idproducto, "idempleado" => $idEmpleado, "idsucursal" => $sucursal), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ReporteKardexInventario.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    public function reportKardexInventarioConSucursalConAlmacen($Fechainicio, $fechafin, $idproducto, $idEmpleado, $exte, $almacen, $sucursal) {
        $output = public_path() . '/report/' . 'kardexInventarioConSucursalConAlmacen';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/kardexInventarioConSucursalConAlmacen.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idproducto" => $idproducto, "idempleado" => $idEmpleado, "idalmacen" => $almacen, "idsucursal" => $sucursal), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ReporteKardexInventario.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteReportVentacruzdas($idproducto, $idempleado, $exte) {
        $output = public_path() . '/report/' . 'VentasCruzadas';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/ventasCruzadas.jrxml', $output, array($extencion), array("idproducto" => $idproducto, "idempleado" => $idempleado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'VentasCruzadas.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteEstadoInventario($idempleado, $exte) {
        $output = public_path() . '/report/' . 'EstadoInvenario';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/estadoInventario1.jrxml', $output, array($extencion), array("idempleado" => $idempleado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'AnalisisAlmacen.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }
    
    
   function reporteeqq($idempleado, $exte) {
        $output = public_path() . '/report/' . 'analisiseqq';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/analisiseqq.jrxml', $output, array($extencion), array("idempleado" => $idempleado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'analisiseqq.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }
    function reporteeqqConSucursalConAlmacen($idempleado, $exte,$idsucursal,$idalmacen) {
        $output = public_path() . '/report/' . 'analisiseqqalmacensucursal';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/analisiseqqalmacensucursal.jrxml', $output, array($extencion), array("idempleado" => $idempleado,"idsucursal" => $idsucursal,"idalmacen" => $idalmacen), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'analisiseqqalmacensucursal.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }
    
    
    
    //reporteeqqConSucursalConAlmacen
        function reporteAnalisisAbc($idempleado, $exte) {
        $output = public_path() . '/report/' . 'analisisabc';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/analisisabc.jrxml', $output, array($extencion), array("idempleado" => $idempleado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'AnalisisABC.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }
        function reporteAnalisisAbcConSucursal($idempleado, $exte,$idsucursal) {
        $output = public_path() . '/report/' . 'analisisabcsucursal';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/analisisabcsucursal.jrxml', $output, array($extencion), array("idempleado" => $idempleado, "idsucursal" => $idsucursal), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'analisisabcsucursal.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }
    
//    reporteAnalisisAbcConSucursal
        function reporteAnalisisAbcConSucursalConAlmacen($idempleado, $exte,$idsucursal,$idalmacen) {
        $output = public_path() . '/report/' . 'analisisabcsucursalalmacen';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/analisisabcsucursalalmacen.jrxml', $output, array($extencion), array("idempleado" => $idempleado, "idsucursal" => $idsucursal, "idalmacen" => $idalmacen), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'analisisabcsucursalalmacen.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }
    
    
    function reporteEstadoInventarioConSucursal($idempleado, $exte, $sucursal) {
        $output = public_path() . '/report/' . 'EstadoInvenario';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/estadoInventario1ConSucursal.jrxml', $output, array($extencion), array("idempleado" => $idempleado, "idsucursal" => $sucursal), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'EstadoInvenario.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteEstadoInventarioConSucursalConAlmacen($idempleado, $exte, $sucursal, $almacen) {
        $output = public_path() . '/report/' . 'EstadoInvenario';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/estadoInventario1ConSucursalConAlmacen.jrxml', $output, array($extencion), array("idempleado" => $idempleado, "idsucursal" => $sucursal, "idalmacen" => $almacen), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'EstadoInvenario.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reportemotivomovimiento($Fechainicio, $fechafin, $id, $exte) {
        $output = public_path() . '/report/' . 'ResumenMoviInventario';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/ResumenMoviInventario.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $id), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'MovimientoInventario.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteVentaConSucursalSinAlmacenSinEmpleado($Fechainicio, $fechafin, $idempleado, $exte, $sucursal) {
        $output = public_path() . '/report/' . 'ListadoVentas';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/ventasPorUsuario/listadoVentasSinEmpleadoConSucursalSinAlmacen.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $idempleado, "idsucursal" => $sucursal), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ListadoVentas.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteVentaConSucursalSinAlmacenConEmpleado($Fechainicio, $fechafin, $usuariologeado, $idempleado, $exte, $sucursal) {
        $output = public_path() . '/report/' . 'ListadoVentas';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/VentasPorUsuario/listadoVentasConEmpleadoConSucursalSinAlmacen.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idusuario" => $idempleado, "idempleado" => $usuariologeado, "idsucursal" => $sucursal), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ListadoVentas.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteVentaSinSucursalSinAlmacenConEmpleado($Fechainicio, $fechafin, $usuariologeado, $idempleado, $exte) {
        $output = public_path() . '/report/' . 'ListadoVentas';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/ventasPorUsuario/listadoVentasConEmpleadoSinSucursalSinAlmacen.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idusuario" => $idempleado, "idempleado" => $usuariologeado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ListadoVentas.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteVentaConSucursalConAlmacenConEmpleado($Fechainicio, $fechafin, $usuariologeado, $idempleado, $exte, $sucursal, $almacen) {
        $output = public_path() . '/report/' . 'ListadoVentas';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/ventasPorUsuario/listadoVentasConEmpleadoConSucursalConAlmacen.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idusuario" => $idempleado, "idempleado" => $usuariologeado, "almacen" => $almacen, "idsucursal" => $sucursal), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ListadoVentas.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteRankingProductos($Fechainicio, $fechafin, $idempleado, $exte) {
        $output = public_path() . '/report/' . 'ReporteRanking';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/rankingProductoSinOrdenarSinSucursalSinAlmacenSinEmpleado.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $idempleado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ReporteRanking.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteRankingSinOrdenarConSucursalSinEmpleadoSinAlmacen($Fechainicio, $fechafin, $idempleado, $exte, $sucursal) {
        $output = public_path() . '/report/' . 'ReporteRanking';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/rankingProductoSinOrdenarConSucursalSinAlmacenSinEmpleado.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $idempleado, "idsucursal" => $sucursal), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ReporteRanking.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteRankingSinOrdenarConSucursalConEmpleadoSinAlmacen($Fechainicio, $fechafin, $idempleado, $usuariologeado, $exte, $sucursal) {
        $output = public_path() . '/report/' . 'ReporteRanking';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/rankingProductoSinOrdenarConSucursalSinAlmacenConEmpleado.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $usuariologeado, "idusuario" => $idempleado, "idsucursal" => $sucursal), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ReporteRanking.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteRankingSinOrdenarConSucursalConEmpleadoConAlmacen($Fechainicio, $fechafin, $idempleado, $usuariologeado, $exte, $sucursal, $almacen) {
        $output = public_path() . '/report/' . 'ReporteRanking';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/rankingProductoSinOrdenarConSucursalConAlmacenConEmpleado.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $usuariologeado, "idusuario" => $idempleado, "idsucursal" => $sucursal, "almacen" => $almacen), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ReporteRanking.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteRankingOrdenarImporteSinSucursalSinEmpleadoSinAlmacen($Fechainicio, $fechafin, $idempleado, $exte) {
        $output = public_path() . '/report/' . 'ReporteRanking';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/rankingProductoImporteSinSucursalSinAlmacenSinEmpleado.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $idempleado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ReporteRanking.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteRankingOrdenarImporteConSucursalSinEmpleadoSinAlmacen($Fechainicio, $fechafin, $idempleado, $exte, $sucursal) {
        $output = public_path() . '/report/' . 'ReporteRanking';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/rankingProductoImporteConSucursalSinAlmacenSinEmpleado.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $idempleado, "idsucursal" => $sucursal), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ReporteRanking.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteRankingOrdenarImporteConSucursalConEmpleadoSinAlmacen($Fechainicio, $fechafin, $idempleado, $usuariologeado, $exte, $sucursal) {
        $output = public_path() . '/report/' . 'ReporteRanking';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/rankingProductoImporteConSucursalSinAlmacenConEmpleado.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $usuariologeado, "idsucursal" => $sucursal, "idusuario" => $idempleado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ReporteRanking.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteRankingOrdenarImporteConSucursalConEmpleadoConAlmacen($Fechainicio, $fechafin, $idempleado, $usuariologeado, $exte, $sucursal, $almacen) {
        $output = public_path() . '/report/' . 'ReporteRanking';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/rankingProductoImporteConSucursalConAlmacenConEmpleado.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $usuariologeado, "idsucursal" => $sucursal, "idusuario" => $idempleado, "almacen" => $almacen), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ReporteRanking.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteRankingOrdenarCantidadSinSucursalSinEmpleadoSinAlmacen($Fechainicio, $fechafin, $idempleado, $exte) {
        $output = public_path() . '/report/' . 'ReporteRanking';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/rankingProductoCantidadSinSucursalSinAlmacenSinEmpleado.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $idempleado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ReporteRanking.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteRankingOrdenarCantidadConSucursalSinEmpleadoSinAlmacen($Fechainicio, $fechafin, $idempleado, $exte, $sucursal) {
        $output = public_path() . '/report/' . 'ReporteRanking';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/rankingProductoCantidadConSucursalSinAlmacenSinEmpleado.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $idempleado, "idsucursal" => $sucursal), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ReporteRanking.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteRankingOrdenarCantidadConSucursalConEmpleadoSinAlmacen($Fechainicio, $fechafin, $idempleado, $usuariologeado, $exte, $sucursal) {
        $output = public_path() . '/report/' . 'ReporteRanking';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/rankingProductoCantidadConSucursalSinAlmacenConEmpleado.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $usuariologeado, "idsucursal" => $sucursal, "idusuario" => $idempleado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ReporteRanking.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteRankingOrdenarCantidadConSucursalConEmpleadoConAlmacen($Fechainicio, $fechafin, $idempleado, $usuariologeado, $exte, $sucursal, $almacen) {
        $output = public_path() . '/report/' . 'ReporteRanking';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/rankingProductoCantidadConSucursalConAlmacenConEmpleado.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $usuariologeado, "idsucursal" => $sucursal, "idusuario" => $idempleado, "almacen" => $almacen), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ReporteRanking.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    public function FlujocajaporusurioConEmpleadoConSucursal($Fechainicio, $fechafin, $idEmpleado, $usuariologueado, $exte, $sucursal) {

        $output = public_path() . '/report/' . 'ReporteFlujoDeCaja';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/reporteFlujoCaja.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idusuario" => $idEmpleado, "idempleado" => $usuariologueado, "idsucursal" => $sucursal), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ReporteFlujoDeCaja.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    public function FlujocajaporusurioConEmpleadoSinSucursal($Fechainicio, $fechafin, $idEmpleado, $usuariologueado, $exte) {
        $output = public_path() . '/report/' . 'ReporteFlujoDeCaja';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/reporteFlujoCajaSinSucursalConEmpleado.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idusuario" => $idEmpleado, "idempleado" => $usuariologueado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ReporteFlujoDeCaja.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    public function FlujocajaCualquierEmpleado($Fechainicio, $fechafin, $idEmpleado, $exte) {
        $output = public_path() . '/report/' . 'ReporteFlujoDeCaja';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/reporteFlujoCajaCualquierEmpleado.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $idEmpleado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ReporteFlujoDeCaja.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    public function FlujocajaporusurioSinEmpleadoConSucursal($Fechainicio, $fechafin, $usuariologueado, $exte, $sucursal) {

        $output = public_path() . '/report/' . 'ReporteFlujoDeCaja';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/reporteFlujoCajaSinEmpleadoConSucursal.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $usuariologueado, "idsucursal" => $sucursal), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ReporteFlujoDeCaja.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteoptica($idventa) {
        $output = public_path() . '/report/' . 'REPORTEOPTICA';
        $report = new JasperPHP;
        $extencion = "pdf";

        $report->process(
                public_path() . '/report/reporteprueba.jrxml', $output, array($extencion), array("id" => $idventa), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'REPORTEOPTICA.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function recibooptica($idventa) {
        $output = public_path() . '/report/' . 'RECIBOOPTICA';
        $report = new JasperPHP;
        $extencion = "pdf";

        $report->process(
                public_path() . '/report/reporteopticabueno.jrxml', $output, array($extencion), array("idventa" => $idventa), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'RECIBOOPTICA.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteReportVentacruzdasConSucursal($idproducto, $idempleado, $exte, $sucursal) {
        $output = public_path() . '/report/' . 'VentasCruzadas';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/ventasCruzadasConSucursal.jrxml', $output, array($extencion), array("idproducto" => $idproducto, "idempleado" => $idempleado, "idsucursal" => $sucursal), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'VentasCruzadas.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteReportVentacruzdasConSucursalConAlmacen($idproducto, $idempleado, $exte, $sucursal, $almacen) {
        $output = public_path() . '/report/' . 'VentasCruzadas';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/ventasCruzadasConSucursalConAlmacen.jrxml', $output, array($extencion), array("idproducto" => $idproducto, "idempleado" => $idempleado, "idsucursal" => $sucursal, "almacen" => $almacen), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'VentasCruzadas.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reportemotivomovimientoConSucursal($Fechainicio, $fechafin, $id, $exte, $sucursal) {
        $output = public_path() . '/report/' . 'ResumenMoviInventario';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/ResumenMoviInventarioSucursal.jrxml', $output, array($extencion), array(
            "fechaini" => $Fechainicio,
            "fechafin" => $fechafin,
            "idempleado" => $id,
            "idSucursal" => $sucursal
                ), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'MovimientoInventario.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reportemotivomovimientoConSucursalConAlmacen($Fechainicio, $fechafin, $id, $exte, $sucursal, $almacen) {
        $output = public_path() . '/report/' . 'ResumenMoviInventario';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/ResumenMoviInventarioSucursalAlmacen.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $id, "idSucursal" => $sucursal, "idAlmacen" => $almacen), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'MovimientoInventario.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reportecompraCredito($Fechainicio, $fechafin, $idempleado, $exte) {
        $output = public_path() . '/report/' . 'reporteCompraCredito';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/reporteCreditoCompra.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $idempleado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'reporteCompraCredito.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reportecompraCreditoConempleadoSinProveedor($Fechainicio, $fechafin, $usuariologeado, $idempleado, $exte) {
        $output = public_path() . '/report/' . 'reporteCompraCredito';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/reporteCreditoCompraConEmpleadoSinProveedor.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $usuariologeado, "idusuario" => $idempleado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'reporteCompraCredito.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteCompraCreditoSinEmpleadoConProveedor($Fechainicio, $fechafin, $usuariologeado, $proveedor, $exte) {
        $output = public_path() . '/report/' . 'reporteCompraCredito';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/reporteCreditoCompraSinEmpleadoConProveedor.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $usuariologeado, "proveedor" => $proveedor), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'reporteCompraCredito.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteCompraCreditoConEmpleadoConProveedor($Fechainicio, $fechafin, $usuariologeado, $empleado, $proveedor, $exte) {
        $output = public_path() . '/report/' . 'reporteCompraCredito';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/reporteCreditoCompraConEmpleadoConProveedor.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $usuariologeado, "idusuario" => $empleado, "proveedor" => $proveedor), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'reporteCompraCredito.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reportecompraEfectivo($Fechainicio, $fechafin, $idempleado, $exte) {

        $output = public_path() . '/report/' . 'reporteCompraEfectivo';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/reporteEfectivoCompra.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $idempleado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'reporteCompraEfectivo.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteCompraEfectivoConEmpleadoSinProveedor($Fechainicio, $fechafin, $usuariologeado, $idempleado, $exte) {
        $output = public_path() . '/report/' . 'reporteCompraEfectivo';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/reporteEfectivoCompraConEmpleadoSinProveedor.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $usuariologeado, "idusuario" => $idempleado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'reporteCompraEfectivo.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteCompraEfectivoSinEmpleadoConProveedor($Fechainicio, $fechafin, $usuariologeado, $proveedor, $exte) {
        $output = public_path() . '/report/' . 'reporteCompraEfectivo';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/reporteEfectivoCompraSinEmpleadoConProveedor.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $usuariologeado, "proveedor" => $proveedor), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'reporteCompraEfectivo.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteCompraEfectivoConEmpleadoConProveedor($Fechainicio, $fechafin, $usuariologeado, $empleado, $proveedor, $exte) {
        $output = public_path() . '/report/' . 'reporteCompraEfectivo';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/reporteEfectivoCompraConEmpleadoConProveedor.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $usuariologeado, "idusuario" => $empleado, "proveedor" => $proveedor), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'reporteCompraEfectivo.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteEstadoInventarioConProducto($idempleado, $exte, $producto) {
        $output = public_path() . '/report/' . 'EstadoInvenario';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/estadoInventario1ConProducto.jrxml', $output, array($extencion), array("idempleado" => $idempleado, "idproducto" => $producto), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'EstadoInvenario.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteEstadoInventarioConSucursalConProducto($idempleado, $exte, $sucursal, $producto) {
        $output = public_path() . '/report/' . 'EstadoInvenario';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/estadoInventario1ConSucursalConProducto.jrxml', $output, array($extencion), array("idempleado" => $idempleado, "idsucursal" => $sucursal, "idproducto" => $producto), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'EstadoInvenario.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteEstadoInventarioConSucursalConAlmacenConProducto1($idempleado, $exte, $sucursal, $almacen, $producto) {
        $output = public_path() . '/report/' . 'EstadoInvenario';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/estadoInventario1ConSucursalConAlmacenConProducto_1.jrxml', $output, array($extencion), array(
            "idempleado" => $idempleado,
            "idsucursal" => $sucursal,
            "idalmacen" => $almacen,
            "idproducto" => $producto)
                , array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'EstadoInvenario.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function ReportMovimientoInventarioProducto($Fechainicio, $fechafin, $id, $exte, $producto) {
        $output = public_path() . '/report/' . 'ResumenMoviInventario';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/ResumenMoviInventarioProducto.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $id, "idProducto" => $producto), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'MovimientoInventario.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function ReportMovimientoInventarioConSucursalProducto($Fechainicio, $fechafin, $id, $exte, $sucursal, $producto) {
        $output = public_path() . '/report/' . 'ResumenMoviInventario';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/ResumenMoviInventarioSucursalProducto.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $id, "idSucursal" => $sucursal, "idProducto" => $producto), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'MovimientoInventario.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function ReportMovimientoInventarioConSucursalConAlmacenProducto($Fechainicio, $fechafin, $id, $exte, $sucursal, $almacen, $producto) {
        $output = public_path() . '/report/' . 'ResumenMoviInventario';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/ResumenMoviInventarioSucursalAlmacenProducto.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $id, "idSucursal" => $sucursal, "idAlmacen" => $almacen, "idProducto" => $producto), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'MovimientoInventario.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reportusuarioactualPorUsuarioNAComision($Fechainicio, $fechafin, $idempleado, $id, $exte) {
        $output = public_path() . '/report/' . 'ListadoVentas';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/listadoVentasPorUsuarioNoAlmacenComision.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $idempleado, "id" => $id), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ListadoVentasDe' . $Fechainicio . "Al" . $fechafin . "." . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteUsuarioActualPorUsuarioComision($Fechainicio, $fechafin, $idempleado, $id, $exte, $almacen) {
        $output = public_path() . '/report/' . 'ListadoVentas';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/listadoVentasPorUsuarioComision.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $idempleado, "id" => $id, "almacen" => $almacen), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ListadoVentas.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteUsuarioActualComision($Fechainicio, $fechafin, $idempleado, $exte) {
        $output = public_path() . '/report/' . 'ListadoVentas';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/listadoVentasComision.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $idempleado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ListadoVentas.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteVentaConSucursalSinAlmacenSinEmpleadoComision($Fechainicio, $fechafin, $idempleado, $exte, $sucursal) {
        $output = public_path() . '/report/' . 'ListadoVentas';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/listadoVentasSinEmpleadoConSucursalSinAlmacenComision.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $idempleado, "idsucursal" => $sucursal), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ListadoVentas.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteVentaConSucursalSinAlmacenConEmpleadoComision($Fechainicio, $fechafin, $usuariologeado, $idempleado, $exte, $sucursal) {
        $output = public_path() . '/report/' . 'ListadoVentas';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/listadoVentasConEmpleadoConSucursalSinAlmacenComision.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idusuario" => $idempleado, "idempleado" => $usuariologeado, "idsucursal" => $sucursal), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ListadoVentas.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteVentaConSucursalConAlmacenConEmpleadoComision($Fechainicio, $fechafin, $usuariologeado, $idempleado, $exte, $sucursal, $almacen) {
        $output = public_path() . '/report/' . 'ListadoVentas';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/listadoVentasConEmpleadoConSucursalConAlmacenComision.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idusuario" => $idempleado, "idempleado" => $usuariologeado, "almacen" => $almacen, "idsucursal" => $sucursal), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ListadoVentas.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reportusuarioactualPorUsuarioAlquiler($Fechainicio, $fechafin, $idempleado, $exte, $estado) {
        $output = public_path() . '/report/' . 'ListadoVentasAlquiler';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/listadoVentasAlquilerPorUsuarioNoAlmacen.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $idempleado, "id" => $idempleado, "estado" => $estado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . "ListadoAlquileres." . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reportusuarioactualAlquiler($Fechainicio, $fechafin, $idempleado, $exte, $almacen, $estado) {
        $output = public_path() . '/report/' . 'ListadoVentasAlquiler';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/listadoVentasAlquilerPorUsuario.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $idempleado, "id" => $idempleado, "almacen" => $almacen, "estado" => $estado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ListadoVentasAlquiler.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reportAlquilerSinSucursalSinAlmacenSinEmpleado($Fechainicio, $fechafin, $idempleado, $exte, $filt) {
        $output = public_path() . '/report/' . 'ListadoVentasAlquiler';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/listadoVentasAlquiler.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $idempleado, "estado" => $filt), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ListadoVentasAlquiler.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteVentaConSucursalSinAlmacenSinEmpleadoAlquiler($Fechainicio, $fechafin, $idempleado, $exte, $sucursal, $filt) {
        $output = public_path() . '/report/' . 'ListadoVentasAlquiler';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/listadoVentasSinEmpleadoConSucursalSinAlmacenAlquiler.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $idempleado, "idsucursal" => $sucursal, "estado" => $filt), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ListadoVentasAlquiler.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteVentaConSucursalSinAlmacenConEmpleadoAlquiler($Fechainicio, $fechafin, $usuariologeado, $idempleado, $exte, $sucursal, $flit) {
        $output = public_path() . '/report/' . 'ListadoVentasAlquiler';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/listadoVentasConEmpleadoConSucursalSinAlmacenAlquiler.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idusuario" => $idempleado, "idempleado" => $usuariologeado, "idsucursal" => $sucursal, "estado" => $flit), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ListadoVentasAlquiler.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteVentaConSucursalConAlmacenConEmpleadoAlquiler($Fechainicio, $fechafin, $usuariologeado, $idempleado, $exte, $sucursal, $almacen, $filt) {
        $output = public_path() . '/report/' . 'ListadoVentasAlquiler';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/listadoVentasConEmpleadoConSucursalConAlmacenAlquiler.jrxml', $output, array($extencion), array("estado" => $filt, "fechaini" => $Fechainicio, "fechafin" => $fechafin, "idusuario" => $idempleado, "idempleado" => $usuariologeado, "almacen" => $almacen, "idsucursal" => $sucursal), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ListadoVentasAlquiler.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteDetalleCompraNoAlmacen($Fechainicio, $fechafin, $idempleado, $idusuario, $exte) {
        $output = public_path() . '/report/' . 'DetalleCompras';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/compraDetalleCompraNoalmacen.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $idempleado, "idusuario" => $idusuario), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'DetalleCompras' . $Fechainicio . 'Al' . $fechafin . '.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reportDetalleCompra($Fechainicio, $fechafin, $idempleado1, $idusuario1, $exte, $sucursal1) {
        $output = public_path() . '/report/' . 'DetalleCompras';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/compraDetalleCompra.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $idusuario1, "sucursal" => $sucursal1, "idusuario" => $idempleado1), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'DetalleCompras' . $Fechainicio . 'Al' . $fechafin . '.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteDetalleCompraSinSucursalSinEmpleadoSinSucursal($Fechainicio, $fechafin, $idempleado, $exte) {
        $output = public_path() . '/report/' . 'DetalleCompras';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/compraDetalleCompraNoAlmacenNoSucursalSinEmpleado.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $idempleado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'DetalleCompras.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function compraDetalleCompraConSucursalSinAlmacenConEmpleado($Fechainicio, $fechafin, $idempleado, $usuariologeado, $exte, $sucursal) {
        $output = public_path() . '/report/' . 'DetalleCompras';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/compraDetalleCompraConSucursalSinAlmacenSinEmpleado.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $usuariologeado, "idusuario" => $idempleado, "sucursal" => $sucursal), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'DetalleCompras.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function compraDetalleCompraConSucursal($Fechainicio, $fechafin, $idempleado, $exte, $sucursal) {
        $output = public_path() . '/report/' . 'DetalleCompras';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/compraDetalleCompraConSucursal.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $idempleado, "sucursal" => $sucursal), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'DetalleCompras.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reportDetalleCompraConSucursalConEmpleadoConAlmacen($Fechainicio, $fechafin, $idusuario, $idempleado, $exte, $sucursal, $almacen) {
        $output = public_path() . '/report/' . 'DetalleVentas';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/compraDetalleCompraConSucursalConAlmacenConEmpleado.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idusuario" => $idempleado, "idempleado" => $idusuario, "sucursal" => $sucursal, "almacen" => $almacen), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'DetalleVentas.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function listadoVentasConEmpleadoSinSucursalSinAlmacenAnulada($Fechainicio, $fechafin, $usuariologeado, $idempleado, $exte) {
        $output = public_path() . '/report/' . 'ListadoVentasAnuladas';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/listadoVentasConEmpleadoSinSucursalSinAlmacenAnulada.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idusuario" => $idempleado, "idempleado" => $usuariologeado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ListadoVentasAnuladas.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function listadoVentasConEmpleadoConSucursalSinAlmacenAnulada($Fechainicio, $fechafin, $usuariologeado, $idempleado, $exte, $sucursal) {
        $output = public_path() . '/report/' . 'ListadoVentasAnuladas';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/listadoVentasConEmpleadoConSucursalSinAlmacenAnulada.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idusuario" => $idempleado, "idempleado" => $usuariologeado, "idsucursal" => $sucursal), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ListadoVentasAnuladas.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function listadoVentasAnuladas($Fechainicio, $fechafin, $idempleado, $exte) {
        $output = public_path() . '/report/' . 'ListadoVentasAnuladas';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/listadoVentasAnuladas.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $idempleado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ListadoVentasAnuladas.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function listadoVentasSinEmpleadoConSucursalSinAlmacenAnulada($Fechainicio, $fechafin, $idempleado, $exte, $sucursal) {
        $output = public_path() . '/report/' . 'ListadoVentasAnuladas';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/listadoVentasSinEmpleadoConSucursalSinAlmacenAnulada.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $idempleado, "idsucursal" => $sucursal), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ListadoVentasAnuladas.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function listadoVentasConEmpleadoConSucursalConAlmacenAnulada($Fechainicio, $fechafin, $usuariologeado, $idempleado, $exte, $sucursal, $almacen) {
        $output = public_path() . '/report/' . 'ListadoVentasAnuladas';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/listadoVentasConEmpleadoConSucursalConAlmacenAnulada.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idusuario" => $idempleado, "idempleado" => $usuariologeado, "almacen" => $almacen, "idsucursal" => $sucursal), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ListadoVentasAnuladas.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function listadoVentasConEmpleadoSinSucursalSinAlmacenEliminadas($Fechainicio, $fechafin, $usuariologeado, $idempleado, $exte) {
        $output = public_path() . '/report/' . 'ListadoVentasEliminadas';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/listadoVentasConEmpleadoSinSucursalSinAlmacenEliminadas.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idusuario" => $idempleado, "idempleado" => $usuariologeado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ListadoVentasEliminadas.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function listadoVentasConEmpleadoConSucursalSinAlmacenEliminar($Fechainicio, $fechafin, $usuariologeado, $idempleado, $exte, $sucursal) {
        $output = public_path() . '/report/' . 'ListadoVentasEliminadas';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/listadoVentasConEmpleadoConSucursalSinAlmacenEliminar.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idusuario" => $idempleado, "idempleado" => $usuariologeado, "idsucursal" => $sucursal), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ListadoVentasEliminadas.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function listadoVentasEliminadas($Fechainicio, $fechafin, $idempleado, $exte) {
        $output = public_path() . '/report/' . 'ListadoVentasElimindas';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/listadoVentasEliminadas.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $idempleado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ListadoVentasElimindas.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function listadoVentasSinEmpleadoConSucursalSinAlmacenEliminadas($Fechainicio, $fechafin, $idempleado, $exte, $sucursal) {
        $output = public_path() . '/report/' . 'ListadoVentasEliminadas';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/listadoVentasSinEmpleadoConSucursalSinAlmacenEliminadas.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $idempleado, "idsucursal" => $sucursal), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ListadoVentasEliminadas.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function listadoVentasConEmpleadoConSucursalConAlmacenEliminada($Fechainicio, $fechafin, $usuariologeado, $idempleado, $exte, $sucursal, $almacen) {
        $output = public_path() . '/report/' . 'ListadoVentasEliminadas';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/listadoVentasConEmpleadoConSucursalConAlmacenEliminada.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idusuario" => $idempleado, "idempleado" => $usuariologeado, "almacen" => $almacen, "idsucursal" => $sucursal), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ListadoVentasEliminadas.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteVentaCreditoSinEmpleadoSinSucursalSinCliente($Fechainicio, $fechafin, $usuariologeado, $exte) {
        $output = public_path() . '/report/' . 'ListadoVentasAlCredito';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/ventasCredito/reporteCreditoVentaSimEmpleadoSinSucursalSinCliente.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $usuariologeado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ListadoVentasAlCredito.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteVentaCreditoSinEmpleadoConSucursalSinCliente($Fechainicio, $fechafin, $usuariologeado, $exte, $sucursal) {
        $output = public_path() . '/report/' . 'ListadoVentasAlCredito';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/ventasCredito/reporteCreditoVentaSinEmpleadoConSucursalSinCliente.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $usuariologeado, "sucursal" => $sucursal), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ListadoVentasAlCredito.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteVentaCreditoConEmpleadoConSucursalSinCliente($Fechainicio, $fechafin, $empleado, $usuariologeado, $exte, $sucursal) {
        $output = public_path() . '/report/' . 'ListadoVentasAlCredito';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/ventasCredito/reporteCreditoVentaConEmpleadoConSucursalSinCliente.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $usuariologeado, "sucursal" => $sucursal, "idusuario" => $empleado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ListadoVentasAlCredito.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteVentaCreditoConEmpleadoConSucursalConCliente($Fechainicio, $fechafin, $empleado, $usuariologeado, $exte, $sucursal, $cliente) {
        $output = public_path() . '/report/' . 'ListadoVentasAlCredito';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/ventasCredito/reporteCreditoVentaConEmpleadoConSucursalConCliente.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $usuariologeado, "sucursal" => $sucursal, "idusuario" => $empleado, "cliente" => $cliente), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ListadoVentasAlCredito.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteVentaCreditoSinEmpleadoSinSucursalConCliente($Fechainicio, $fechafin, $usuariologeado, $exte, $cliente) {
        $output = public_path() . '/report/' . 'ListadoVentasAlCredito';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/ventasCredito/reporteCreditoVentaSinEmpleadoSinSucursalConCliente.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $usuariologeado, "cliente" => $cliente), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ListadoVentasAlCredito.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function ventaDetalleVentaSinSucursaSinAlmacenSinEmpleadoBelleMarie($Fechainicio, $fechafin, $idempleado, $exte) {
        $output = public_path() . '/report/' . 'DetalleVentas';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/ventaDetalleVentaSinSucursaSinAlmacenSinEmpleadoBelleMarie.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $idempleado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'DetalleVentas.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function ventaDetalleVentaConSucursaSinAlmacenSinEmpleadoBelleMarie($Fechainicio, $fechafin, $idempleado, $exte, $sucursal) {
        $output = public_path() . '/report/' . 'DetalleVentas';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/ventaDetalleVentaConSucursaSinAlmacenSinEmpleadoBelleMarie.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $idempleado, "sucursal" => $idempleado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'DetalleVentas.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function ventaDetalleVentaConSucursaSinAlmacenConEmpleadoBelleMarie($Fechainicio, $fechafin, $idempleado, $usuariologeado, $exte, $sucursal) {
        $output = public_path() . '/report/' . 'DetalleVentas';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/ventaDetalleVentaConSucursaSinAlmacenConEmpleadoBelleMarie.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $usuariologeado, "idusuario" => $idempleado, "sucursal" => $idempleado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'DetalleVentas.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function ventaDetalleVentaConSucursaConAlmacenConEmpleadoBelleMarie($Fechainicio, $fechafin, $idempleado, $usuariologeado, $exte, $sucursal, $almacen) {
        $output = public_path() . '/report/' . 'DetalleVentas';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/ventaDetalleVentaConSucursaConAlmacenConEmpleadoBelleMarie.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $usuariologeado, "idusuario" => $idempleado, "sucursal" => $idempleado, "almacen" => $almacen), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'DetalleVentas.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    //////////CUOTAS ATRASADAS///////////////////////

    function reporteVentaCreditoAtrasadasSinEmpleadoSinSucursalSinCliente($usuariologeado, $exte, $fechainicio, $fechafin) {
        $output = public_path() . '/report/' . 'reporteCreditoVentaAtrasadas';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/ventasCredito/cuotasRetrasadas/reporteCreditoVentaAtrasadasSinEmpleadoSinSucursalSinCliente.jrxml', $output, array($extencion), array("fechainicio" => $fechainicio, "fechafin" => $fechafin, "idempleado" => $usuariologeado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306')
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ListadoVentasAlCreditoAtrasadas.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteCreditoVentaAtrasadasSinEmpleadoConSucursalConCliente($idcliente, $usuariologeado, $exte, $idsucursal, $fechainicio, $fechafin) {
        $output = public_path() . '/report/' . 'reporteCreditoVentaAtrasadas';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/ventasCredito/cuotasRetrasadas/reporteCreditoVentaAtrasadasSinEmpleadoConSucursalConCliente', $output, array($extencion), array("fechainicio" => $fechainicio, "fechafin" => $fechafin, "idcliente" => $idcliente, "idsucursal" => $idsucursal, "idempleado" => $usuariologeado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306')
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ListadoVentasAlCreditoAtrasadas.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteCreditoVentaAtrasadasConEmpleadoConSucursalConCliente($idemple, $idcliente, $usuariologeado, $exte, $idsucursal, $fechainicio, $fechafin) {
        $output = public_path() . '/report/' . 'reporteCreditoVentaAtrasadas';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/ventasCredito/cuotasRetrasadas/reporteCreditoVentaAtrasadasConEmpleadoConSucursalConCliente', $output, array($extencion), array("fechainicio" => $fechainicio, "fechafin" => $fechafin, "idemple" => $idemple, "idcliente" => $idcliente, "idsucursal" => $idsucursal, "idempleado" => $usuariologeado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306')
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ListadoVentasAlCreditoAtrasadas.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteCreditoVentaAtrasadasConEmpleadoConSucursalSinCliente($idemple, $usuariologeado, $exte, $idsucursal, $fechainicio, $fechafin) {
        $output = public_path() . '/report/' . 'reporteCreditoVentaAtrasadas';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/ventasCredito/cuotasRetrasadas/reporteCreditoVentaAtrasadasConEmpleadoConSucursalSinCliente', $output, array($extencion), array("fechainicio" => $fechainicio, "fechafin" => $fechafin, "idemple" => $idemple, "idsucursal" => $idsucursal, "idempleado" => $usuariologeado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306')
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ListadoVentasAlCreditoAtrasadas.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteVentaCreditoAtrasadasSinEmpleadoSinSucursalConCliente($idcliente, $usuariologeado, $exte, $fechainicio, $fechafin) {
        $output = public_path() . '/report/' . 'reporteCreditoVentaAtrasadas';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/ventasCredito/cuotasRetrasadas/reporteCreditoVentaAtrasadasSinEmpleadoSinSucursalConCliente', $output, array($extencion), array("fechainicio" => $fechainicio, "fechafin" => $fechafin, "idcliente" => $idcliente, "idempleado" => $usuariologeado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306')
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ListadoVentasAlCreditoAtrasadas.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteCreditoVentaAtrasadasSinEmpleadoSinSucursalConCliente($idcliente, $usuariologeado, $exte, $fechainicio, $fechafin) {
        $output = public_path() . '/report/' . 'reporteCreditoVentaAtrasadas';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/ventasCredito/cuotasRetrasadas/reporteCreditoVentaAtrasadasSinEmpleadoSinSucursalConCliente', $output, array($extencion), array("fechainicio" => $fechainicio, "fechafin" => $fechafin, "idcliente" => $idcliente, "idempleado" => $usuariologeado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306')
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ListadoVentasAlCreditoAtrasadas.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteVentaCreditoAtrasadasSinEmpleadoConSucursalSinCliente($usuariologeado, $exte, $idsucursal, $fechainicio, $fechafin) {
        $output = public_path() . '/report/' . 'reporteCreditoVentaAtrasadas';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/ventasCredito/cuotasRetrasadas/reporteVentaCreditoAtrasadasSinEmpleadoConSucursalSinCliente', $output, array($extencion), array("fechainicio" => $fechainicio, "fechafin" => $fechafin, "idsucursal" => $idsucursal, "idempleado" => $usuariologeado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306')
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ListadoVentasAlCreditoAtrasadas.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    ////
    function reporteFlujoIngresosEgresos($fechainic, $fechafin, $usuariologeado, $exte) {
        $output = public_path() . '/report/' . 'reporteCreditoVentaAtrasadas';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/reporteFlujoIngresosEgresos', $output, array($extencion), array("fechaini" => $fechainic, 'fechafin' => $fechafin, "idempleado" => $usuariologeado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306')
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'FlujoIngresosEgresosDEL' . $fechainic . 'AL' . $fechafin . '.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteFlujoIngresosEgresosConSucursal($fechainic, $fechafin, $usuariologeado, $exte, $sucursal) {
        $output = public_path() . '/report/' . 'reporteCreditoVentaAtrasadas';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/reporteFlujoIngresosEgresosConSucursal', $output, array($extencion), array("fechaini" => $fechainic, 'fechafin' => $fechafin, "idempleado" => $usuariologeado, "idsucursal" => $sucursal), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306')
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'FlujoIngresosEgresosConSucursalDEL' . $fechainic . 'AL' . $fechafin . '.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteEgresoConEmpleadoConTipo($Fechainicio, $fechafin, $idempleado, $usuariologueado, $exte, $idtipo) {
        $output = public_path() . '/report/' . 'ReporteEgreso';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/listadoEgresoConEmpleadoConTipo.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $idempleado, "idtipo" => $idtipo, "idusuario" => $usuariologueado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ReporteEgreso.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteEgresoConTipo($Fechainicio, $fechafin, $usuariologueado, $exte, $idtipo) {
        $output = public_path() . '/report/' . 'ReporteEgreso';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/listadoEgresoConTipo.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $usuariologueado, "idusuario" => $idtipo), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ReporteEgreso.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteDetalleVentasConEmpleadoConSucursalSinAlmacen($Fechainicio, $fechafin, $idempleado1, $idusuario1, $exte, $sucursal1) {
        $output = public_path() . '/report/' . 'DetalleVentas';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/ventaDetalleVentaConEmpleadoConSucursalSinAlmacen.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $idusuario1, "sucursal" => $sucursal1, "idusuario" => $idempleado1), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'DetalleVentasDe' . $Fechainicio . 'Al' . $fechafin . '.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    //REPORTE CREDITOS CONSOLIDADO
    function reporteVentaCreditoSinEmpleadoSinSucursalSinClienteConsolidado($Fechainicio, $fechafin, $usuariologeado, $exte) {
        $output = public_path() . '/report/' . 'ListadoVentasAlCredito';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/ventasCredito/consolidado/reporteCreditoVentaSimEmpleadoSinSucursalSinCliente.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $usuariologeado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ListadoVentasAlCreditoConsolidado.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteVentaCreditoSinEmpleadoConSucursalSinClienteConsolidado($Fechainicio, $fechafin, $usuariologeado, $exte, $sucursal) {
        $output = public_path() . '/report/' . 'ListadoVentasAlCredito';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/ventasCredito/consolidado/reporteCreditoVentaSinEmpleadoConSucursalSinCliente.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $usuariologeado, "sucursal" => $sucursal), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ListadoVentasAlCreditoConsolidado.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteVentaCreditoConEmpleadoConSucursalSinClienteConsolidado($Fechainicio, $fechafin, $empleado, $usuariologeado, $exte, $sucursal) {
        $output = public_path() . '/report/' . 'ListadoVentasAlCredito';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/ventasCredito/consolidado/reporteCreditoVentaConEmpleadoConSucursalSinCliente.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $usuariologeado, "sucursal" => $sucursal, "idusuario" => $empleado), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ListadoVentasAlCreditoConsolidado.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteVentaCreditoConEmpleadoConSucursalConClienteConsolidado($Fechainicio, $fechafin, $empleado, $usuariologeado, $exte, $sucursal, $cliente) {
        $output = public_path() . '/report/' . 'ListadoVentasAlCredito';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/ventasCredito/consolidado/reporteCreditoVentaConEmpleadoConSucursalConCliente.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $usuariologeado, "sucursal" => $sucursal, "idusuario" => $empleado, "cliente" => $cliente), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ListadoVentasAlCreditoConsolidado.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

    function reporteVentaCreditoSinEmpleadoSinSucursalConClienteConsolidado($Fechainicio, $fechafin, $usuariologeado, $exte, $cliente) {
        $output = public_path() . '/report/' . 'ListadoVentasAlCredito';
        $report = new JasperPHP;
        if ($exte == 0) {
            $extencion = "pdf";
        } else {
            $extencion = "xlsx";
        }
        $report->process(
                public_path() . '/report/ventasCredito/consolidado/reporteCreditoVentaSinEmpleadoSinSucursalConCliente.jrxml', $output, array($extencion), array("fechaini" => $Fechainicio, "fechafin" => $fechafin, "idempleado" => $usuariologeado, "cliente" => $cliente), array('driver' => 'mysql',
            'username' => 'root',
            'password' => '',
            'host' => 'localhost',
            'database' => 'restopost',
            'port' => '3306',)
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . 'ListadoVentasAlCreditoConsolidado.' . $extencion);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output . '.' . $extencion));
        flush();
        readfile($output . '.' . $extencion);
        unlink($output . '.' . $extencion);
    }

}
