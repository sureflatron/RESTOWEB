<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
    <title>OsB POS</title>
 
 
 
 
 <link href="css/flujo.css" type="text/css" rel="stylesheet"  >

 
</head>

<body  >
  <h1 >Flujo de caja de la fecha   {{ $Fechainicio }} al  {{ $fechafin }} </h1>

  <h3>Ingreso</h3>
       <table border="0" cellspacing="0" cellpadding="0">
        <thead>
 


                    <tr>
            <th class="unit">Tipo</th>
            <th class="desc">Id</th>
            <th class="unit">Fecha</th>
            <th class="qty">Hora</th>
                        <th class="qty">Empleado</th>
            <th class="total">importe</th>
          </tr>
        </thead>
        <tbody>
    @foreach($ventas as $ventas)
      <tr>
        <td class="unit">{{ $ventas->Tipo }}</td>
           <td  class="desc">{{ $ventas->id }}</td>
              <td class="unit">{{ $ventas->fecha }}</td>
               <td class="qty">{{ $ventas->hora }}</td>
      <td class="qty">{{ $ventas->empleado }}</td>
     <td class="total">{{ $ventas->importe }}</td>
       </tr>


 

        @endforeach
        </tbody>
<tfoot>
 
          <tr>
            <td colspan="2"></td>
            <td colspan="2">TOTAL INGRESO</td>
            <td>{{ $totalingreso }}</td>
          </tr>
        </tfoot>
      </table>
    <h3>Egreso</h3>
          <table border="0" cellspacing="0" cellpadding="0">
        <thead>
 

                    <tr>
            <th class="unit">Tipo</th>
            <th class="desc">Id</th>
            <th class="unit">Fecha</th>
            <th class="qty">Hora</th>
                        <th class="qty">Empleado</th>
            <th class="unit">importe</th>
          </tr>
        </thead>
        <tbody>
    @foreach($compras as $compras)
      <tr>
        <td class="unit">{{ $compras->Tipo }}</td>
           <td  class="desc">{{ $compras->id }}</td>
              <td class="unit">{{ $compras->fecha }}</td>
               <td class="qty">{{ $compras->hora }}</td>
      <td class="qty">{{ $compras->empleado }}</td>
     <td class="unit">{{ $compras->importe }}</td>
       </tr>



 

        @endforeach
        </tbody>
<tfoot>
 
     
          <tr>
            <td colspan="2"></td>
            <td colspan="2">TOTAL EGRESO</td>
            <td>{{ $totalegreso }}</td>
          </tr>
        </tfoot>
      </table>


  <h3>Resumen</h3>
          <table>
 
 
 <tfoot>
          <tr>
          
            <td colspan="2">TOTAL INGRESO</td>
            <td>{{ $totalingreso }}</td>
          </tr>

          <tr>
    
            <td colspan="2">(-)TOTAL EGRESO</td>
            <td>{{ $totalegreso }}</td>
          </tr>
 
          <tr>
        
            <td colspan="2">TOTAL EFECTIVO</td>
            <td>{{ $totalsumado }}</td>
          </tr>
    <tr>
        
            <td colspan="2">TOTAL TARJETA</td>
            <td>{{ $totaletarjeta }}</td>
          </tr>
              <tr>
        
            <td colspan="2" class="total">TOTAL</td>
            <td class="total">{{ $total }}</td>
          </tr>
        </tfoot>
      </table>
 

 
 
 


</body>

</html>
 