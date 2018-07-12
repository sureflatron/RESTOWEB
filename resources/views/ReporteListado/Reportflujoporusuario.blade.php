@extends('Layouts.Panel')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Reporte de Flujo de Caja Por Usuario</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li class="active">Reporte de Flujo de Caja Por Usuario</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
<h4><strong>Flujo de caja  por usuario</strong></h4>

<div class="row">
    <div class="col s12">
        <input name="group1" type="radio" id="test1" checked />
        <label for="test1">Exportar a PDF</label>
        <input name="group1" type="radio" id="test2" />
        <label for="test2">Exportar a Excel</label>
    </div>
</div>
<div class="row">
    <div class="col s12">
    </div>
</div>
<div class="row">
    <div class="col s12">
        {!!Form::label('FEchanicio','Fecha inicio: ')!!}
        <input type="date" name="" id="fechainiciofu" class="datepicker">
    </div>
</div>
<div class="row">
    <div class="col s12">
        {!!Form::label('Fechafin','Fecha Fin: ')!!}
        <input type="date" name="" id="Fechafinfu" class="datepicker">
    </div>
</div>
<div class="row">
    <div class="col s12">
        {!!Form::label('','Lista de empleado  : ')!!}
        <select   id="empleados"> 
        </select>
    </div>
</div>
<div class="row" id="sucursaleslista">
    <div class="col s12">
        <h6><strong>Sucursal</strong></h6>
    </div>
    <div class="col s12">
        <select id="almacen"><option value="null">Selecione una Sucursal</option></select>
    </div>
</div>
<div class="row">
    <div class="col s12">
        {!!link_to('#', $title='Generar', $attributes = ['id'=>'flujoporusuarios', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
</div>
@stop
@section('scripts')
{!! Html::script('js/addreporte.js') !!}
@endsection


