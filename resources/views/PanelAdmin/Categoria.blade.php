@extends('Layouts.Panel')
@section('Contenido')
@include('Modal.modalcategoria')
<div id="breadcrumbs-wrapper" class=" grey lighten-3">
          <div class="container">
            <div class="row">
              <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Vista de Categoria</h5>
                <ol class="breadcrumb">
                  <li><a href="/Producto/">Agregar Producto</a>
                  </li>   
                    <li><a href="/index/">Inicio</a>
                  </li>        
                </ol>
              </div>
            </div>
          </div>
        </div>
    <div class="container">


<h2>Listado de Categoria</h2>
 
       <!-- Modal Trigger -->

      <div class="row">
  <div class="col s6">

 <input placeholder="Buscar Categoria" id="buscar" type="text">          
  <a class="waves-effect waves-light btn modal-trigger" href="#modal2">+</a>

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
 {!! Html::script('js/addcategoria.js') !!}
   
 
   
@endsection

<script type="text/javascript">
  

</script>