@extends('Layouts.Panel')
@section('Contenido')
@include('Modal.modalcomposicionproducto')
 
<div id="breadcrumbs-wrapper" class=" grey lighten-3">
          <div class="container">
            <div class="row">
              <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Ingrediente Producto</h5>
                <ol class="breadcrumb">
                <li><a href="/index/">Inicio</a>
                  </li>        
                </ol>
              </div>
            </div>
          </div>
        </div>

  <input type="hidden"  value="{{ $idproducto }}" id="Idproducto">
  <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
 
          <div class="row">
 
        <div class="col s12 m6 l3"> <h4>Agregar Composicion a :</h4>  </div>
    <div class="col s12 m6 l3"><h4>{{ $nombreproducto }}</h4></div>
    </div>
  <div class="row">
   <div class="input-field col s12 m4 l2">
   {!! Form::select('idproducto', $productos,null, ['id'=>'idcomposicion']) !!}
    
        <label for="Ingrediente">Producto</label>
   </div>
     
    <div class="input-field col s12 m4 l3" >    <label for="Unidad">Cantidad :</label> 
     {!!Form::text('nuevacantidad',null, ['id'=>'nuevacantidad'])!!}

  
    </div>
     
     <div class="input-field col s12 m2 l3">    <a class="btn" id="guardar">+</a>    </div>
     
      </div>


 

<table class="centered" >
<thead>
 
		<th>Producto</th>
		<th>Cantidad</th>	 
		 
        
	 <th>Operaciones</th>
	 
 
</thead>

<tbody id="datos">
<td>
  
</tbody>
</td>
 </table>

 
@stop


@section('scripts')
 {!! Html::script('js/addcompo.js') !!}
   
 
   
@endsection