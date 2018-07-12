<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>RECIBO</title>
        <link rel="stylesheet" href="/css/reportes2.css" media="all" />
        <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
    </head>
    <body >
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
                    <h2 id="factura">RECIBO </h2>
                </div>
                <div id="descripcion">
                    <p>{{ $actividad }}</p>
                </div>
            </div>
            <div id="project" style="font-size: 15px !important;">
                <div>
                    <span><strong>Fecha:</strong>{{ $fecha }}<strong> Hora:</strong>{{ $hora }}</span>
                </div> 
                <div>
                    <span><strong>NIT/CI:</strong></span> {{ $nit }}
                </div>
                <div>
                    <span><strong>Señor(es):</strong></span> {{ $cliente }}
                </div>
                <div>
                    <span><strong>Telefono:</strong> @if($telefono == null)
                        @else
                        {{  $telefono }}
                        @endif<strong> Celular:</strong> @if($celular == null)

                        @else
                        {{ $celular }}
                        @endif</span>
                </div>
                <div>
                    <span><strong>Correo:</strong>@if($correo == null)

                        @else
                        {{ $correo }}
                        @endif</span>
                </div>
            </div>
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
                            <td >{{ $results->subtotal }}</td>
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
                    <div>
                        <p>
                            <strong>DESCUENTO Bs. {{ $descuento }}</strong>
                        </p>
                    </div>
                    <div>
                        <!--p>
                            <strong>TOTAL NETO Bs.{{ $totalVenta }}</strong>
                         </p-->   
                        <div>                                                                    
                              <!--spsn><strong>Numero Orden :{{ $orden }}</strong></span-->
                            <p>
                                <span><strong>TOTAL NETO Bs.{{ $totalVenta }}</strong></span>                        
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
        </main>
        @if ($formapago == 'Credito')
        <div class="tamano">
            <div style="text-align: left;">
                <strong style="font-size: 15px;">OBSERVACIONES:</strong>
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
        @if($formapago == 'Credito')
        <div class="tamano">
            <div style="text-align: center;">
                <strong style="font-size: 15px;">PLAN DE PAGOS</strong>
            </div>
            <table style="width: 100%;" id="table">
                <thead>
                    <tr class="encabezado">
                        <th>FECHA</th>
                        <th>IMPORTE</th>
                    </tr>
                </thead>
                <tbody style="font-size: 13px;">
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
        <footer  style="text-align: center; border-top: 0px solid !important; padding-top: 10px;">
            <div>
                <span>OsB SMB</span>
                <span>www.osbolivia.com</span>
            </div>
            <div>
                <span><strong>N&uacute;mero Orden :</strong> {{ $orden }}</span>

            </div>
        </footer>
        <div   class="tamano">
            <div  class="saltoDePagina">
                <div>
                    <h2 id="factura">Comanda Cocina</h2>
                </div>
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
                            <strong>Vendedor: </strong>{{ $usuario }}
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
                <div>
                    <h6 style="text-align: left; margin: 0px;">
                        OBSERVACIONES

                    </h6>
                    <div style="border:1px solid black; font-size: 15px" >
                        @if($observaciones == null)
                        No hay observaciones
                        @else
                        {{$observaciones}}
                        @endif
                    </div>
                </div> 
            </div>
        </div> 
    </body>
</html>