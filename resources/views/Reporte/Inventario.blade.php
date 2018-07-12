<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Reporte de Inventario</title>
    <link rel="stylesheet" href="css/comprasreporte.css" media="all" />
  </head>
  <body>
    <header class="clearfix">
      <div id="logo">
        <img src="{{ $imagen }}" width="263" height="301">
      </div>
      <div id="company">
        <h2 class="name">{{ $nomempresa }}</h2>
        <div>
          {{ $correo }}
        </div>
        <div>
          <a href="mailto:company@example.com">{{ $web }}</a>
        </div>
      </div>
      </div>
    </header>
    <main>
      <div id="details" class="clearfix">
        <div id="invoice">
          <h1>Nota de Inventario</h1>
          <div class="date">
            Codigo :  {{ $idcompra }}
          </div>
          <div class="date">
            Fecha: {{ $fecha }}
          </div>
          <div class="date">
            Almacen: {{ $almacen }}</div>
          <div class="date">
            Tipo Inventario: {{ $tipoinventario }}
          </div>
          <div class="date">
            Descripcion: {{ $glosa }}
          </div>
        </div>
      </div>
      <table border="0" cellspacing="0" cellpadding="0">
        <thead>
          <tr>
            <th class="qty">Producto</th>
            <th class="qty">Cantidad</th>
          </tr>
        </thead>
        <tbody>
          @foreach($results as $results)
            <tr>
              <td class="desc">{{ $results->nombre }}</td>
              <td class="qty">{{ $results->cantidad }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </main>
    <footer>
      {{ $nomempresa }} por Registrado por: 
    </footer>
  </body>
</html>