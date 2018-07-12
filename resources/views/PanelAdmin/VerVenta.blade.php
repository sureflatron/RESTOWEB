@extends('Layouts.Panel')
@section('Contenido')

  <div id="breadcrumbs-wrapper" class=" grey lighten-3">
          <div class="container">
            <div class="row">
              <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Lista de ventas </h5>
                <ol class="breadcrumb">
                        
                    <li><a href="/index/">Inicio</a>
                  </li>   
                </ol>
              </div>
            </div>
          </div>
        </div>
 

<div class="container">


     <div class="row">
     <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
    <div class="col s6">
       <h4 >Listado de ventas : </h4>
      </div>
     </div>


 <div class="row">
  <div class="col s6">

 <input placeholder="Buscar Venta" id="buscar" type="text">          
       <a class="btn" href="/Ventas/">+</a>

</div>

  <a class="waves-effect waves-light btn" onclick="Buscadordeproducto()">Buscar</a>
</div>
     <table class="centered" id="tablacategoria">
<thead>
 
    <th>ID Venta</th>
    <th>Fecha</th>
  <th>Registrado</th>
    <th>Estado</th>
  <th>Total</th>
  <th>Operacion</th>
   
 
</thead>

<tbody id="datos">
 
</tbody>
 </table>  

 

 
  
    </div>

  

 

<div class="divider"></div>

@stop
  @section('scripts')
 {!! Html::script('js/addlistaventas.js') !!}
@endsection