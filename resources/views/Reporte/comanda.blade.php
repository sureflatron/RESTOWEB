<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Comanda</title>
        <link rel="stylesheet" href="/css/reportes2.css" media="all" />
        <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
    </head>
    <body >
        <div class="btnPrint">
            <button class="btn-floating btn-large waves-effect waves-light red" onclick="window.print();">Imprimir</button>
        </div>
        <header  class="tamano">
            <div id="company" class="clearfix">
                <strong class="nombreempresa">COMANDA</strong>
                <div class="notices" style="font-size: 20px;">
                    <div><strong>Numero venta: </strong>{{ $idventa }} </div>
                    <div><strong>Fecha Hora: </strong>{{ $fecha }}  {{ $hora }}  </div>
                    <div><strong>Vendedor: </strong>{{ $usuario }}  </div>
                </div>
            </div>
        </header>
        <main class="tamano">
            <table>
                <thead>
                    <tr>
                        <th>Cantidad</th>
                        <th class="desc">Detalle</th>
                        <th>Color</th>
                        <th>Talla</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($results as $consumo)
                    <tr>
                        <td >{{ $consumo->cantidad }}</td>
                        <td >{{ $consumo->producto }}</td>
                        <td >{{ $consumo->color }}</td>
                        <td >{{ $consumo->tamano }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </main>
    </body>
</html>