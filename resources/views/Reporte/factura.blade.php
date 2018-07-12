<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Factura</title>
        <link rel="stylesheet" href="/css/reportes2.css" media="all" />
        <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
    </head>
    <body >
        <div class="btnPrint">
            <button class="btn-floating btn-large waves-effect waves-light red" onclick="window.print();">Imprimir</button>
        </div>
        <header class="tamano">
            <h1 id="nombreempresa">{{ $empresa }} </h1>
            <p style="margin-top: -21px; font-size: 14px;">De: {{ $propietario }}</p>
            <div id="company" class="clearfix">
                <div style="text-align: center; font-size: 15px; text-transform: uppercase;">
                    {{ $sucursal }}<br>
                    {{ $direccion }}<br>
                    TELEFONO - {{ $telefono }}<br>
                    {{ $ciudad }} - {{ $pais }}
                </div>
                <div>
                    <h2 id="factura">FACTURA</h2>
                </div>
                <div id="datosfactura">
                    <div>
                        <span><strong>Nit:</strong></span>{{ $facturanit }}
                    </div>
                    <div>
                        <span><strong>Nº FACTURA:</strong></span>{{ $nroFactura }}
                    </div>
                    <div>
                        <span><strong>Nº AUTORIZACION:</strong></span>{{ $nroAutorizacion }}
                    </div>
                </div>
                <div id="descripcion">
                    <p>{{ $actividad }}</p>
                </div>
            </div>
            <div id="project">
                <div>
                    <span><strong>Fecha:</strong>{{ $fecha }}<strong> Hora:</strong>{{ $hora }}</span>
                </div> 
                <div>
                    <span><strong>NIT/CI:</strong></span> {{ $NIT }}
                </div>
                <div>
                    <span><strong>Señor(es):</strong></span> {{ $razonSocial }}
                </div>
            </div>
        </header>
        <main  class="tamano">
            <div id="detalleVenta">
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
                        @foreach($consumo as $consumo)
                        <tr>
                            <td >{{ $consumo->cantidad }}</td>
                            <td >{{ $consumo->nombre }}</td>
                            <td >{{ $consumo->precioVenta }}</td>
                            <td >{{ $consumo->importe }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div id="total">
                    <div>
                        <p>
                            <strong>TOTAL Bs. {{ $totalneto }} </strong>
                        </p>
                    </div>
                    @if($descuento > 0 )
                    <div>
                        <p>
                            <strong>DESCUENTO %. {{ $descuento }}</strong>
                        </p>
                    </div>
                    @else 
                    <div>
                        <p>
                            <strong>DESCUENTO %. {{ $descuentoclienteliteral }}</strong>
                        </p>
                    </div>
                    @endif
                    
                    @if($descuentocliente > 0 )
                    <div>
                        <p>
                            <strong>TOTAL NETO Bs.{{ $descuentocliente }}</strong>
                        </p>
                    </div>
                    @else 
                    <div>
                        <p>
                            <strong>TOTAL NETO Bs.{{ $total }}</strong>
                        </p>
                    </div>
                    @endif
                </div>
                <!--                <div>
                                    <span>Cajero :</span> {{ $nombrecajero }}
                                </div>-->
            </div>
            <div id="totLiteral">
                <span>SON: </span>{{ $totalLiteral }}
            </div>
<!--            <div>
                <div class="descripcion" style="font-size: 15px;">
                    <p>
                        No se admiten cambios, ni devoluciones pasadas 48 horas despues de la compra, muchas gracias!
                    </p>
                </div>
            </div>-->
            <div id="notices">
                <!-- <div>
                  <span>Forma de pago:</span>{{$formaPago}}
                </div>-->
                <div class="notice">
                    <div>
                        <span>CODIGO DE CONTROL: </span> {{ $codigoControl }}
                    </div>
                    <div>
                        <span>FECHA LIMITE DE EMISION: </span>{{ $fechaFin }}
                    </div>
                </div>
            </div>
        </main>
        <div id="img"  class="tamano">
            <img src="/{{$codigoqr}}" alt="codQR" height="130" width="130" />
        </div> 
        <footer  class="tamano">
            <p>
                <span>
                    "ESTA FACTURA CONTRIBUYE AL DESARROLLO DEL PAIS, EL USO ILICITO DE ESTA SERA SANCIONADO DE ACUERDO A LA LEY"
                </span>
            </p>
            <p>Ley Nro 453: Los servicios deben suministrarse en condiciones de inocuidad, calidad y seguridad</p>
            <div>
                <span>OsB POS</span>
                <span>www.osbolivia.com</span>
            </div>
        </footer>
    </body>
</html>