@extends('Layouts.Panel')
@section('Contenido')

  <div id="breadcrumbs-wrapper" class=" grey lighten-3">
          <div class="container">
            <div class="row">
              <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Nuevo  Producto</h5>
                <ol class="breadcrumb">
                     
                    <li><a href="/index/">Inicio</a>
                  </li>   
                </ol>
              </div>
            </div>
          </div>
        </div>
       <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
 <div class="col s6">
       <h4 >Formulario de  Empleado : </h4>
      </div>

<div class="row">
    <form class="col s12">
      <div class="row">
        <div class="input-field col s12">
          <input     id="nombre" type="text"  >
          <label for="disabled">Nombre :</label>
        </div>
        </div>
          <div class="row">
        <div class="input-field col s12">
    <select id="genero">
   
      <option value="Masculino">Masculino</option>
      <option value="Femenino">Femenino</option>
      <option value="Otro">Otro</option>
    </select>
      <label for="disabled">Genero :</label>
   
        </div>
        </div>

        <div class="row">
        <div class="input-field col s12">
          <span >Fecha de nacimiento :</span> 
      <input type="date"  id="fechanacimiento">
   
        </div>
        </div>

             <div class="row">
        <div class="input-field col s12">
    
      <input type="text"  id="telefonofijo">
      <label for="disabled">Telefono Fijo :</label>
        </div>
        </div>
       <div class="row">
        <div class="input-field col s12">
    
      <input type="text"  id="celular">
      <label  >Celular :</label>
        </div>
        </div>
           <div class="row">
        <div class="input-field col s12">
    
      <input type="text"  id="Docidentidad">
      <label  >Documento de identidad :</label>
        </div>
        </div>
            <div class="row">
        <div class="input-field col s12">
    
      <input type="text"  id="correo">
      <label  >Correo electronico :</label>
        </div>
        </div>

       <div class="row">
        <div class="input-field col s12">
    <select id="Cargo">
   
   
    </select>
      <label for="disabled">Cargo :</label>
   
        </div>
        </div>

              <div class="row">
        <div class="input-field col s12">
    <select id="turno">
   
      <option value="Masculino">Media mañana</option>
      <option value="Femenino">Media tarde </option>
      <option value="Otro">Completo </option>
    </select>
      <label for="disabled">Turno :</label>
   
        </div>
        </div>

    </form>
  </div>
    <div class="row">


        <div class="input-field col s6" id="Guardaryvolveresconder" >
              <a class="btn" id="guardar">Guardar y volver </a>
                       
        </div>
 
      </div>
 

<div class="row" id="ingredienteycomposicionmostrar" style='display:none;'>
    
     
  </div>
   


@stop
@section('scripts')
 {!! Html::script('js/addempleado.js') !!}
@endsection
  