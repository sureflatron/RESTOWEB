<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
    <title>OsB POS</title>
 
 
 
 
 <link href="css/reporte.css" type="text/css" rel="stylesheet"  >


</head>

<body>
 
  <table >
    <thead>
      <tr>
      
        <th>Codigo</th>
         <th>Nombre</th>
         <th>Precio Venta</th>
  
  
 
      </tr>
    </thead>
    <tbody>

      @foreach($productos as $productos)
      <tr>
        
        <td >{{ $productos->id }}</td>
           <td >{{ $productos->nombre }}</td>
              <td >{{ $productos->precioVenta }}</td>

        


      </tr>

        @endforeach

    </tbody>
  </table>
  
   
</body>

</html>