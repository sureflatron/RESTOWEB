@extends('Layouts.Panel')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Listado Libros de Orden</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li class="active">Listado Libros de Orden</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
@include('Modal.modallibroorden')
<h3><strong>Listado de Libros de Orden</strong></h3>
<div class="row" style="padding-bottom: 20px;">
    <div class="col s12">
        <a class="btn" href="#" id="nuevolibroorden">NUEVO LIBRO DE ORDEN</a>
    </div>
</div>
<table id="tablacategoria" class="centered display compact nowrap" cellspacing="0" width="100%">
    <thead>
    <th>Sucursal</th>
    <th>NIT</th>
    <th>Nro. Autorizacion</th>
    <th>Nro. Inicio</th>
    <th>Nro. Fin</th>
    <th>Estado</th>
    <th>Tipo</th>
    <th>Fecha Inicio</th>
    <th>Fecha Fin</th>
    <th>Editar</th>
    <th>Eliminar</th>
</thead>
<tfoot style=" display: table-header-group; background: white;">
<th>Sucursal</th>
<th>NIT</th>
<th>Nro. Autorizacion</th>
<th>Nro. Inicio</th>
<th>Nro. Fin</th>
<th>Estado</th>
<th>Tipo</th>
<th>Fecha Inicio</th>
<th>Fecha Fin</th>
<th></th>
<th></th>
</tfoot>
<tbody id="datos">
</tbody>
</table>
@stop
@section('scripts')
{!! Html::script('js/addlibroorden.js') !!}
@endsection