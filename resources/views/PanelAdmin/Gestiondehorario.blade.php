i@extends('Layouts.Panel')
@section('Contenido')
@include('Modal.modalHorario')
<div id="breadcrumbs-wrapper" class=" grey lighten-3">
          <div class="container">
            <div class="row">
              <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Vista de Horario</h5>
                <ol class="breadcrumb">
                <li><a href="/index/">Inicio</a>
                  </li>        
                </ol>
              </div>
            </div>
          </div>
        </div> 



 <input type="hidden"  value="{{ $idturno }}" id="idturno">
  <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
 
       <div class="row">
 
        <div class="col s12 m6 l3"> <h4>Agregar Horario a :</h4>  </div>
    <div class="col s12 m6 l3"><h4>{{ $turno }}</h4></div>
 
    </div>
    <div class="row">
      <div class="input-field col s12 m2 l0">
       <span>Hora de entrada</span>
   <input type="time"  id="horaentrada"    step="1800">
       
   </div>
     <div class="input-field col s12 m2 l0">

                <span>Hora de Salida</span>
    <input type="time"  id="horasalida"   step="1800" >
    
   </div>
    <div class="input-field col s12 m2 l2">    <a class="btn" id="guardar" href="#">+</a>    </div>
        </div>
<table class="centered" >
<thead>
 
		<th>Hora de  Entrada </th>
		<th>Hora de salida </th>	 
	 
	 <th>Operaciones</th>
	 
 
</thead>

<tbody id="datos">
 
 
</tbody>
 
 </table>

@stop

@section('scripts')
 {!! Html::script('js/addhorario.js') !!}
   
 
   
@endsection