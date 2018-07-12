@extends('Layouts.Panel')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Gestionar Ingreso</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li><a href="/GestionarIngreso">Listado de Ingresos</a></li>
                    <li class="active">Gestionar Ingreso</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
<input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
<div class="col s6">
    <h3><strong>Formulario de  Ingreso</strong></h3>
</div>
<div class="row">
    <div class="col s12 m12 l6 input-field">
        <i class="prefix mdi-editor-insert-invitation"></i>
        {!!Form::label('Fecha','Fecha: *')!!}
        <input type="date" value="{!!$fecha!!}" name="" id="fecha" class="datepicker" placeholder="">
    </div>
    <div class="col s12 m12 l6">
        {!!Form::label('idTipoIngreso','Tipo Ingreso: *')!!}
        {!! Form::select('idTipoIngreso', $sucursal,null, ['id'=>'idTipoIngresos']) !!}
    </div>
</div>
<div></div>
<div class="row">
    <div class="col s12 m12 l6 input-field">
        <i class="prefix mdi-action-account-box"></i>
        {!!Form::label('recibidoDe','Recibido De: *')!!}
        {!!Form::text('recibidoDe',null, ['id'=>'recibidoDes'])!!}
    </div>
    <div class="col s12 m12 l6 input-field">
        <i class="prefix">Bs.</i>
        {!!Form::label('importe','Importe: *')!!}
        {!!Form::number('importe',null, ['id'=>'importes'])!!}
    </div>
</div>
<div class="row">
    <div class="col s12">
        {!!Form::label('importe','Forma de Pago: *')!!}
        {!! Form::select('concepto', $concepto,null, ['id'=>'concepto'])!!}
    </div>
    <div class="col s12" id="cuentabanco" style="display: none">
        {!!Form::label('glosa','Numero de Cuenta: ')!!}
        <select id="cuenta">
        </select>
    </div>
    <div class="col s12 input-field" id="cuentacheque" style="display: none">
        <i class="prefix mdi-image-blur-linear"></i>
        {!!Form::label('glosa','Numero de Cuenta: ')!!}
        {!!Form::number('importe',null, ['id'=>'cheque'])!!}
    </div>
</div>
<div class="row">
    <div class="col s12 input-field">
        <i class="mdi-editor-border-color prefix"></i>
        {!!Form::label('glosa','Glosa: ')!!}
        {!!Form::textarea('glosa',null, ['id'=>'glosas','class'=>'materialize-textarea'])!!}
    </div>
</div>
<div class="row">
    <div class="col s12 m6 l4">
        <a href="#" class="btn" id=guardar>GUARDAR E IMPRIMIR</a>
    </div>
    <div class="col s12 m6 l3">
        <a href="/GestionarIngreso" class="btn">VOLVER</a>  
    </div>
</div>
@stop
@section('scripts')
{!! Html::script('js/addlistaringreso.js') !!}
@endsection
