<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>RECIBO</title>
        <link rel="stylesheet" href="/css/reportes2.css" media="all" />
        <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">      
    </head>
    <body>
        <div class="btnPrint">
            <button class="btn-floating btn-large waves-effect waves-light red" onclick="window.print();">Imprimir</button>
        </div>
        <header class="tamano">
            <h1 id="nombreempresa">{{ $nombre }} </h1>
            <p style="margin-top: -21px; font-size: 14px;">De: {{ $propietario }}</p>
            <div id="company" class="clearfix">
                <div style="text-align: center; font-size: 15px; text-transform: uppercase;">
                    {{ $sucursal }}<br>
                    {{ $direccion }}<br>
                    TELEFONO - {{ $telefonosuc }}<br>
                    {{ $ciudad }} - {{ $pais }}
                </div>
                <div>
                    <h2 id="factura">RECIBO <!--ENVIO NRO--></h2>                                        
                    <!--strong><p style="margin-top: -21px; font-size: 14px;">{{ $idventa }}</p></strong-->
                </div>
                <div id="descripcion">
                    <p>{{ $actividad }}</p>
                </div>
            </div>  
            <div id="project" style="font-size: 15px !important;">
                <!--div><span><strong>Señor(es):</strong></span> {{ $codcliente }}</div-->
                <div><span><strong>Fecha:</strong>{{ $fecha }}<strong> Hora:</strong>{{ $hora }}</span></div>
                <div><span><strong>Cliente:</strong></span> {{ $cliente }}</div>
                <div><span><strong>Vendedor:</strong></span> {{ $empleado }}</div>               
                <div>
                    @if($formapago == 'Credito')
                    <span><strong>Fecha de Entrega:</strong> {{ $cobrarCada }}                           
                        @endif</span>
                </div>



                <div>
                    <span><strong>Telf. Pedido:</strong> 
                        @if($telefonopedido == null)
                        @else
                        {{  $telefonopedido }}
                        @endif</span>
                </div>
                <div>
                    <span><strong>Celular:</strong> 
                        @if($celular == null)
                        @else
                        {{  $celular }}
                        @endif</span>
                </div>
                <div><span><strong>CI:</strong></span> {{ $ci }}</div>
                <div><span><strong>Ciudad:</strong></span> {{ $cuidadEnvio }}</div>  
                <div>
                    <span><strong>Hora de Entrega:</strong> 
                        @if($horaentrega == null)
                        @else
                        {{  $horaentrega }}
                        @endif</span>
                </div>
                <div><span><strong>Forma de Pago::</strong></span> {{ $formapago }}</div> 
                <div>
                    <span><strong>Fecha de Entrega:</strong> 
                        @if($fechaentrega == null)
                        @else
                        {{  $fechaentrega }}
                        @endif</span>
                </div>
                <div><span><strong>Persona Que Recibe:</strong></span> {{ $personaentrega }}</div>
                <div>
                    <span><strong>Direccion:</strong> 
                        @if($direccionenvio == null)
                        @else
                        {{  $direccionenvio }}
                        @endif</span>
                </div>
                <div>
                    <span><strong>Impor. Transporte:</strong> 
                        @if($importetransporte == null)
                        @else
                        {{  $importetransporte }}<span>.Bs</span>
                        @endif</span>
                </div>
            </div>

            <!--div style="text-align: center;">
                <h2><strong>NOTA DE VENTA DE ENVIO NRO. {{ $idventa }} </strong></h2>
            </div-->
        </header>
        <main  class="tamano">
            <div id="detalleVenta " style="border-bottom: 0px solid !important;">
                <table>
                    <thead class="encabezado">
                        <tr>
                            <th> CANTIDAD</th>
                            <th> DETALLE </th>
                            <th> P.UNITARIO </th>
                            <th> SUBTOTAL </th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($results as $results)
                        <tr>
                            <td >{{ $results->cantidad }}</td>
                            <td >{{ $results->producto }}</td>
                            <td >{{ $results->precio }}</td>
                            <td >{{ $results->totalsubpro }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div id="total">
                    <div>
                        <p>
                            <strong>TOTAL Bs. {{ $total }} </strong>
                        </p>
                    </div>
                    @if($descuento == null || $descuento<1)
                    @else
                    <div>
                        <p>
                            <strong>DESCUENTO Bs. {{ $descuento }}</strong>
                        </p>
                    </div>
                    @endif

                    @if($importetransporte == null || $importetransporte<1)
                    @else
                    <div>
                        <p>
                            <strong>IMPORTE-TRANSPORTE Bs. {{ $importetransporte }}</strong>
                        </p>
                    </div>
                    @endif

                    <div>
                        <!--p>
                            <strong>TOTAL NETO Bs.{{ $totalVenta }}</strong>
                         </p-->   
                        <div>                                                                    
                              <!--spsn><strong>Numero Orden :{{ $orden }}</strong></span-->
                            <p>
                                <span><strong>TOTAL NETO Bs.{{ $totalVenta+$importetransporte }}</strong></span>                        
                            </p>  
                        </div>

                    </div>
                    @if($formapago == 'Credito')
                    <div>
                        <p>
                            <strong>TOTAL PAGO INICIAL Bs.{{ $aCuenta }}</strong>
                        </p>
                    </div>
                    <div>
                        <p>
                            <strong>SALDO Bs.{{ $saldo }}</strong>
                        </p>
                    </div>
                    @endif
                </div>
            </div>
            @if($garantia > 0)
            <div>
                <p>
                    <strong>GARANTIA POR ALQUILER Bs.{{ $garantia }}</strong>
                </p>
            </div>
            @endif
            @if ($formapago == 'Credito')
            <div>
                <p>
                    <strong>OBSERVACIONES:</strong>
                </p>                                                    
                </br>
                <p> @if($observaciones == null)
                    Sin Observaciones 
                    @else
                    {{ $observaciones }}
                    @endif</p>

            </div>
            @endif


        </main>
        <footer  style="text-align: center; border-top: 0px solid !important; padding-top: 10px;">
            <div>
                <span>OsB SMB</span>
                <span>www.osbolivia.com</span>

            </div>
            <div>
                <span><strong>N&uacute;mero Orden :</strong> {{ $orden }}</span>

            </div>
        </footer>

        <!--table style="width: 100%;">
            <tr style="padding: 10px;font-size: 13px;">
                <td colspan="4" style="text-align: center; border-bottom: 0px !important;  padding-top: 50px !important;"><strong style="border-top: 1px solid black;">FIRMA CLIENTE:</strong></td>
                <td style="text-align: center; border-bottom: 0px !important;  padding-top: 50px !important;"><strong style="border-top: 1px solid black;">FIRMA VENDEDOR</strong></td>
            </tr>
        </table-->
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

        </br>
        </br>
        </br>
        </br>                                            
        <div   class="tamano">
            <div  class="saltoDePagina">
                <div>
                    <div >
                        <p style="margin-top: -21px; font-size: 16px;"><strong>N&uacute;mero Orden :</strong> {{ $orden }}</p>
                        <span><strong>Comanda Cocina </strong></span>
                    </div>
                    <!--h2 id="factura">
                    <!-- Numero Orden : {{ $orden }}-->
                    <!--/h2 -->                   
                </div>
                <div class="notices" style="font-size: 20px;"> 
                    <div class="notices">                        
                        <div style="font-size: 15px;">
                            <strong>Fecha Hora : </strong>{{ $fecha }}   {{ $hora }}
                        </div>
                        <div>
                            <strong>Vendedor: </strong>{{ $empleado }}
                        </div>
                        <div>
                            <strong> 
                                {{ $estadopedido }}
                            </strong>
                        </div>
                    </div>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Cantidad</th>
                            <th class="desc">Detalle</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($comanda as $consumo)
                        <tr>
                            <td >{{ $consumo->cantidad }}</td>
                            <td >{{ $consumo->producto }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div> 

    </body>
</html>