@extends('Layouts.Panel')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Reporte de Garantias por Alquiler Por Usuario</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li class="active">Reporte de Garantias por Alquiler Por Usuario</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
<h4><strong>Reporte de Garantias por Alquiler Por Usuario</strong></h4>
<div class="row">
    <div class="col s12 m6 l6">
        <p>
            <input name="group1" type="radio" id="test1" checked />
            <label for="test1">Exportar a PDF</label>
        </p>
        <p>
            <input name="group1" type="radio" id="test2" />
            <label for="test2">Exportar a Excel</label>
        </p>
    </div>
    <div class="col s12 m6 l6">
        <p>
            <input name="group2" type="radio" id="test3" checked />
            <label for="test3">Productos Devueltos</label>
        </p>
        <p>
            <input name="group2" type="radio" id="test4" />
            <label for="test4">Productos Pendientes</label>
        </p>
    </div>
</div>
<div class="row">
    <div class="input-field col s12 m6 l6">
        <i class="prefix mdi-action-event"></i>
        <label for="fechainicioss">Fecha de Inicio: *</label>
        <input type="date" name="" id="fechainicioss" class="datepicker" placeholder="">
    </div>
    <div class="input-field col s12 m6 l6">
        <i class="prefix mdi-action-event"></i>
        <label for="Fechafinss">Fecha Fin: *</label>
        <input type="date" name="" id="Fechafinss" class="datepicker" placeholder="">
    </div>
</div>
<div class="row">
    <div class="col s12">
        <label for="empleado">Lista de Empleados:</label>
        <select   id="empleado" class="browser-default"> 
        </select>
    </div>
</div>
<div class="row" id="sucursaleslista">
    <div class="col s12">
        <label for="almacen">Lista de Sucursales:</label>
        <select id="almacen" class="browser-default"><option value="null">Selecione un Almacen</option></select>
    </div>
</div>

<div class="row">
    <div class="col s12">
        <label for="almacenV">Lista de ALmacenes:</label>
        <select id="almacenV" class="browser-default"><option value="null">Selecione un Almacen</option></select>
    </div>
</div>
<br>
<div class="row">
    <div class="col s12 m5 l3">
        {!!link_to('#', $title='Generar', $attributes = ['id'=>'vetasalquilerporusuario', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
</div>
@stop
@section('scripts')
{!! Html::script('js/addreporte.js') !!}
@endsection   

