@extends('Layouts.Panel')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Listado de Facturas</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li class="active">Listado de Facturas</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
@include('Modal.modalanular')
<div class="row">
    <h3><strong>Listado de Facturas</strong></h3>
    <h6>Las Facturas a mostrar son de la fecha:</h6>
    <div class="input-field col s12 m4 l2">
        <i class="mdi-action-today prefix"></i>
        <input type="date" value="" name="" id="fecha" class="datepicker" style="text-align: center !important;">
    </div>
</div>
<div class="row" id="cargando">
    <div class="col s12">
        <div class="progress">
            <div class="indeterminate"></div>
        </div>
        <div class="progress">
            <div class="indeterminate"></div>
        </div>
        <div class="progress">
            <div class="indeterminate"></div>
        </div>
    </div>
</div>
<div class="row" id="listado" style="display: none;">
    <div class="col s12">
        <table id="tablacategoria" class="centered display compact nowrap" cellspacing="0" width="100%">
            <thead>
            <th>ID Venta</th>
            <th>Empleado</th>
            <th>Fecha</th>
            <th>Nro. Factura</th>
            <th>RazonSocial</th>
            <th>NIT</th>
            <th>Total</th>
            <th>Editar</th>
            <th>Eliminar</th>
            <th>Imprimir</th>
            <th>Comanda</th>
            </thead>
            <tfoot style=" display: table-header-group; background: white;">
            <th>ID Venta</th>
            <th>Empleado</th>
            <th>Fecha</th>
            <th>Nro. Factura</th>
            <th>RazonSocial</th>
            <th>NIT</th>
            <th>Total</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            </tfoot>
            <tbody id="datos">
            </tbody>
        </table>
    </div>
</div>
@stop
@section('scripts')
{!! Html::script('js/addfacturaanulada.js') !!}
@endsection 