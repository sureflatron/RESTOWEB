@extends('Layouts.Panel')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Reporte de Egresos</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li class="active">Reporte de Egresos</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
<h4><strong>Reporte de Egreso por Usuario</strong></h4>
<div class="row">
    <div class="col s12">
        <p>
            <input name="group1" type="radio" id="test1" checked />
            <label for="test1">Exportar a PDF</label>
        </p>
        <p>
            <input name="group1" type="radio" id="test2" />
            <label for="test2">Exportar a Excel</label>
        </p>
    </div>
</div>
<div class="row">
    <div class="input-field col s12">
        <i class="mdi-editor-insert-invitation prefix"></i>
        {!!Form::label('FEchanicio','Fecha inicio: ')!!}
        <input type="date" name="" id="fechainicioegreso" class="datepicker" placeholder="">
    </div>
</div>

<div class="row">
    <div class="input-field col s12">
        <i class="mdi-editor-insert-invitation prefix"></i>
        {!!Form::label('Fechafin','Fecha Fin: ')!!}
        <input type="date" name="" id="Fechafinegreso" class="datepicker" placeholder="">
    </div>
</div>

<div class="row">
    <div class="col s12">
        <label for="empleado">Lista de Empleados:</label>
        <select   id="empleado" class="browser-default"> 
        </select>
    </div>
</div>

<div class="row">
    <div class="col s12">
        <label for="egresos">Lista de Tipos de Egresos:</label>
        <select id="egresos" class="browser-default"> 
        </select>
    </div>
</div>

<br>
<div class="row">
    <div class="col s12 m5 l3">
        {!!link_to('#', $title='Generar', $attributes = ['id'=>'egresoporusuario', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
</div>

@stop
@section('scripts')
{!! Html::script('js/addreporte.js') !!}
@endsection   

