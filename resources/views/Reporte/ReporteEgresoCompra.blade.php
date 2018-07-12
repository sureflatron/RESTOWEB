<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
    <title>OsB POS</title>
 
 
 
 
 <link href="css/flujo.css" type="text/css" rel="stylesheet"  >

 
</head>

<body  >
  <h1 >Flujo de Egreso de la fecha   del {{ $Fechainicio }} al  {{ $fechafin }} </h1>

 
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

 

 
 
 


</body>

</html>
 