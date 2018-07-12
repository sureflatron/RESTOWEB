 @extends('Layouts.Panel')
@section('Contenido')
@include('Modal.modalCargo')
<div id="breadcrumbs-wrapper" class=" grey lighten-3">
          <div class="container">
            <div class="row">
              <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Vista de Cargo</h5>
                <ol class="breadcrumb">
                 
                    <li><a href="/index/">Inicio</a>
                  </li>        
                </ol>
              </div>
            </div>
          </div>
        </div>
    <div class="container">


<h2>Cargo</h2>
 
       <!-- Modal Trigger -->

      <div class="row">
  <div class="col s6">

 <input placeholder="Buscar producto" id="buscar" type="text">          
  <a class="waves-effect waves-light btn modal-trigger" href="#modal1">+</a>

</div>

  <a class="waves-effect waves-light btn" onclick="Buscadordeproducto()">Buscar</a>
</div>

  <table class="centered" id="tablacategoria">
<thead>
  
  <th>Nombre</th>
  <th>Operacion</th>
   
 
</thead>

<tbody id="datos">
 
</tbody>
 </table>
  
 
 
 <div class="input-field col s6 m6 l6">
                @if(Session::has('message'))
            <div id="message">{{ Session::get('message') }}</div>
        @endif
          </div>
    </div>
 <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
 

 

</div>


 @stop
  @section('scripts')
 {!! Html::script('js/addCargo.js') !!}
@endsection