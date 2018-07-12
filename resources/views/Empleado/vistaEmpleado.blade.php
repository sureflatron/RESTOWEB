@extends('Layouts.Panel')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Lista de Empleados</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li class="active">Lista de Empleados</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
@include('Modal.modalEmpleado')
<div class="row">
    <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
    <div class="col s6">
        <h3 ><strong>Listado  de Empleados</strong></h3>
    </div>
</div>

<div class="row" style="padding-bottom: 20px;">
    <div class="col s6">
        <a class="btn" href="#" onclick="nuevoempleado()">  NUEVO EMPLEADO</a>
    </div>
</div>

<table  id="tablacategoria" class="centered display compact nowrap" cellspacing="0" width="100%">
    <thead>
    <th>Nombre</th>
    <th>Genero</th>
    <th>Doc. Identidad</th>
    <th>Cargo</th>
    <th>Comision</th>
    <th>Editar</th>
    <th>Eliminar</th>
    <th>Usuario</th>
</thead>
<tfoot  style=" display: table-header-group; background: white;">
<th>Nombre</th>
<th>Genero</th>
<th>Doc. Identidad</th>
<th>Cargo</th>
<th>comision</th>
<th></th>
<th></th>
<th></th>
</tfoot>
<tbody id="datos">
</tbody>
</table>  

@stop

@section('scripts')
{!! Html::script('js/addempleado.js') !!}
@endsection