@extends('Layouts.Reporte')
@section('Contenido')
         <div id="breadcrumbs-wrapper" class=" grey lighten-3">
          <div class="container">
            <div class="row">
              <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title"  style="text-align:center;">Reporte Raking de producto de la fecha  {{ $Fechainicio  }} a  {{ $fechafin  }}</h5>
                <ol class="breadcrumb" >
                 
                     
                    <li><a onClick="imprimir()"  id="desaparece"  href="#" >Imprimir a PDF</a>
                  </li>     
                   <li><a href="/descargarRakingtotal/{{ $Fechainicio  }}/{{ $fechafin }}">Exportar a excel</a>
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
  
 <table   BORDER=1 WIDTH=100% id="tabla">
<thead>
 
 
            <tr>
            <th  >Producto</th>
            <th  >Cantidad vendida</th>
            <th >Importe total vendido</th>
            <th  >Sucursal</th>
          </tr>
</thead>


<tbody id="datos">
 
</tbody>
 </table>
      
  <div style="page-break-after: always;">

 </div>

 </div> 


 </div>
    <div class="col s12 m4 l2"><p>  </a> </p></div>


  </div>


@stop
  @section('scripts')
 
   {!!Html::script('js/extra/reporteraking.js')!!}
@endsection

