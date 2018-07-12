<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Comanda de Venta</title>
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
        <div><a >{{ $web }}</a></div>
      </div>
      </div>
    </header>
    <main>
      <div id="details" class="clearfix">
     
        <div id="invoice">
          <h1>Nota de Venta</h1>
  <div class="date">Codigo :  {{ $idventa }}</div>
    
          <div class="date">Fecha: {{ $fecha }}</div>
        <div class="date">  {{ $sucursal }}</div>
        </div>
      </div>

      <table border="0" cellspacing="0" cellpadding="0">
        <thead>
          <tr>
      
            <th class="desc">PRODUCTO</th>
      <th class="desc">PrecioUnitario</th>
            <th class="qty">Cantidad</th>
            <th class="total">SUBTOTAL</th>
          </tr>
        </thead>

        <tbody>
                 @foreach($results as $results)
          <tr>
             
            <td class="desc">{{ $results->producto }}</td>
             <td class="unit">{{ $results->  precio }}</td>
            <td class="unit">{{ $results->  cantidad }}</td>
 
            <td class="total">{{ $results->total }}</td>
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
  Registrado por:{{ $nomempresa }} 
    </footer>
  </body>
</html>