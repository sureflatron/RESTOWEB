 @extends('Layouts.Panel')
@section('Contenido')
@include('Modal.modal')
<div id="breadcrumbs-wrapper" class=" grey lighten-3">
          <div class="container">
            <div class="row">
              <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Vista de Unidad de medida</h5>
                <ol class="breadcrumb">
  
                    <li><a href="/index/">Inicio</a>
                  </li>        
                </ol>
              </div>
            </div>
          </div>
        </div>

          <h2>Listado de  Unidad</h2>

      <div class="row">
  <div class="col s6">

 <input placeholder="Buscar Unidad de medida" id="buscar" type="text">          
  <a class="waves-effect waves-light btn modal-trigger" href="#modal2">+</a>

</div>

  <a class="waves-effect waves-light btn" onclick="Buscadordeproducto()">Buscar</a>
</div>
<table class="centered" id="unidad">
<thead>
	<th>Nombre</th>
	<th>Abreviatrua</th>
	<th>Operacion</th>
	 
 
</thead>

<tbody id="datos">
 
</tbody>
 </table>
  
     
 @stop

  @section('scripts')
 {!! Html::script('js/addunidad.js') !!}
  
   
@endsection