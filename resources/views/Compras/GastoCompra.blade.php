@extends('Layouts.Panel')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Listado de Tipos de Gastos de Compras</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li class="active">Listado de Tipos de Gastos de Compras</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
@include('Modal.modalConcepto')
<h3><strong>Listado de Tipos de Gastos de Compras</strong></h3>
<div class="row" style="padding-bottom: 20px;">
    <div class="col s12">
        <a class="waves-effect waves-light btn modal-trigger" href="#modal1">Nuevo Tipo de Gasto</a>
    </div>
</div>
<table id="tablacategoria" class="centered display compact nowrap" cellspacing="0" width="100%">
    <thead>
    <th>Nombre</th>
    <th>Editar</th>
    <th>Eliminar</th>
</thead>
<tfoot style=" display: table-header-group; background: white;">
    <th>Nombre</th>
    <th></th>
    <th></th>
</tfoot>
<tbody id="datos">
</tbody>
</table>

@stop
@section('scripts')
{!! Html::script('js/addTipoGastoCompra.js') !!}   
@endsection