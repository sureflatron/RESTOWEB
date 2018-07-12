@extends('Layouts.Reporte')
@section('Contenido')
         <div id="breadcrumbs-wrapper" class=" grey lighten-3">
          <div class="container">
            <div class="row">
              <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title"  style="text-align:center;">Reportes </h5>
                <ol class="breadcrumb" >
                 
                     
                    <li><a onClick="imprimir()"  id="desaparece"  href="#" >Imprimir a PDF</a>
                  </li>     
                   <li><a id="excel"  onClick="imprimir()"  href="#">Exportar a excel</a>
                  </li> 
                   <li><a href="#">Volver</a>
                  </li> 

                </ol>
              </div>
            </div>
          </div>
        </div>
 





<div class="row">
    <div class="col s12"><p> </p></div>
    <div class="col s12 m4 l2"><p></p></div>
    <div class="col s12 m4 l8">  <div style="page-break-after: always;" id="imprmir">
  
 <table   BORDER=1 WIDTH=100%>
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
 
</tbody>
 </table>
     <h3 id="totales">Total vendido : </h3>
  <div style="page-break-after: always;">

 </div>

 </div> 


 </div>
    <div class="col s12 m4 l2"><p>  </a> </p></div>


  </div>


@stop
  @section('scripts')
  {!!Html::script('js/extra/reporteventa.js')!!}
  
@endsection


