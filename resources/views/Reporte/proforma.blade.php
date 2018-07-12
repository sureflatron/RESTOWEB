<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>PROFORMA VENTA</title>
        <style>
            strong{
                color: #000;
            }
            #table{
                width: 100%;
                font-size: 12px;
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
            #datos{
                width: 70%;
                /*border-collapse: collapse;*/
            }
        </style>
    </head>
    <body>
        <header class="clearfix">
            <h1 style="text-align: center; font-size: 20px;"><strong>PROFORMA</strong></h1>
            <div style="text-align: right;">
                <div style="color: #000;"><strong>Fecha:</strong> {{ $fecha }}</div>
                <div style="color: #000;"><strong>Cod:</strong> {{ $idventa }}</div>
            </div>
            <div style="position: relative;  padding-bottom: 50px;">
                <img src="{{ $imagen }}" alt="logo-empresa" width="150px;" style="padding-top: -50px;"/>
                <div style="position: absolute; padding-left: 20px; padding-top: -30px;">
                    <table id="datos" style="margin-left: 110px; text-align: left; font-size: 13px;">
                        <tr >
                            <td style="padding-left: -140px; /*border-left: 1px solid black; border-top: 1px solid black; border-bottom: 1px solid black;*/" >Cliente:</td>
                            <td style="padding-left: -20px; /*border-right: 1px solid black; border-top: 1px solid black; border-bottom: 1px solid black;*/">{{$cliente}}</td>
                        </tr>
                        <tr>
                            <td style="padding-left: -140px; /*border-left: 1px solid black; border-bottom: 1px solid black;*/">Telefono:</td>
                            <td style="padding-left: -20px; /*border-right: 1px solid black;border-bottom: 1px solid black;*/">@if ($celular == null)

                                @else
                                {{$celular}}
                                @endif</td>
                        </tr>
                        <tr>
                            <td style="padding-left: -140px; /*border-left: 1px solid black; border-bottom: 1px solid black;*/">Email:</td>
                            <td style="padding-left: -20px; /*border-right: 1px solid black;border-bottom: 1px solid black;*/">@if ($correo == null)

                                @else
                                {{$correo}}
                                @endif</td>
                        </tr>
                    </table>
                </div>
            </div>
        </header>
        <main >
            <table style="width: 100%;" id="table">
                <tr>
                    <th>IMAGEN</th>
                    <th>CONCEPTO</th>
                    <th>DESCRIPCION</th>
                    <th>DIMENSIONES</th>
                    <th>MATERIAL</th>
                    <th>CANT.</th>
                    <th>PREC.UNIT.</th>
                    <th>TOTAL</th>
                </tr>
                @foreach($results as $results)
                <tr>
                    <td ><img src="{{ $results-> imgpro }}" alt="LP" width="60px;"></td>
                    <td >{{ $results-> producto }}</td>
                    <td style="font-size: 10px;" >{{ $results-> descripcion }}</td>
                    <td >{{ $results-> tamano }}</td>
                    <td >{{ $results-> material }}</td>
                    <td >{{ $results-> cantidad }}</td>
                    <td >{{ $results-> precio }}</td>
                    <td style="border-left: 1px solid black;">{{ $results-> subtotal }}</td>
                </tr>
                @endforeach
                <tr style="padding: 10px; font-size: 15px;">
                    <td colspan="7" style="text-align: right;"><strong>TOTAL PROFORMA:</strong></td>
                    <td style="border-left: 1px solid black;">{{$total}}</td>
                </tr>
                 @if($descuento > 0 )
                <tr style="padding: 10px; font-size: 13px; ">
                        <td colspan="7" style="text-align: right; border-bottom: 0px !important;"><strong>DESCUENTO:</strong></td>
                        <td style="border-left: 1px solid black; border-bottom: 0px !important;">Bs. {{ $descuento }} </td>
                </tr>
                    @else 
                <tr style="padding: 10px; font-size: 13px; ">
                        <td colspan="7" style="text-align: right; border-bottom: 0px !important;"><strong>DESCUENTO %:</strong></td>
                        <td style="border-left: 1px solid black; border-bottom: 0px !important;">%. {{$descuentoclienteliteral}} </td>
                </tr>
                    @endif
                    @if($descuentocliente > 0 )
                <tr style="padding: 10px; font-size: 13px;">
                        <td colspan="7" style="text-align: right; "><strong>TOTAL VENTA :</strong></td>
                        <td style="border-left: 1px solid black; ">Bs. {{ $total-($total*($descuentoclienteliteral/100)) }}</td>
                </tr>
                    @else 
                <tr style="padding: 10px; font-size: 13px;">
                        <td colspan="7" style="text-align: right; "><strong>TOTAL VENTA:</strong></td>
                        <td style="border-left: 1px solid black; ">Bs. {{ $total-$descuento }}</td>
                </tr>
                    @endif
            </table>
            <!--            <div style="text-align: right; padding-top: 20px;">
                            <strong>TOTAL PROFORMA:</strong> {{$total}}
                        </div>-->
        </main>
        <footer style="position: absolute; padding-top: 20px; text-align: center; font-weight: bold; width: 100%; bottom: 0; color: #000;">
            {{ $direccion }}<br>
            Telefono: {{ $telefonoEmpresa }}<br>
            {{ $ciudad }}-{{ $pais }}
        </footer>
        <!--position: absolute;-->
    </body>
</html>