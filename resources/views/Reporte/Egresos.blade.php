<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Reporte de Compra</title>
        <link rel="stylesheet" href="css/comprasreporte.css" media="all" />
    </head>
    <body>
        <header class="clearfix">
            <div id="logo">
                <img src="{{ $imagen }}" width="263" height="301">
            </div>
            <div id="company">
                <h2 class="name">{{ $nomempresa }}</h2>

                <div>{{ $correo }}</div>
                <div><a href="mailto:company@example.com">{{ $web }}</a></div>
            </div>
        </div>
    </header>
    <main>
        <div id="details" class="clearfix">

            <div id="invoice">
                <h1>Nota de Egreso</h1>
                <div class="date">Codigo :  {{ $id }}</div>
                <div class="date">Fecha: {{ $fecha }}</div>

                <div class="date">Proveedor : {{ $proveedor }}</div>
                <div class="date">Sucursal : {{ $sucursal }}</div>
            </div>
        </div>

        <table border="0" cellspacing="0" cellpadding="0">
            <thead>
                <tr>


                    <th class="unit">Pagado a:</th>
                    <th class="qty">Glosa</th>
                    <th class="qty">Tipo Egreso</th>
                    <th class="qty">Importe</th>

                </tr>
            </thead>

            <tbody>

                <tr>



                    <td class="desc">{{ $pagadoA }}</td>
                    <td class="desc">{{ $glosa }}</td>
                    <td class="desc">{{ $tipo }}</td>
                    <td class="desc">{{ $importe }}</td>


                </tr>

            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2"></td>
                    <td colspan="2"> </td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td colspan="2"></td>
                    <td> </td>
                </tr>

            </tfoot>
        </table>


    </main>
    <footer>
        {{ $nomempresa }} por Registrado por:
    </footer>
</body>
</html>