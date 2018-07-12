<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
    <title>Rest-Bar</title>
 
 
 
  {!!Html::script('js/materialize.min.js')!!}  
  {!!Html::script('js/jquery-1.11.2.min.js')!!}
  {!!Html::script('js/extra/reporteventa.js')!!}
      {!!Html::style('css/materialize.css')!!} 
    {!!Html::style('css/style.css')!!}


</head>

<body  >
  
  <table >
    <thead>
      <tr>
   
    <th>idVenta</th>
         <th>Fecha</th>
         <th>Forma de pago</th>
          <th>Cliente</th>
          <th>Sucursal</th>
  
    <th>Total</th>
 
      </tr>
    </thead>
    <tbody id="datos">
  


        @endforeach

    </tbody>
  </table>
<h3 id="totales"> </h3>
</body>

</html>
 

 