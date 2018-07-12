@extends('Layouts.Panel')
@section('Contenido')
@include('Modal.modalEmpleado')

<div id="breadcrumbs-wrapper" class=" grey lighten-3">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Empleado</h5>
                <ol class="breadcrumb">
                    <li>
                        <a href="/index/">Inicio</a>
                    </li>   
                </ol>
            </div>
        </div>
    </div>
</div>



<div class="row">
    <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
    <div class="col s6">
        <h4 >Vista de Empleado : </h4>
    </div>
</div>
<div class="row">
    <div class="input-field col s6">
        <a class="btn" href="/AddEmpleado/">+</a>
        <input placeholder="Buscar Empleado" id="buscar" type="text">   </input>       
        <a class="waves-effect waves-light btn" onclick="Buscadordeproducto()">Buscar</a>
    </div>
</div>

<table class="centered" id="tablacategoria">
    <thead>
    <th>Nombre</th>
    <th>Genero</th>
    <th>Doc. Identidad</th>
    <th>Cargo</th>
    <th>Operacion</th>
</thead>
<tbody id="datos">
</tbody>
</table>
@stop
@section('scripts')
{!! Html::script('js/addempleado.js') !!}
@endsection