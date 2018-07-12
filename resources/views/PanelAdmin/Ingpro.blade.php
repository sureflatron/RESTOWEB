@extends('Layouts.Panel')
@section('Contenido')
@include('Modal.modaldetalle')

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
 
        <div class="col s12 m6 l3"> <h4>Agregar Ingrediente a :</h4>  </div>
    <div class="col s12 m6 l3"><h4>{{ $producto }}</h4></div>
    </div>
  <div class="row">
   <div class="input-field col s12 m2 l1">
   {!! Form::select('Id_categoria', $ingrediente,null, ['id'=>'idingrediente']) !!}
    
        <label for="Ingrediente">Ingrediente</label>
   </div>
    <div class="input-field col s12 m2 l1">
      {!! Form::select('unidad', $unidad,null, ['id'=>'idunidad']) !!}

    <label for="Unidad">Unidad</label>
    </div>
    <div class="input-field col s12 m4 l3" >    <label for="Unidad">Cantidad :</label> 
     {!!Form::text('nuevacantidad',null, ['id'=>'nuevacantidad'])!!}

  
    </div>
    <div class="input-field col s12 m4 l3" >
    <label for="Unidad">Costo :</label> 
{!!Form::text('Costo',null, ['id'=>'costo'])!!}
     </div>
     <div class="input-field col s12 m2 l3">    <a class="btn" id="guardar">+</a>    </div>
     
      </div>


 

<table class="centered" >
<thead>
 
		<th>Ingrediente</th>
		<th>Unidad</th>	 
		<th>cantidad</th>
        <th>Costo</th>
	 <th>Operaciones</th>
	 
 
</thead>

<tbody id="datos">
<td>
 <select id= "ingrediente"></select>
<select id= "unidad"></select>
</tbody>
</td>
 </table>
 <div class="row"> 
 
<div class="col s12 m6 l3"> <h5>Total costo aproximado: </h5> </div> 
        <div class="col s12 m2 l7">  <h5 id="total"> 0</h5>   </div> 
 </div> 
   
 @stop
@section('scripts')
 {!! Html::script('js/addingpros.js') !!}
  
   
@endsection