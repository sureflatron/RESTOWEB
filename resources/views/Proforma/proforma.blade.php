@extends('Layouts.PanelVentas')
@section('Contenido')
<div id="breadcrumbs-wrapper" class=" grey lighten-3">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Vista de ventas </h5>
                <a href="/index/" class="breadcrumb">Inicio</a>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
<div class="container">
    <div class="row">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <div class="col s12">
            <h3><strong>Listado de Proformas</strong></h3>
        </div>
    </div>
    <div class="row">
        <div class="col s12">
            <table id="tablacategoria" class="centered display compact nowrap" cellspacing="0" width="100%">
                <thead>
                <th>Codigo</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Cliente</th>
                <th>Total</th>
                <th>Operacion</th>
                </thead>
                <tfoot style=" display: table-header-group; background: white;">
                <th>Codigo</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Cliente</th>
                <th>Total</th>
                <th>Operacion</th>
                </tfoot>
                <tbody id="datos">
                </tbody>
            </table>
        </div>
    </div>
</div>   
@stop
@section('scripts')
{!! Html::script('js/addproforma.js') !!}
@endsection