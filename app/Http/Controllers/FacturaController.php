<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use App\Http\Requests;
use App\Controllers\CodigoControl;
use Illuminate\Http\Response;
use \Milon\Barcode\DNS2D;

class FacturaController extends Controller {

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
     * 'id', 'idLibroOrden', 'idVenta','idPuntoVenta', 'nroFactura', 'nroAutorizacion', 'codigoControl', 'fecha', 'NIT', 'razonSocial', 'fechaLimite', 'total', 'totalLiteral', 'eliminado'
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
      idventa: idventa,idpuntoventa: idpuntoventa,total: total,valorliteral: valorliteral,nombre:nombre,nit:nit
     */
    public function store(Request $request) {
        $nitempresa = "";
        $nroAutorizacion;
        $llave;
        $estado;
        $idLibroOrden;
        $fechaLimite;
        $numeroinicio;
        $numerofin;
        $date = Carbon::now();
        $fecha=$request->fecha;
        if($fecha=="")
        { 
        $fecha = $date->toDateString();    
        }        
        $date2 = Carbon::now();
        $hora = $date2->toTimeString();
        $fechacontrol = str_replace('-', '', $fecha);
        $ultimo;
        $numeroactual;
        $idSucursal;
        $comision;
        $idelemeplaedo;
        $emplecom = DB::select("SELECT 
                        empleado.id,
                        empleado.comision,
                        puntoventa.idSucursal
                      FROM
                        empleado,
                        puntoventa
                      WHERE
                      empleado.`id` = `puntoventa`.`idEmpleado`
                      AND `puntoventa`.`id` = ? ", [ $request->idpuntoventa]);
        foreach ($emplecom as $com) {
            $comision = $com->comision;
            $idSucursal = $com->idSucursal;
            $idelemeplaedo = $com->id;
        }
        $totalventa = $request->totaltotal - $request->importedescuento;
        $comisionimporte = ($comision * $totalventa) / 100;
        if ($request->facturacredito == "true") {
            $datofactura = self::obtenerdatoslibroderoden($idelemeplaedo, $request->idpuntoventa);
            foreach ($datofactura as $key => $value) {
                $nitempresa = $value->NIT;
                $nroAutorizacion = $value->nroAutorizacion;
                $llave = $value->llave;
                $estado = $value->estado;
                $idLibroOrden = $value->id;
                $fechaLimite = $value->fechaFin;
                $numeroinicio = $value->nroInicio;
                $numerofin = $value->nroFin;
                $idSucursal = $value->idSucursal;
            }
            $numeroactual = self::obtenernumerodefactura($idLibroOrden);
            if ($numeroactual == "No se puede generar factura porque no existe libro de órdenes activos" && $request->facturacredito == "true") {
                return response()->json([$numeroactual]);
            }
            if ($numeroactual == "Fecha limite a Terminado" && $request->facturacredito == "true") {
                return response()->json([$numeroactual]);
            }
            if ($numeroactual == "limite de numero de factura" && $request->facturacredito == "true") {
                return response()->json([$numeroactual]);
            }
        }
        $idproducto = 0;
        $venta = 0;
        $tipoproducto = 0;
        $datosproductos = DB::select("SELECT producto.id as producto,
                                                producto.tipoproducto,venta.id
                                        FROM venta 
                                            INNER JOIN detalleventa on venta.id=detalleventa.idVenta
                                            INNER JOIN  producto on producto.id=detalleventa.idProducto
                                        WHERE venta.id=?  
                                        ORDER BY producto.tipoproducto DESC", [$request->idventa]);
        foreach ($datosproductos as $key => $value) {
            $idproducto = $value->producto;
            $tipoproducto = $value->tipoproducto;
            $venta = $value->id;
            if ($tipoproducto == 'combo') {
                DB::table('composicionproductodetalleventa')
                        ->where('idventa', $request->idventa)
                        ->update(['eliminado' => 0]);
            } else if ($tipoproducto == 'ingrediente') {
                DB::table('ingredienteproductodetalleventa')
                        ->where('idventa', $request->idventa)
                        ->update(['eliminado' => 0]);
            }
        }
        $medaactiva = DB::table('mesa')->where('id', $request->idmesas)->update(['estado' => 0]);
        $sumador = DB::select("SELECT contador+1 as siguiente FROM sucursal WHERE sucursal.id=?", [$idSucursal]);
        $siguiente;
        foreach ($sumador as $key => $value) {
            $siguiente = $value->siguiente;
        }
        $sumarcontador = DB::table('sucursal')->where('id', $idSucursal)->update(['contador' => $siguiente]);
        $actua = DB::table('venta')->where('id', $request->idventa)->update(['ordennumero' => $siguiente]);
        if ($request->totaltotal != null) {
            $actua = DB::table('venta')->where('id', $request->idventa)
                ->update(['estado' => 1,
                'formaPago' => $request->pago,
                'etapa' => $request->etapa,
                'Pago' => $request->pagos,
                'Cambio' => $request->cambio,
                'idTipoDescuento' => $request->idTipoDescuento,
                'porcentajedescuento' => $request->porcentajedescuento,
                'total' => $request->totaltotal,
                'importedescuento' => $request->importedescuento,
                'aCuenta' => $request->aCuenta,
                'saldoACobrar' => $request->saldoACobrar,
                'cuotasSaldo' => $request->cuotasSaldo,
                'cobrarCada' => $request->cobrarCada,
                'idCliente' => $request->idCliente,
                'fecha' => $fecha,
                'hora' => $hora,
                'garantia' => $request->garantia,
                'observaciones' => $request->observaciones,
                'personaentrega' => $request->PersonaRecibeenvio,
                'direccionenvio' => $request->DireccionEnvio,
                'importetransporte' => $request->Importetransporte,
                'fechaentrega' => $request->FechaEntrega,
                'horaentrega' => $request->HoraEntrega,
                'estadoVenta' => $request->estadoenvio,
                'telefono' => $request->celular,
                'ciudad' => $request->departamento,
                'nroCuenta' => $request->nroCuenta,
                'idMesa' => $request->idmesas,
                'comision' => $comisionimporte,
                'idPuntoVenta' => $request->idpuntoventa,
                'descuentocliente'=>$request->totalfifodescuentocliente
                    ]);
        }
        DB::table('venta')->where('id', $request->idventa)->update(['estado' => 1]);
        if ($request->etapa == "credito") {
            $date = Carbon::now();
            $fechaInicio = $date->toDateString();

//            $fechaDeCobro = Carbon::parse($request->cobrarCada);
//            $fechaFin = $fechaDeCobro->subMonth();
//            $cantdias = $date->diffInDays($fechaFin);
//            $periodoCuotas = $cantdias / $request->cuotasSaldo;

            $periodoCuotas = $request->cobrarCada;

            $aux2 = Carbon::now();
            $endDate = $aux2->addDays($periodoCuotas);
            $importe = $request->saldoACobrar / $request->cuotasSaldo;
            $limite = $request->cuotasSaldo;
            for ($i = 1; $i <= $limite; $i++) {
                $aux = $endDate;
                $fechaFin2 = $aux->toDateString();
                $cuentacobrar = DB::table('cuentaCobrar')->insert([
                    'fecha' => $fechaInicio,
                    'fechaVencimiento' => $fechaFin2,
                    'importe' => $importe,
                    'glosa' => "",
                    'idVenta' => $request->idventa,
                    'idPuntoVenta' => $request->idpuntoventa,
                    'eliminado' => 0
                ]);
                $fechaInicio = $fechaFin2;
                if ($periodoCuotas == 1) {
                    $endDate->addDay();
                } elseif ($periodoCuotas == 7) {
                    $endDate->addWeek();
                } else if ($periodoCuotas == 15) {
                    $endDate->addDays(15);
                } else if ($periodoCuotas == 30) {
                    $endDate->addMonth();
                }
//                $endDate = $endDate->addDays($periodoCuotas);
            }
//            DB::table('detalleventa')->where('idVenta', $request->idventa)->update(['estado' => 0]);
        }
        if ($request->facturacredito == "true") {
            $codigocontrols = $this->generate(
                    $nroAutorizacion, $nitempresa, $request->nit, $fechacontrol, $request->total, $llave
            );
            DB::table('factura')->insert([
                'idLibroOrden' => $idLibroOrden,
                'idVenta' => $request->idventa,
                'idPuntoVenta' => $request->idpuntoventa,
                'nroFactura' => $numeroactual,
                'nroAutorizacion' => $nroAutorizacion,
                'codigoControl' => $codigocontrols,
                'fecha' => $fecha,
                'NIT' => $request->nit,
                'razonSocial' => $request->nombre,
                'fechaLimite' => $fechaLimite,
                'total' => $request->total,
                'totalLiteral' => $request->valorliteral,
                'eliminado' => 0]);
            $actualibro = DB::table('libroorden')->where('id', $idLibroOrden)->update(['nroActual' => $numeroactual]);
            $medaactiva = DB::table('mesa')->where('id', $request->idmesas)->update(['estado' => 0]);
            $ultimo = DB::table('factura')->select('id')->orderby('id', 'DESC')->take(1)->get('id');
            return response()->json([$ultimo]);
        } else {
            $mensaje = DB::table('venta')->select('id')->where('id', $request->idventa)->orderby('id', 'DESC')->take(1)->get('id');
            return response()->json([$mensaje]);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

    /**
     * @param String $authorizationNumber Numero de autorizacion
     * @param String $invoiceNumber Numero de factura
     * @param String $nitci Número de Identificación Tributaria o Carnet de Identidad
     * @param String $dateOfTransaction fecha de transaccion de la forma AAAAMMDD
     * @param String $transactionAmount Monto de la transacción 
     * @param String $dosageKey Llave de dosificación
     */
    public function prubacodigocontrol() {
        $date = Carbon::now();     // 790400539356    851896  1994428014  25/07/2008  84178 
        $fecha = $date->toDateString();
        // $fechacontrol=$fecha->format('d/m/Y'); //06/07/2007
        $input = '05/09/2016';
        $format = 'd/m/Y';
        $lasasd = Carbon::parse($input)->format('Y/m/d'); // Carbon::createFromFormat($format, $input)->toDateString();
        /// $codigoControl=$this->generate('790400539356' , '851896' ,'1994428014' ,'20080725' ,'84178','J]f$gD7vCHkejLbPFGE]scPJ#UEEQzrbRz4LTJD95u6}=Dc=h6%gMC$7LtB(]vGa');
        //aqui libroorden
        return $lasasd;
    }

    public function imprirfactura($idfactura) {
        $results = DB::select("
            SELECT DISTINCT empresa.propietario,
                    empresa.actividad,
                    empresa.nombre as empresa,
                    sucursal.nombre as sucursal,
                    sucursal.direccion as direccion,
                    sucursal.telefono as telefono,
                    ciudad.nombre as ciudad,
                    pais.nombre as pais,
                    venta.formaPago,
                    (select sum(detalleventa.total) from detalleventa where detalleventa.idVenta = venta.id) as totalneto,
                    ((select sum(detalleventa.importedescuento) from detalleventa where detalleventa.idVenta = venta.id) + venta.importedescuento) as descuento,
                    empleado.nombre,
                    libroorden.NIT as facturanit,
                    factura.razonSocial,
                    factura.nroFactura,
                    venta.hora as hora,
                    factura.nroAutorizacion,
                    factura.codigoControl,
                    factura.NIT,
                    factura.total,
                    factura.totalLiteral,
                    factura.idVenta,
                    factura.fecha,
                    libroorden.fechaFin,
                    ROUND((venta.total-(venta.total*(venta.descuentocliente)/100)) ,2) AS descuentocliente,
                    venta.descuentocliente as descuentoclienteliteral
                FROM factura 
                INNER JOIN libroorden
                INNER JOIN puntoventa
                INNER JOIN venta
                INNER JOIN empleado
                INNER JOIN sucursal
                ON sucursal.id=puntoventa.idSucursal
                INNER JOIN empresa 
                ON empresa.id=sucursal.idEmpresa
                INNER JOIN ciudad
                ON ciudad.id = sucursal.idCiudad
                INNER JOIN pais
                ON pais.id = ciudad.idPais
                WHERE  libroorden.id=factura.idLibroOrden
                AND puntoventa.id=venta.idPuntoVenta
                AND venta.id=factura.idVenta
                AND puntoventa.idEmpleado=empleado.id
                AND factura.id=?
                 ", [$idfactura]);
        $empresa;
        $nroFactura;
        $formaPago;
        $nombrecajero;
        $razonSocial;
        $facturanit;
        $nroAutorizacion;
        $codigoControl;
        $NIT;
        $total;
        $totalLiteral;
        $idVenta;
        $fecha;
        $fechaFins;
        $hora;
        $propietario;
        $actividad;
        $descuento;
        $totalneto;
        $sucursal;
        $direccion;
        $telefono;
        $ciudad;
        $pais;
        $descuentocliente;
        $bool = false;
        foreach ($results as $key => $value) {
            $formaPago = $value->formaPago;
            $nombrecajero = $value->nombre;
            $razonSocial = $value->razonSocial;
            $nroAutorizacion = $value->nroAutorizacion;
            $codigoControl = $value->codigoControl;
            $NIT = $value->NIT;
            $total = $value->total;
            $totalLiteral = $value->totalLiteral;
            $idVenta = $value->idVenta;
            $fecha = $value->fecha;
            $facturanit = $value->facturanit;
            $fechaFins = $value->fechaFin;
            $nroFactura = $value->nroFactura;
            $empresa = $value->empresa;
            $hora = $value->hora;
            $propietario = $value->propietario;
            $actividad = $value->actividad;
            $descuento = $value->descuento;
            $totalneto = $value->totalneto;
            $sucursal = $value->sucursal;
            $direccion = $value->direccion;
            $telefono = $value->telefono;
            $ciudad = $value->ciudad;
            $pais = $value->pais;
            $descuentocliente=$value->descuentocliente;
            $descuentoclienteliteral=$value->descuentoclienteliteral;
            $bool = true;            
        }
        if (!$bool) {
            return redirect('/404');
        }
        $usuario;
        $idventa;
        $bool = true;
        $comanda = DB::select("call comanda (?)", [$idVenta]);
        foreach ($comanda as $key => $value) {
            $usuario = $value->empleado;
            $idventa = $value->idVenta;
            $bool = false;
        }
        if ($bool == true) {
            $usuario = "";
            $idventa = 0;
        }
        //fecha
        $fechanueva = Carbon::parse($fecha)->format('d/m/Y');
        $fechaFin = Carbon::parse($fechaFins)->format('d/m/Y');
        return self::reporte($pais, $ciudad, $telefono, $direccion, $sucursal, $formaPago, $nombrecajero,
  $razonSocial, $nroAutorizacion, $codigoControl, $NIT, $totalLiteral, $idVenta, $fechanueva, $facturanit,
  $total, $fechaFin, $nroFactura, $empresa, $hora, $propietario, $actividad, $descuento, $usuario, $idventa, 
  $comanda, $totalneto,$descuentocliente,$descuentoclienteliteral);
        //return   $formaPago; propietario
    }

    public function reporte($pais, $ciudad, $telefono, $direccion, $sucursal, $formaPago, $nombrecajero,
   $razonSocial, $nroAutorizacion, $codigoControl, $NIT, $totalLiteral, $idVenta, $fecha, $facturanit, $total, 
   $fechaFin, $nroFactura, $empresa, $hora, $propietario, $actividad, $descuento, $usuario, $idventa, $comanda,
   $totalneto,$descuentocliente,$descuentoclienteliteral) {
        // $facturanit = Carbon::parse($facturanits)->format('d-m-Y');
        //$fechaemi = Carbon::parse($fechaFin)->format('d/m/Y');
        $datosqr = "" . $facturanit . "|" . $nroFactura . "|" . $nroAutorizacion . "|" . $fecha . "|" . $total . "|" . $total . "|" . $codigoControl . "|" . $NIT . "|" . "0" . "|" . "0" . "|" . "0" . "|" . "0" . "|";
        $codigoqr = self::generadorqr($datosqr);
        $consumo = self::obtenerdatosdeventa($idVenta);
        //$pdf=\PDF::loadView('Reporte.factura',compact(['formaPago','NIT','idVenta','nombrecajero','razonSocial','nroAutorizacion','codigoControl','totalLiteral','nombrecajero','fecha','facturanit','total','codigoqr','consumo','fechaFin','nroFactura','empresa']));
        //return  $pdf->stream();
        return view('Reporte.factura',
 compact(['pais', 'ciudad', 'telefono', 'direccion', 'sucursal', 'formaPago', 'NIT', 'idVenta', 'nombrecajero', 
 'razonSocial', 'nroAutorizacion', 'codigoControl', 'totalLiteral', 'fecha', 'facturanit', 'total', 'codigoqr', 
 'consumo', 'fechaFin', 'nroFactura', 'empresa', 'hora', 'propietario', 'actividad', 'descuento', 'comanda', 
 'idventa', 'usuario', 'totalneto','descuentocliente','descuentoclienteliteral']));
    }

    ///*******************************MEtodos de factruacion*******************************************************************************************************************
    public function generadorqr($texto) {
        $d = new DNS2D();
        $todo = $d->getBarcodePNGPath($texto, "QRCODE");
        return $todo;
    }

    public function obtenerdatoslibroderoden($idempleado, $idpunto) {
        $datos = DB::select("SELECT libroorden.id,
                libroorden.NIT,
                libroorden.nroAutorizacion,
                libroorden.llave,
                libroorden.estado,
                libroorden.nroInicio,
                libroorden.nroFin,
                libroorden.fechaInicio,
                libroorden.fechaFin,
                 libroorden.idSucursal
            from puntoventa 
            INNER JOIN sucursal 
            ON sucursal.id=puntoventa.idSucursal
            INNER JOIN libroorden 
            ON libroorden.idSucursal=sucursal.id 
            AND libroorden.eliminado=0 
            AND libroorden.estado=0 
            AND libroorden.nroFin<>libroorden.nroActual
            INNER JOIN empleado on empleado.id=puntoventa.idEmpleado
            WHERE empleado.id=? and puntoventa.id=?
            ", [$idempleado, $idpunto]);
        return $datos;
    }

    public function obtenernumerodefactura($idlibro) {
        $datos = DB::select("SELECT libroorden.nroActual+1 as total,
                libroorden.nroFin as limite,
                libroorden.estado,
                libroorden.fechaFin
            from libroorden
            WHERE libroorden.id=?
            and libroorden.estado=0
             ", [$idlibro]);
        $actual;
        $hasta;
        $estado;
        $mensaje;
        $fechafin;
        $date = Carbon::now();
        $fechaactual = $date->toDateString();
        foreach ($datos as $key => $value) {
            $actual = $value->total;
            $hasta = $value->limite;
            $estado = $value->estado;
            $fechafin = $value->fechaFin;
        }
        if ($estado == 1) {
            return $mensaje = "No se puede generar factura porque no existe libro de órdenes activos";
        }
        if ($actual > $hasta) {
            $mensaje = "limite de numero de factura";
        } else {
            $mensaje = $actual;
        }
        if ($fechafin < $fechaactual) {
            $mensaje = "Fecha limite a Terminado";
        } else {
            $mensaje = $actual;
        }
        return $mensaje;
    }

    public function obtenerdatosdeventa($idventa) {
        $results = DB::select("
                SELECT
                detalleventa.cantidad,
                producto.nombre,
                detalleventa.precio as precioVenta,
                detalleventa.total,
                (detalleventa.cantidad*detalleventa.precio) as importe 
            from venta 
            INNER JOIN detalleventa
            INNER JOIN producto
            WHERE detalleventa.idVenta=venta.id 
            and producto.id=detalleventa.idProducto 
            and venta.id=?
            ", [$idventa]);
        return $results;
    }

    ///*********************************COdigo de control*******************************************************************************************
    function generate($authorizationNumber, $invoiceNumber, $nitci, $dateOfTransaction, $transactionAmount, $dosageKey) {
        //validación de datos
        if (empty($authorizationNumber) || empty($invoiceNumber) || empty($dateOfTransaction) ||
                empty($transactionAmount) || empty($dosageKey) || (!strlen($nitci) > 0 )) {
            throw new InvalidArgumentException('<b>Todos los campos son obligatorios</b>');
        } else {
            $this->validateNumber($authorizationNumber);
            $this->validateNumber($invoiceNumber);
            $this->validateNumber($dateOfTransaction);
            $this->validateNumber($nitci);
            $this->validateNumber($transactionAmount);
            $this->validateDosageKey($dosageKey);
        }

        //redondea monto de transaccion 
        $transactionAmount = $this->roundUp($transactionAmount);

        /* ========== PASO 1 ============= */
        $invoiceNumber = self::addVerhoeffDigit($invoiceNumber, 2);
        $nitci = self::addVerhoeffDigit($nitci, 2);
        $dateOfTransaction = self::addVerhoeffDigit($dateOfTransaction, 2);
        $transactionAmount = self::addVerhoeffDigit($transactionAmount, 2);
        //se suman todos los valores obtenidos
        $sumOfVariables = $invoiceNumber + $nitci + $dateOfTransaction + $transactionAmount;
        //A la suma total se añade 5 digitos Verhoeff
        $sumOfVariables5Verhoeff = self::addVerhoeffDigit($sumOfVariables, 5);

        /* ========== PASO 2 ============= */
        $fiveDigitsVerhoeff = substr($sumOfVariables5Verhoeff, strlen($sumOfVariables5Verhoeff) - 5);
        $numbers = str_split($fiveDigitsVerhoeff);
        for ($i = 0; $i < 5; $i++) {
            $numbers[$i] = $numbers[$i] + 1;
        }

        $string1 = substr($dosageKey, 0, $numbers[0]);
        $string2 = substr($dosageKey, $numbers[0], $numbers[1]);
        $string3 = substr($dosageKey, $numbers[0] + $numbers[1], $numbers[2]);
        $string4 = substr($dosageKey, $numbers[0] + $numbers[1] + $numbers[2], $numbers[3]);
        $string5 = substr($dosageKey, $numbers[0] + $numbers[1] + $numbers[2] + $numbers[3], $numbers[4]);

        $authorizationNumberDKey = $authorizationNumber . $string1;
        $invoiceNumberdKey = $invoiceNumber . $string2;
        $NITCIDKey = $nitci . $string3;
        $dateOfTransactionDKey = $dateOfTransaction . $string4;
        $transactionAmountDKey = $transactionAmount . $string5;

        /* ========== PASO 3 ============= */
        //se concatena cadenas de paso 2
        $stringDKey = $authorizationNumberDKey . $invoiceNumberdKey . $NITCIDKey . $dateOfTransactionDKey . $transactionAmountDKey;
        //Llave para cifrado + 5 digitos Verhoeff generado en paso 2
        $keyForEncryption = $dosageKey . $fiveDigitsVerhoeff;
        //se aplica AllegedRC4
        $allegedRC4String = self::allegedrc4($stringDKey, $keyForEncryption, true);

        /* ========== PASO 4 ============= */
        //cadena encriptada en paso 3 se convierte a un Array         
        $chars = str_split($allegedRC4String);
        //se suman valores ascii
        $totalAmount = 0;
        $sp1 = 0;
        $sp2 = 0;
        $sp3 = 0;
        $sp4 = 0;
        $sp5 = 0;

        $tmp = 1;
        for ($i = 0; $i < strlen($allegedRC4String); $i++) {
            $totalAmount += ord($chars[$i]);
            switch ($tmp) {
                case 1: $sp1 += ord($chars[$i]);
                    break;
                case 2: $sp2 += ord($chars[$i]);
                    break;
                case 3: $sp3 += ord($chars[$i]);
                    break;
                case 4: $sp4 += ord($chars[$i]);
                    break;
                case 5: $sp5 += ord($chars[$i]);
                    break;
            }
            $tmp = ($tmp < 5) ? $tmp + 1 : 1;
        }

        /* ========== PASO 5 ============= */
        //suma total * sumas parciales dividido entre resultados obtenidos 
        //entre el dígito Verhoeff correspondiente más 1 (paso 2)
        $tmp1 = floor($totalAmount * $sp1 / $numbers[0]);
        $tmp2 = floor($totalAmount * $sp2 / $numbers[1]);
        $tmp3 = floor($totalAmount * $sp3 / $numbers[2]);
        $tmp4 = floor($totalAmount * $sp4 / $numbers[3]);
        $tmp5 = floor($totalAmount * $sp5 / $numbers[4]);
        //se suman todos los resultados
        $sumProduct = $tmp1 + $tmp2 + $tmp3 + $tmp4 + $tmp5;
        //se obtiene base64
        $base64SIN = self::big_base_convert($sumProduct); //

        /* ========== PASO 6 ============= */
        //Aplicar el AllegedRC4 a la anterior expresión obtenida
        return self::allegedrc4($base64SIN, $dosageKey . $fiveDigitsVerhoeff);
    }

    public function big_base_convert($value) {
        $dictionary = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9",
            "A", "B", "C", "D", "E", "F", "G", "H", "I", "J",
            "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T",
            "U", "V", "W", "X", "Y", "Z", "a", "b", "c", "d",
            "e", "f", "g", "h", "i", "j", "k", "l", "m", "n",
            "o", "p", "q", "r", "s", "t", "u", "v", "w", "x",
            "y", "z", "+", "/");
        $quotient = 1;
        $word = "";
        while ($quotient > 0) {
            $quotient = floor($value / 64);
            $remainder = $value % 64;
            $word = $dictionary[$remainder] . $word;
            $value = $quotient;
        }
        return $word;
    }

    function allegedrc4($message, $key, $unscripted = false) {

        $state = range(0, 255);
        $x = 0;
        $y = 0;
        $index1 = 0;
        $index2 = 0;
        $nmen = "";
        $messageEncryption = "";

        for ($i = 0; $i <= 255; $i++) {
            //Index2 = ( ObtieneASCII(key[Index1]) + State[I] + Index2 ) MODULO 256
            $index2 = ( ord($key[$index1]) + $state[$i] + $index2) % 256;
            //IntercambiaValor( State[I], State[Index2] )
            $aux = $state[$i];
            $state[$i] = $state[$index2];
            $state[$index2] = $aux;
            //Index1 = (Index1 + 1) MODULO LargoCadena(Key)
            $index1 = ($index1 + 1 ) % strlen($key);
        }
        //PARA I = 0 HASTA LargoCadena(Mensaje)-1 HACER
        for ($i = 0; $i < strlen($message); $i++) {
            //X = (X + 1) MODULO 256
            $x = ($x + 1) % 256;
            //Y = (State[X] + Y) MODULO 256
            $y = ($state[$x] + $y) % 256;
            //IntercambiaValor( State[X] , State[Y] )
            $aux = $state[$x];
            $state[$x] = $state[$y];
            $state[$y] = $aux;
            //NMen = ObtieneASCII(Mensaje[I]) XOR State[(State[X] + State[Y]) MODULO 256]
            $nmen = ( ord($message[$i])) ^ $state[($state[$x] + $state[$y]) % 256];
            //MensajeCifrado = MensajeCifrado + "-" + RellenaCero(ConvierteAHexadecimal(NMen))            
            $nmenHex = strtoupper(dechex($nmen));
            $messageEncryption = $messageEncryption . (($unscripted) ? "" : "-") . ((strlen($nmenHex) == 1) ? ('0' . $nmenHex) : $nmenHex);
        }
        return (($unscripted) ? $messageEncryption : substr($messageEncryption, 1, strlen($messageEncryption)));
    }

    static public $d = array(
        array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9),
        array(1, 2, 3, 4, 0, 6, 7, 8, 9, 5),
        array(2, 3, 4, 0, 1, 7, 8, 9, 5, 6),
        array(3, 4, 0, 1, 2, 8, 9, 5, 6, 7),
        array(4, 0, 1, 2, 3, 9, 5, 6, 7, 8),
        array(5, 9, 8, 7, 6, 0, 4, 3, 2, 1),
        array(6, 5, 9, 8, 7, 1, 0, 4, 3, 2),
        array(7, 6, 5, 9, 8, 2, 1, 0, 4, 3),
        array(8, 7, 6, 5, 9, 3, 2, 1, 0, 4),
        array(9, 8, 7, 6, 5, 4, 3, 2, 1, 0)
    );
    static public $p = array(
        array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9),
        array(1, 5, 7, 6, 2, 8, 3, 0, 9, 4),
        array(5, 8, 0, 3, 7, 9, 6, 1, 4, 2),
        array(8, 9, 1, 6, 0, 4, 3, 5, 2, 7),
        array(9, 4, 5, 3, 1, 2, 6, 8, 7, 0),
        array(4, 2, 8, 6, 5, 7, 3, 9, 0, 1),
        array(2, 7, 9, 3, 8, 0, 6, 4, 1, 5),
        array(7, 0, 4, 6, 9, 1, 3, 2, 5, 8)
    );
    static public $inv = array(0, 4, 3, 2, 1, 5, 6, 7, 8, 9);

    private function calcsum($num) {

        if (!preg_match('/^[0-9]+$/', $num)) {
            throw new \InvalidArgumentException(sprintf("Error! Value is restricted to the number, %s is not a number.", $num));
        }

        $r = 0;
        foreach (array_reverse(str_split($num)) as $n => $N) {
            $r = self::$d[$r][self::$p[($n + 1) % 8][$N]];
        }
        return self::$inv[$r];
    }

    private function verhoeff_add_recursive($number, $digits) {
        return sprintf("%s%s", $number, self::calcsum($number));
    }

    private function addVerhoeffDigit($value, $max) {
        for ($i = 1; $i <= $max; $i++) {
            $value .= self::calcsum($value);
        }
        return $value;
    }

    private function roundUp($value) {
        //reemplaza (,) por (.)        
        $value2 = str_replace(',', '.', $value);
        //redondea a 0 decimales        
        return round($value2, 0, PHP_ROUND_HALF_UP);
    }

    function validateNumber($value) {
        if (!preg_match('/^[0-9,.]+$/', $value)) {
            throw new InvalidArgumentException(sprintf("Error! Valor restringido a número, %s no es un número.", $value));
        }
    }

    function validateDosageKey($value) {
        if (!preg_match('/^[A-Za-z0-9=#()*+-_\@\[\]{}%$]+$/', $value)) {
            throw new InvalidArgumentException(sprintf("Error! llave de dosificación,<b> %s </b>contiene caracteres NO permitidos.", $value));
        }
    }

    public function imprirfacturaresto($idfactura) {
        $results = DB::select("SELECT DISTINCT empresa.propietario,
                    empresa.actividad,
                    empresa.nombre as empresa,
                    sucursal.nombre as sucursal,
                    sucursal.direccion as direccion,
                    sucursal.telefono as telefono,
                    ciudad.nombre as ciudad,
                    pais.nombre as pais,
                    venta.formaPago,
                    (select sum(detalleventa.total) from detalleventa where detalleventa.idVenta = venta.id) as totalneto,
                    ((select sum(detalleventa.importedescuento) from detalleventa where detalleventa.idVenta = venta.id) + venta.importedescuento) as descuento,
                    empleado.nombre,
                    libroorden.NIT as facturanit,
                    factura.razonSocial,
                    factura.nroFactura,
                    venta.hora as hora,
                    factura.nroAutorizacion,
                    factura.codigoControl,
                    factura.NIT,
                    factura.total,
                    factura.totalLiteral,
                    factura.idVenta,
                    factura.fecha,
                    libroorden.fechaFin,
                    venta.observaciones
                FROM factura 
                INNER JOIN libroorden
                INNER JOIN puntoventa
                INNER JOIN venta
                INNER JOIN empleado
                INNER JOIN sucursal
                ON sucursal.id=puntoventa.idSucursal
                INNER JOIN empresa 
                ON empresa.id=sucursal.idEmpresa
                INNER JOIN ciudad
                ON ciudad.id = sucursal.idCiudad
                INNER JOIN pais
                ON pais.id = ciudad.idPais
                WHERE  libroorden.id=factura.idLibroOrden
                AND puntoventa.id=venta.idPuntoVenta
                AND venta.id=factura.idVenta
                AND puntoventa.idEmpleado=empleado.id
                AND factura.id=?
                 ", [$idfactura]);
        $empresa;
        $nroFactura;
        $formaPago;
        $nombrecajero;
        $razonSocial;
        $facturanit;
        $nroAutorizacion;
        $codigoControl;
        $NIT;
        $total;
        $totalLiteral;
        $idVenta;
        $fecha;
        $fechaFins;
        $hora;
        $propietario;
        $actividad;
        $descuento;
        $totalneto;
        $sucursal;
        $direccion;
        $telefono;
        $ciudad;
        $pais;
        $observaciones;
        foreach ($results as $key => $value) {
            $formaPago = $value->formaPago;
            $nombrecajero = $value->nombre;
            $razonSocial = $value->razonSocial;
            $nroAutorizacion = $value->nroAutorizacion;
            $codigoControl = $value->codigoControl;
            $NIT = $value->NIT;
            $total = $value->total;
            $totalLiteral = $value->totalLiteral;
            $idVenta = $value->idVenta;
            $fecha = $value->fecha;
            $facturanit = $value->facturanit;
            $fechaFins = $value->fechaFin;
            $nroFactura = $value->nroFactura;
            $empresa = $value->empresa;
            $hora = $value->hora;
            $propietario = $value->propietario;
            $actividad = $value->actividad;
            $descuento = $value->descuento;
            $totalneto = $value->totalneto;
            $sucursal = $value->sucursal;
            $direccion = $value->direccion;
            $telefono = $value->telefono;
            $ciudad = $value->ciudad;
            $pais = $value->pais;
            $observaciones = $value->observaciones;
        }
        $usuario;
        $idventa;
        $mesa;
        $estadopedido;
        $numeromesa;
        $orden;
        $comanda = DB::select("call comanda (?)", [$idVenta]);
        foreach ($comanda as $key => $value) {
            $usuario = $value->empleado;
            $idventa = $value->idVenta;
            $mesa = $value->mesa;
            $numeromesa = $value->numeromesa;
            $orden = '0' . $value->orden;
        }

        if ($mesa == '' || $mesa == "0") {
            $estadopedido = "Para llevar";
        } else {
            $estadopedido = "Para " . $numeromesa;
        }
        //fecha
        $fechanueva = Carbon::parse($fecha)->format('d/m/Y');
        $fechaFin = Carbon::parse($fechaFins)->format('d/m/Y');
        return self::reporteresto($observaciones, $numeromesa, $mesa, $estadopedido, $orden, $pais, $ciudad, $telefono, $direccion, $sucursal, $formaPago, $nombrecajero, $razonSocial, $nroAutorizacion, $codigoControl, $NIT, $totalLiteral, $idVenta, $fechanueva, $facturanit, $total, $fechaFin, $nroFactura, $empresa, $hora, $propietario, $actividad, $descuento, $usuario, $idventa, $comanda, $totalneto);
        //return   $formaPago; propietario
    }

    public function reporteresto($observaciones, $numeromesa, $mesa, $estadopedido, $orden, $pais, $ciudad, $telefono, $direccion, $sucursal, $formaPago, $nombrecajero, $razonSocial, $nroAutorizacion, $codigoControl, $NIT, $totalLiteral, $idVenta, $fecha, $facturanit, $total, $fechaFin, $nroFactura, $empresa, $hora, $propietario, $actividad, $descuento, $usuario, $idventa, $comanda, $totalneto) {
        // $facturanit = Carbon::parse($facturanits)->format('d-m-Y');
        //$fechaemi = Carbon::parse($fechaFin)->format('d/m/Y');
        $datosqr = "" . $facturanit . "|" . $nroFactura . "|" . $nroAutorizacion . "|" . $fecha . "|" . $total . "|" . $total . "|" . $codigoControl . "|" . $NIT . "|" . "0" . "|" . "0" . "|" . "0" . "|" . "0" . "|";
        $codigoqr = self::generadorqr($datosqr);
        $consumo = self::obtenerdatosdeventa($idVenta);
        //$pdf=\PDF::loadView('Reporte.factura',compact(['formaPago','NIT','idVenta','nombrecajero','razonSocial','nroAutorizacion','codigoControl','totalLiteral','nombrecajero','fecha','facturanit','total','codigoqr','consumo','fechaFin','nroFactura','empresa']));
        //return  $pdf->stream();
        return view('Reporte.facturaresto', compact(['observaciones', 'numeromesa', 'mesa', 'estadopedido', 'orden', 'pais', 'ciudad', 'telefono', 'direccion', 'sucursal', 'formaPago', 'NIT', 'idVenta', 'nombrecajero', 'razonSocial', 'nroAutorizacion', 'codigoControl', 'totalLiteral', 'fecha', 'facturanit', 'total', 'codigoqr', 'consumo', 'fechaFin', 'nroFactura', 'empresa', 'hora', 'propietario', 'actividad', 'descuento', 'comanda', 'idventa', 'usuario', 'totalneto']));
    }

}
