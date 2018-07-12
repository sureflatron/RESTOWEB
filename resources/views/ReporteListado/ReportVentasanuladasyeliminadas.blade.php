@extends('Layouts.Panel')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Reporte de Ventas Anuladas y Eliminadas</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li class="active">Reporte de Ventas Anuladas y Eliminadas</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
<h4><strong>Reporte de Ventas Anuladas y Eliminadas</strong></h4>
<div class="row">
    <div class="col s12 m5 l3">
        <input name="group1" type="radio" id="test1" checked />
        <label for="test1">Exportar a PDF</label>
        <input name="group1" type="radio" id="test2" />
        <label for="test2">Exportar a Excel</label>
    </div>
</div>
<div class="row center">
    <div class="col s12">
        <input name="credito" type="radio" id="cred1" checked />
        <label for="cred1">Reporte de Ventas Anuladas</label>
        <input name="credito" type="radio" id="cred2" />
        <label for="cred2">Reporte de Ventas Eliminadas</label>
    </div>
</div>
<div class="row">
    <div class="col s12">
        {!!Form::label('FEchanicio','Fecha inicio: ')!!}
        <input type="date" name="" id="fechainicio1" class="datepicker">
    </div>
</div>
<div class="row">
    <div class="col s12">
        {!!Form::label('Fechafin','Fecha Fin: ')!!}
        <input type="date" name="" id="Fechafin1" class="datepicker">
    </div>
</div>

<div class="row">
    <div class="col s12">
        <h6><strong>Lista de Empleados:</strong></h6>
    </div>
    <div class="col s12">
        <select id="empleado"> 
        </select>
    </div>
</div>
<div class="row">
    <div class="col s12">
        <h6><strong>Sucursal:</strong></h6>
    </div>
    <div class="col s12">
        <select id="almacen"><option value="null">Selecione un Almacen</option></select>
    </div>
</div>

<div class="row">
    <div class="col s12">
        <h6><strong>Almacen:</strong></h6>
    </div>
    <div class="col s12">
        <select id="almacenV"><option value="null">Selecione un Almacen</option></select>
    </div>
</div>


<br>
<div class="row">
    <div class="col s12 m5 l3">
        {!!link_to('#', $title='Generar', $attributes = ['id'=>'ventaAnuladaELiminada', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
</div>
@stop
@section('scripts')
{!! Html::script('js/addreporte.js') !!}
@endsection   
