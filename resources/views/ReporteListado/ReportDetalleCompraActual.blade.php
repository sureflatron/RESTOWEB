@extends('Layouts.Panel')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Reporte de Compras Detalladas del Usuario Actual</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li class="active">Reporte de Compras Detalladas del Usuario Actual</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
<h4><strong>Reporte de Compras Detalladas del Usuario Actual</strong></h4>
<div class="divider"></div>
<div class="row">
    <div class="col s12">
        <h6><strong>Exportar Archivo a:</strong></h6>
    </div>
    <div class="col s12">
        <input name="group1" type="radio" id="test1" checked />
        <label for="test1"><strong>PDF</strong></label>
    </div>
    <div class="col s12">
        <input name="group1" type="radio" id="test2" />
        <label for="test2"><strong>Excel</strong></label>
    </div>
</div>
<div class="row">
    <div class="col s12">
        <h6><strong>Fecha Inicio:</strong></h6>
    </div>
    <div class="col s12">
        <input type="date" name="" id="fechainicios" class="datepicker">
    </div>
</div>
<div class="row">
    <div class="col s12">
        <h6><strong>Fecha Fin:</strong></h6>
    </div>
    <div class="col s12">
        <input type="date" name="" id="Fechafins" class="datepicker">
    </div>
</div>
<div class="row">
    <div class="col s12">
        <h6><strong>Almacen:</strong></h6>
    </div>
    <div class="col s12">
        <select id="almacen1"><option value="null">Selecione un Almacen</option></select>
    </div>
</div>
<div class="row">
    <div class="col s12">
        {!!link_to('#', $title='Generar', $attributes = ['id'=>'reportecompraactual', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
    <br>
    <br>
    <br>
    <br>
    <br>
</div>

@stop
@section('scripts')
{!! Html::script('js/addreporte.js') !!}
@endsection   