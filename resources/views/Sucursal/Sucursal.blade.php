@extends('Layouts.Panel')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Listado de sucursales</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li class="active">Listado de Sucursales</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
@include('Modal.modalsucursal')
<div class="container">
    <div class="row">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <div class="col s6">
            <h3><strong>Listado de Sucursales</strong></h3>
        </div>
    </div>
    <div class="row" style="padding-bottom: 20px;">
        <div class="col s6">         
            <a class="waves-effect waves-light btn modal-trigger" href="#modal1">NUEVA SUCURSAL</a>
        </div>
    </div>
</div>
<table  id="tablacategoria" class="centered display compact nowrap" cellspacing="0" width="100%">
    <thead>
    <th>Nombre</th>
    <th>Dirreccion</th>
    <th>Telefono</th>
    <th>Ciudad</th>
    <th>Editar</th>
    <th>Eliminar</th>
</thead>
<tfoot  style=" display: table-header-group; background: white;">
<th>Nombre</th>
<th>Dirreccion</th>
<th>Telefono</th>
<th>Ciudad</th>
<th></th>
<th></th>
</tfoot>
<tbody id="datos">
</tbody>
</table>

@stop

@section('scripts')
{!! Html::script('js/addsucursal.js') !!}
@endsection
