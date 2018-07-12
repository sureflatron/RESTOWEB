@extends('Layouts.Panel')
@section('Contenido')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Reporte de Libro de Venta</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li class="active">Reporte de Libro de Venta</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
<h4><strong>Reporte de Libro de Venta</strong></h4>
<div class="row">
    <div class="col s12">
        <h6><strong>Fecha de Inicio:*</strong></h6>
    </div>
    <div class="col s12">
        <input type="date" name="" id="fechainicio" class="datepicker">
    </div>
</div>
<div class="row">
    <div class="col s12">
        <h6><strong>Fecha Fin:*</strong></h6>
    </div>
    <div class="col s12">
        <input type="date" name="" id="Fechafin" class="datepicker">
    </div>
</div>
<div class="row">
    <div class="col s12">
        <h6><Strong>Sucursal:*</Strong></h6>
    </div>
    <div class="col s12">
        {!! Form::select('Sucursal', $sucursales ,null, ['id'=>'sucursal'])!!}
    </div>
</div>
<div class="row">
    <div class="col s12">
        {!!link_to('#', $title='Generar', $attributes = ['id'=>'generarlibro', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
</div>
@stop
@section('scripts')
{!! Html::script('js/extra/reporlibroventaexcel.js') !!}
@endsection
