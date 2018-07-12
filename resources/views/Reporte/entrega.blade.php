<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>COMPRABANTE DE ENTREGA</title>
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
                        <p style="padding-top: -15px; font-size: 14px;">De: {{ $propietario }}</p>
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
                <h2><strong>COMPROBANTE DE ENTREGA DEL PRODUCTO</strong></h2>
            </div>
        </header>

        <table style="width: 100%; text-align: left; font-size: 15px;">
            <tr>
                <td><strong>Cliente:</strong> &nbsp;&nbsp;&nbsp;{{ $cliente}}</td>
                <td><strong>Celular: </strong>&nbsp;&nbsp;&nbsp;
                    @if($celular == null)
                    @else
                    {{ $celular }}
                    @endif</td>
            </tr>
            <tr>
                <td><strong>CI:</strong>&nbsp;&nbsp;&nbsp; {{  $nit }}</td>
                <td><strong>Ciudad:</strong>&nbsp;&nbsp;&nbsp; {{ $ciudadcliente }}</td>
            </tr>
            <tr>
                <td><strong>E-mail:</strong>&nbsp;&nbsp;&nbsp;
                    @if($correo == null)

                    @else
                    {{ $correo }}
                    @endif</td>
                <td><strong>Forma de Pago:</strong>&nbsp;&nbsp;&nbsp; {{ $formapago }}</td>
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
                    </tr>
                </thead>
                <tbody>
                    @foreach($results as $results)
                    <tr>
                        <td >{{ $results->cantidad }}</td>
                        <td>{{ $results->codigoDeBarra }}</td>
                        <td >{{ $results->producto }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div>
                <div style="text-align: left;">
                    <h5><strong>OBSERVACIONES:</strong></h5>
                </div>
                <div style="text-align: justify; border: 1px solid black; border-radius: 2px; padding: 20px;">
                    <p style="font-size: 13px;"> @if($observaciones == null)
                        Sin Observaciones 
                        @else
                        {{ $observaciones }}
                        @endif</p>
                </div>
            </div>
            <table style="width: 100%;">
                <tr style="padding: 10px;font-size: 13px;">
                    <td colspan="4" style="text-align: center; border-bottom: 0px !important;  padding-top: 100px !important;"><strong style="border-top: 1px solid black;">RECIBI CONFORME:</strong></td>
                    <td style="text-align: center; border-bottom: 0px !important;  padding-top: 100px !important;"><strong style="border-top: 1px solid black;">ENTREGUE CONFORME</strong></td>
                </tr>
                <tr style="font-size: 13px;">
                    <td colspan="4" style="text-align: left; border-bottom: 0px !important; padding-left: 100px;"><strong>NOMBRE:</strong></td>
                    <td style="text-align: left; border-bottom: 0px !important; padding-left: 100px;"><strong >NOMBRE:</strong></td>
                </tr>
                <tr style="font-size: 13px;">
                    <td colspan="4" style="text-align: left; border-bottom: 0px !important; padding-left: 100px;"><strong>CI:</strong></td>
                    <td style="text-align: left; border-bottom: 0px !important; padding-left: 100px;"><strong >CI:</strong></td>
                </tr>
            </table>
            <footer style="position: absolute; padding-top: 20px; text-align: center; width: 100%; bottom: 0; color: #000; font-size: 10px;">
                OsB POS www.osbolivia.com
            </footer>
    </body>
</html>