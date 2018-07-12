@extends('Layouts.Panel')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Reporte de Flujos de Ingresos y Egresos</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li class="active">Reporte de Flujos de Ingresos y Egresos</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
<div class="row">
    <div class="col s12">
        <h4><strong>Reporte de Flujos de Ingresos y Egresos</strong></h4>
    </div>
</div>
<div class="row">
    <div class="col s12">
        <p>
            <input name="group1" type="radio" id="test1" checked />
            <label for="test1">EXPORTAR A PDF</label>
        </p>
        <p>
            <input name="group1" type="radio" id="test2" />
            <label for="test2">EXPORTAR A EXCEL</label>
        </p>
    </div>
</div>
<div class="row">
    <div class="input-field col s12">
        <i class="prefix mdi-action-event"></i>
        <label for="fechainicio">Fecha de Inicio:</label>
        <input type="date" name="" id="fechainicio" class="datepicker">
    </div>
</div>
<div class="row">
    <div class="input-field col s12">
        <i class="prefix mdi-action-event"></i>
        <label for="fechainicio">Fecha Fin:</label>
        <input type="date" name="" id="Fechafin" class="datepicker">
    </div>
</div>
<div class="row">
    <div class="col s12">
        <label for="almacen">Lista de Sucursales:</label>
        <select id="almacen" class="browser-default">
            <option value="null">Selecione una Sucursal</option>
        </select>
    </div>
</div>
<br>
<div class="row">
    <div class="col s12">
        <a class="btn waves-effect btnPrint" id="reporteflujoingresosegresos">generar</a>
    </div>
</div>

@stop
@section('scripts')
{!! Html::script('js/addreporte.js') !!}
@endsection 