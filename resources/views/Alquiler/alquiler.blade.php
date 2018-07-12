@extends('Layouts.PanelVentas')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Lista de Alquiler</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li class="active">Lista de Alquiler</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
@include('Modal.modalDevolucionAlquiler')
<input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
<div class="container">
    <div class="row">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <div class="col s12">
            <h3><strong>Listado de Alquileres</strong></h3>
        </div>
    </div>
    <div class="row" style="padding-bottom: 20px;">
        <div class="col s12">
            <a class="btn waves-green"  href="#" title="Nuevo Alquiler" id="nuevoaluiler">NUEVO ALQUILER</a>
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
                <th>ID</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Empleado</th>
                <th>Cliente</th>
                <th>Facturado a</th>
                <th>Estado</th>
<!--                <th>Total</th>
                <th>Importe Descuento</th>-->
                <th>Total Neto</th>
                <th>Forma de Cobro</th>
                <th>Garantia</th>
                <th>Fecha de Devolucion</th>
                <th>Editar</th>
                <th>Eliminar</th>
                <th>Nota de venta</th>
                <th>Devolucion</th>
                </thead>
                <tfoot style=" display: table-header-group; background: white;">
                <th>ID</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Empleado</th>
                <th>Cliente</th>
                <th>Facturado a</th>
                <th>Estado</th>
<!--                <th>Total</th>
                <th>Importe Descuento</th>-->
                <th>Total Neto</th>
                <th>Forma de Cobro</th>
                <th>Garantia</th>
                <th>Fecha de Devolucion</th>
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
</div>   
@stop
@section('scripts')
{!! Html::script('js/addlistaalquiler.js') !!}
@endsection