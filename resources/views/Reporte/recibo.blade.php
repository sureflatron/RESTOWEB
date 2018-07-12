<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>RECIBO PAGO CREDITO</title>
        <link rel="stylesheet" href="/css/reportes2.css" media="all" />
        <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
    </head>
    <body >
        <div class="btnPrint">
            <button class="btn-floating btn-large waves-effect waves-light red" onclick="window.print();">Imprimir</button>
        </div>
        <header   class="tamano">
            <h1 id="nombreempresa">{{ $empresa }}</h1>  
            <p style="margin-top: -21px; font-size: 14px;">De: {{ $propietario }}</p>
            <div id="company" class="clearfix">
                <div style="text-align: center; font-size: 15px; text-transform: uppercase;">
                    {{ $sucursal }}<br>
                    {{ $direccion }}<br>
                    Telefono - {{ $telf }}<br>
                    {{ $ciudad }} - {{ $pais }}
                </div>
                <div>
                    <h2 id="factura">RECIBO</h2>
                </div>
            </div>
            <div id="project" style="font-size: 15px;">
                <div>
                    <span><strong>Fecha:</strong>{{ $fecha }}
                </div> 
                <div>
                    <span><strong>Celular:</strong></span>
                    @if($celular == null)
                    S/C
                    @else
                    {{ $celular }}
                    @endif
                    &nbsp; &nbsp; &nbsp;
                    <span><strong>Telfono:</strong></span>
                    @if($telefono == null)
                    S/T
                    @else
                    {{ $telefono }}
                    @endif
                </div>
                <div>
                    <span><strong>Señor(es):</strong></span> {{ $cliente }}
                </div>     
            </div>
        </header>
        <main  class="tamano">
            <div id="detalleVenta" style="border-bottom: 0px !important;">
                <table>
                    <thead class="encabezado">
                        <tr>
                            <th> CANTIDAD</th>
                            <th class="desc"> DETALLE </th>
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
                    <div style="padding-bottom: 5px;">
                        <div>
                            <p>
                                <strong>TOTAL </strong>Bs.{{ $total }} 
                            </p>
                        </div>
                        <div>
                            <p>
                                <strong>DESCUENTO </strong>Bs.{{ $descuento }} 
                            </p>
                        </div>
                        <div>
                            <p>
                                <strong>TOTAL NETO DEL CREDITO </strong>Bs.{{ $totalVenta }} 
                            </p>
                        </div>
                    </div>
                    <div style="padding-bottom: 5px; border-bottom: 1px solid black; border-top: 1px solid black;">
                        <div>
                            <p>
                                <strong>TOTAL CUOTA:</strong> Bs. {{ $credito }}
                            </p>
                        </div>
                        <div>
                            <p>
                                <strong>MONTO ACTUAL PAGADO: </strong>Bs.  {{ $totalcancelado }}
                            </p>
                        </div>
                        <div>
                            <p>
                                <strong>SALDO ACTUAL CUOTA:</strong>Bs. {{ $tot }}
                            </p>
                        </div>
                        <!--                        <div>
                                                    <p>
                                                        @if( ($totalVenta - $cancelado) <= 0 )
                                                        <strong>SALDO RESTANTE DEL CREDITO: </strong>Bs. 0
                                                        @else 
                                                        <strong>SALDO RESTANTE DEL CREDITO: </strong>Bs.  {{ $totalVenta - $cancelado }} 
                                                        @endif
                                                    </p>
                                                </div>-->
                    </div>
                    <div>
                        <div>
                            <p>
                                <strong>TOTAL PAGADO A LA FECHA: </strong>Bs. {{ $cancelado }}
                            </p>
                        </div>
                        <div>
                            <p>
                                @if( ($totalVenta - $cancelado) <= 0 )
                                <strong>TOTAL DEUDA A LA FECHA: </strong>Bs. 0
                                @else 
                                <strong>TOTAL DEUDA A LA FECHA: </strong>Bs.  {{ $totalVenta - $cancelado }} 
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                <div style="font-size: 18px;">
                    <div>
                        <p>
                            @if( ($totalVenta - $cancelado) <= 0 )
                            @else
                            @if( $fechacuota == null )

                            @else
                            <Strong>Fecha Siguiente Cuota: </Strong>{{ $fechacuota }}
                            @endif
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </main>
        <footer  class="tamano">
            <div>
                <span>OsB POS / RESTO</span>
                <span>www.osbolivia.com</span>
            </div>
        </footer>
    </body>
</html>