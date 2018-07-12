<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>RECIBO OsB SMB</title>
        <style>
            .empresa{
                text-align: center;
            }
            #table{
                width: 100%;
                font-size: 13px;
                border-collapse: collapse;
            }
            #table tr:nth-child(even) {
                background-color: #fff;
            }
            #table tr:nth-child(odd) {
                background-color:#fff;
            }
            #table th {
                background-color: #000;
                color: white;
            }
            #table td{
                text-align: center;
                padding: 10px;
                border-bottom: 1px solid black;
            }
        </style>
    </head>
    <body>
        <header>
            <div class="row">
                <div class="empresa" style="position: relative;" >
                    <div style="width: 40%;">
                        <img src="{{ $imagen }}" alt="logo-empresa" width="100px;"/>
                        <h1 id="nombreempresa" style=" font-size: 18px;">{{ $nombre }} </h1>      
                        <!--<p style="padding-top: -15px; font-size: 14px;">De: {{ $propietario }}</p>-->
                        <div style="text-align: center; font-size: 12px; padding-top: -5px; text-transform: uppercase;">
                            {{ $sucursal }}<br>
                            {{ $direccion }}<br>
                            TELEFONO - {{ $telefonosuc }}<br>
                            {{ $ciudad }} - {{ $pais }}
                        </div>
                    </div>
                    <div style="position: absolute;">
                        <div style="text-align: left !important; padding-top: -150px; padding-left: 70%; font-size: 14px;">
                            <div>
                                <strong>Codigo de Cliente:</strong> &nbsp;&nbsp;&nbsp; {{ $codcliente }}  
                            </div>
                            <div>
                                <strong>Fecha:</strong> &nbsp;&nbsp;&nbsp; {{ $fecha }}
                            </div>
                            <div>
                                <strong>Hora:</strong> &nbsp;&nbsp;&nbsp; {{ $hora }}
                            </div>
                            @if($formapago == 'Credito')
                            <div>
                                <strong>Fecha de Entrega:</strong>&nbsp;&nbsp;&nbsp; {{ $cobrarCada }}
                            </div>
                            @endif
                            <div>
                                <strong>Vendedor:</strong>&nbsp;&nbsp;&nbsp; {{ $empleado }}
                            </div>
                        </div>    
                    </div>
                </div>
            </div>
            <div style="text-align: center;">
                <h2><strong>NOTA DE VENTA DE ENVIO NRO. {{ $idventa }} </strong></h2>
            </div>
        </header>

        <table style="width: 100%; text-align: left; font-size: 15px;">
            <tr>
                <td><strong>Cliente:</strong> &nbsp;&nbsp;&nbsp;{{ $cliente}}</td>
                <td><strong>Impor. Transporte:</strong>&nbsp;&nbsp;&nbsp;
                    @if($importetransporte == null)

                    @else
                    {{ $importetransporte }}
                    @endif</td>
            </tr>
            <tr>
                <td><strong>Telf. Pedido:</strong>&nbsp;&nbsp;&nbsp;
                    @if($telefonopedido == null)

                    @else
                    {{ $telefonopedido }}
                    @endif</td>
                <td><strong>Celular: </strong>&nbsp;&nbsp;&nbsp;
                    @if($celular == null)
                    @else
                    {{ $celular }}
                    @endif</td>
            </tr>
            <tr>
                <td><strong>CI:</strong>&nbsp;&nbsp;&nbsp; {{  $ci }}</td>
                <td><strong>Ciudad:</strong>&nbsp;&nbsp;&nbsp; {{ $cuidadEnvio }}</td>
            </tr>
            <tr>
                <td><strong>Hora de Entrega:</strong>&nbsp;&nbsp;&nbsp;
                    @if($horaentrega == null)

                    @else
                    {{ $horaentrega }}
                    @endif</td>
                <td><strong>Forma de Pago:</strong>&nbsp;&nbsp;&nbsp; {{ $formapago }}</td>
            </tr>
            <tr>
                <td><strong>Fecha de Entrega:</strong>&nbsp;&nbsp;&nbsp;
                    @if($fechaentrega == null)

                    @else
                    {{ $fechaentrega }}
                    @endif</td>
                <td><strong>Persona Que Recibe:</strong>&nbsp;&nbsp;&nbsp; {{ $personaentrega }}</td>
            </tr>
            <tr>
                <td><strong>Direccion:</strong>&nbsp;&nbsp;&nbsp;
                    @if($direccionenvio == null)

                    @else
                    {{ $direccionenvio }}
                    @endif</td>
            </tr>
        </table>
        <div style="text-align: center;">
            <h3><strong>Detalle de Venta</strong></h3>
        </div>
        <div style="border-bottom: 0px !important;">
            <table style="width: 100%;" id="table">
                <thead>
                    <tr>
                        <th> CANTIDAD</th>
                        <th> CODIGO DE BARRA</th>
                        <th> DETALLE </th>
                        <th> P.UNITARIO </th>
                        <th> % DESCUENTO</th>
                        <th> IMPORTE DESCUENTO</th>
                        <th> P. VENTA REAL</th>
                        <th> SUBTOTAL </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($results as $results)
                    <tr>
                        <td >{{ $results->cantidad }}</td>
                        <td>{{ $results->codigoDeBarra }}</td>
                        <td >{{ $results->producto }}</td>
                        <td >{{ $results->precio }}</td>
                        <td > {{ $results->porproductopor }}</td>
                        <td > {{ $results->imporproducto }}</td>
                        <td > {{ $results->preciofinal }}</td>
                        <td >{{ $results->totalsubpro }}</td>
                    </tr>
                    @endforeach
                    <tr style="padding: 10px; font-size: 13px;">
                        <td colspan="7" style="text-align: right; border-bottom: 0px !important;"><strong>TOTAL:</strong></td>
                        <td style="border-left: 1px solid black; border-bottom: 0px !important;">Bs. {{$total}}</td>
                    </tr>
                    <tr style="padding: 10px; font-size: 13px; ">
                        <td colspan="7" style="text-align: right; border-bottom: 0px !important;"><strong>DESCUENTO:</strong></td>
                        <td style="border-left: 1px solid black; border-bottom: 0px !important;">Bs. {{ $descuento }} </td>
                    </tr>
                    <tr style="padding: 10px; font-size: 13px;">
                        <td colspan="7" style="text-align: right; "><strong>TOTAL VENTA:</strong></td>
                        <td style="border-left: 1px solid black; ">Bs. {{ $totalVenta }}</td>
                    </tr>
                    @if($formapago == 'Credito')
                    <tr style="padding: 10px; font-size: 13px;">
                        <td colspan="7" style="text-align: right; border-bottom: 0px !important;"><strong>TOTAL PAGO INICIAL:</strong></td>
                        <td style="border-left: 1px solid black; border-bottom: 0px !important;">Bs. {{ $aCuenta }}</td>
                    </tr>
                    <tr style="padding: 10px; font-size: 13px;">
                        <td colspan="7" style="text-align: right;"><strong>SALDO:</strong></td>
                        <td style="border-left: 1px solid black;">Bs. {{ $saldo }} </td>
                    </tr>
                    @endif
                    @if($garantia > 0)
                    <tr style="padding: 10px; font-size: 13px;">
                        <td colspan="7" style="text-align: right;"><strong>GARANTIA POR ALQUILER:</strong></td>
                        <td style="border-left: 1px solid black;">Bs. {{ $garantia }} </td>
                    </tr>
                    @endif
                </tbody>
            </table>
            @if ($formapago == 'Credito')
            <div>
                <div style="text-align: left;">
                    <h5><strong>OBSERVACIONES:</strong></h5>
                </div>
                <div style="text-align: justify; border: 1px solid black; border-radius: 2px; padding: 10px;">
                    <p style="font-size: 13px;"> @if($observaciones == null)
                        Sin Observaciones 
                        @else
                        {{ $observaciones }}
                        @endif</p>
                </div>
            </div>
            @endif
            <table style="width: 100%;">
                <tr style="padding: 10px;font-size: 13px;">
                    <td colspan="4" style="text-align: center; border-bottom: 0px !important;  padding-top: 50px !important;"><strong style="border-top: 1px solid black;">FIRMA CLIENTE:</strong></td>
                    <td style="text-align: center; border-bottom: 0px !important;  padding-top: 50px !important;"><strong style="border-top: 1px solid black;">FIRMA VENDEDOR</strong></td>
                </tr>
            </table>
<!--            <div>
                <p style="text-align: center; font-size: 10px;">
                    Las modificaciones en los vestidos no se realizaran hasta completar el pago del mismo. 
                    La prueba de vestido se debe realizar con un máximo de 25 días antes de la fecha del matrimonio. 
                    Por la naturaleza de nuestros productos no se aceptan cambios ni devoluciones.
                    A la firma del presente recibo, la novia confirma la aprobación de todo lo descrito en este documento. 
                </p>
            </div>-->
            @if($formapago == 'Credito')
            <div>
                <div style="text-align: center;">
                    <h3><strong>PLAN DE PAGOS</strong></h3>
                </div>
                <table style="width: 100%;" id="table">
                    <thead>
                        <tr>
                            <th>FECHA</th>
                            <th>IMPORTE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cuotas as $results)
                        <tr>
                            <td>{{ $results->fechaVencimiento }}</td>
                            <td>{{ $results->importe }} Bs.</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            <footer style="position: absolute; padding-top: 20px; text-align: center; width: 100%; bottom: 0; color: #000; font-size: 10px;">
                OsB SMB www.osbolivia.com
            </footer>
    </body>
</html>