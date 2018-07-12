@extends('Layouts.PanelVentas')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Listado de Ventas OPTICA</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li class="active">Listado de Ventas OPTICA</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
<input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
<div class="container">
    <div class="row">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <div class="col s12">
            <h3><strong>Listado de ventas</strong></h3>
            <h6>Las ventas a mostrar son de la fecha:</h6>
            <div class="input-field col s12 m4 l2">
                <i class="mdi-action-today prefix"></i>
                <input type="date" value="" name="" id="fecha" class="datepicker" style="text-align: center !important;">
            </div>
        </div>
    </div>
    <div class="row" style="padding-bottom: 20px;">
        <div class="col s12">
            <a class="btn"  href="#" title="Nueva Venta" id="nuevaventas">NUEVA VENTA</a>
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
                <th>Fecha</th>
                <th>Hora</th>
                <th id = "etiqueta1" >Empleado</th>
                <th>Cliente</th>
                <th>Facturado a</th>
                <th>Estado</th>
                <th>Total</th>
                <th>Importe Descuento</th>
                <th>Total Neto</th>
                <th>Forma de Cobro</th>
                <th>Saldo</th>
                <th>Editar</th>
                <th>Eliminar</th>
                <th>Proforma</th>
                <th>Nota Venta</th>
                <tfoot style=" display: table-header-group; background: white;">
                <th>ID Venta</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th id = "etiqueta2">Empleado</th>
                <th>Cliente</th>
                <th>Facturado a</th>
                <th>Estado</th>
                <th>Total</th>
                <th>Importe Descuento</th>
                <th>Total Neto</th>
                <th>Forma de Cobro</th>
                <th>Saldo</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                </tfoot>
                </thead>
                <tbody id="datos">
                </tbody>
            </table>  
        </div>
    </div>
</div>   
@stop
@section('scripts')
{!! Html::script('js/addlistaventasopt.js') !!}
@endsection
