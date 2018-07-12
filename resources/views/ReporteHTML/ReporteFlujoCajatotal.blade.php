@extends('Layouts.Reporte')
@section('Contenido')
         <div id="breadcrumbs-wrapper" class=" grey lighten-3">
          <div class="container">
            <div class="row">
              <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title"  style="text-align:center;">Flujo de caja  de todos los usuario de la fecha {{ $fechafin  }} a  {{ $Fechainicio }}</h5>
                <ol class="breadcrumb" >
                 
                     
                    <li><a onClick="imprimir()"  id="desaparece"  href="#" >Imprimir a PDF</a>
                  </li>     
                   <li><a    href="/descargarreporteflujocajasinusuario/{{$Fechainicio}}/{{$fechafin}}">Exportar a excel</a>
                  </li> 
                   <li><a href="/GestionarReporte">Volver</a>
                  </li> 

                </ol>
              </div>
            </div>
          </div>
        </div>
 



<input type="hidden"  id="fechafin" value="{{ $fechafin  }}">
<input type="hidden" id="Fechainicio"  value="{{ $Fechainicio }}">
 
 
<div class="row">
    <div class="col s12"><p> </p></div>
    <div class="col s12 m4 l2"><p></p></div>
    <div class="col s12 m4 l8">  <div style="page-break-after: always;" id="imprmir">
  
<h4>Ingreso</h4>
 <table   BORDER=1 WIDTH=100%>
<thead>

                    <tr>
            <th  >Tipo</th>
            <th  >Id</th>
            <th >Fecha</th>
            <th >Hora</th>
                        <th>Empleado</th>
            <th  >importe</th>
          </tr>
</thead>

<tbody id="datoingreso">
 
</tbody>
<tfoot>
 
          <tr>
            <td colspan="2"></td>
            <td colspan="2" id="totalingreso">TOTAL INGRESO</td>
          
          </tr>
        </tfoot>
 </table>
 
<h4>Egreso</h4>

<table   BORDER=1 WIDTH=100%>
<thead>

                    <tr>
            <th  >Tipo</th>
            <th  >Id</th>
            <th >Fecha</th>
            <th >Hora</th>
          <th  >Empleado</th>
            <th  >importe</th>
          </tr>
</thead>

<tbody id="datoegreso">
 
</tbody>
<tfoot>
 
          <tr>
            <td colspan="2"></td>
            <td colspan="2" id="totalegreso">TOTAL EGRESO</td>
          
          </tr>
        </tfoot>
 </table>
 
<h3>Resumen</h3>
 








  <div class="row">
      <div class="  col s12"> </div>
      <div class=" col s12 m6"><h4>TOTAL INGRESO :</h4></div>
            <div class=" col s12 m6"><h4 id="totalingresos">TOTAL INGRESO :</h4></div>

   <li  ><div class="divider"></div></li>
    </div> 
     <div class="row">
      <div class=" col s12"> </div>
       <div class=" col s12 m6"><h4>(-)TOTAL EGRESO  :</h3> </div>
            <div class=" col s12 m6"><h4 id="totalegresos">TOTAL EGRESO :</h4></div>
          <li  ><div class="divider"></div></li>
    </div>

  <div class="row">
      <div class=" col s12"> </div>
      <div class="grid-example col s12 m6"><h4>Total Egreso y Ingreso:</h4> </div>
            <div class="grid-example col s12 m6"><h4  > {{ $totalsumado }}</h4>  </div>
    </div>



    <div class="row">
      <div class=" col s12"> </div>
      <div class=" col s12 m6"><h4>TOTAL Tarjeta  :</h4> </div>
            <div class=" col s12 m6"><h4 id="totaltarjeta"></h4>  </div>
    </div>


    <div class="row">
      <div class=" col s12"> </div>
      <div class="grid-example col s12 m6"><h4>TOTAL:</h4> </div>
            <div class="grid-example col s12 m6"><h4  >{{ $total }}</h4>  </div>
    </div>


  <div style="page-break-after: always;">

 </div>

 </div> 


 </div>
    <div class="col s12 m4 l2"><p>  </a> </p></div>


  </div>


@stop
  @section('scripts')
    {!!Html::script('js/extra/FlujoCajatotales.js')!!}
@endsection


