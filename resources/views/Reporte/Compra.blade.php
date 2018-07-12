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
          <h1>Nota de Compra</h1>
          <div class="date">Codigo :  {{ $idcompra }}</div>
          <div class="date">Almacen: {{ $almacen }}</div>
          <div class="date">Fecha: {{ $fecha }}</div>
          <div class="date">Proveedor: {{ $proveedor }}</div>
          <div class="date">Glosa: {{ $glosa }}</div>
        </div>
      </div>
      <table border="0" cellspacing="0" cellpadding="0">
        <thead>
          <tr>
            <th class="desc">PRODUCTO</th>
            <th class="unit">P/U</th>
            <th class="qty">Cantidad</th>
            <th class="total">SUBTOTAL</th>
          </tr>
        </thead>
        <tbody>
          @foreach($results as $results)
            <tr>   
              <td class="desc">{{ $results->nombre }}</td>
              <td class="unit">{{ $results->costo }}</td>
              <td class="qty">{{ $results->cantidad }}</td>
              <td class="total">{{ $results->subtotal }}</td>
            </tr>
           @endforeach
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
          <tr>
            <td colspan="2"></td>
            <td colspan="2">TOTAL :</td>
            <td>{{ $results->total }}</td>
          </tr>
        </tfoot>
      </table>
    </main>
    <footer>
    {{ $nomempresa }} por Registrado por:
    </footer>
  </body>
</html>