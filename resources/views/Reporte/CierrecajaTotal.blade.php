<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
    <title>Rest-Bar</title>
 
 
 
 
 <link href="css/reporte.css" type="text/css" rel="stylesheet"  >


</head>

<body  >
  
  <table >
    <thead>
      <tr>
   
    <th>idVenta</th>
      <th>Usuario</th>
         <th>Fecha</th>
         <th>Forma de pago</th>
          <th>Cliente</th>
          <th>Sucursal</th>
  
    <th>Total</th>
 
      </tr>
    </thead>
    <tbody>
 
      @foreach($productos as $productos)
      <tr>
        
        <td >{{ $productos->idventa }}</td>
           <td >{{ $productos->usuario }}</td>
           <td >{{ $productos->fecha }}</td>
              <td >{{ $productos->formaPago }}</td>
      <td >{{ $productos->cliente }}</td>
            <td >{{ $productos->sucursal }}</td>
        <td >{{ $productos->total }}</td>
 
 
    
 


      </tr>


        @endforeach

    </tbody>
  </table>
<h3>Total vendido : {{ $totales}}</h3>
</body>

</html>
 

 