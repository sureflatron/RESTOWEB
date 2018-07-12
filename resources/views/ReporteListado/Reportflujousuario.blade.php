@extends('Layouts.Panel')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Reporte de Flujo de Caja del Usuario Actual</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li class="active">Reporte de Flujo de Caja Del Usuario Actual</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
<h4><strong>Flujo de caja del Usuario Actual</strong></h4>
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
        {!!Form::label('FEchanicio','Fecha inicio: ')!!}
        <input type="date" name="" id="fechainiciof" class="datepicker">
    </div>
</div>
<div class="row">
    <div class="col s12">
        {!!Form::label('Fechafin','Fecha Fin: ')!!}
        <input type="date" name="" id="Fechafinf" class="datepicker">
    </div>
</div>
<div class="row">
    <div class="col s12">
        {!!link_to('#', $title='Generar', $attributes = ['id'=>'flujoporusuaeio', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
</div>
@stop
@section('scripts')
{!! Html::script('js/addreporte.js') !!}
@endsection   


