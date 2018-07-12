@extends('Layouts.Panel')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Gestionar Libro de Orden</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li><a href="/Gestionarlibroorden">Listado Libros de Orden</a></li>
                    <li class="active">Gestionar Libro de Orden</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
<h3><strong>Nuevo Libro de Orden</strong></h3>
<input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
<div class="row">
    <div class="col s12 m6 l6">
        <div class="row">
            <div class="input-field col s1">
                <i class="mdi-social-domain prefix"></i>
            </div>
            <div class="col s11">
                {!!Form::label('Sucursal','Sucursal: *')!!}
                {!! Form::select('idSucursal', $sucursal,null, ['id'=>'idSucursal']) !!}
            </div>
        </div>
    </div>
    <div class="input-field col s12 m6 l6">
        <i class="mdi-social-person prefix"></i>
        {!!Form::label('NIT','NIT: *')!!}
        {!!Form::text('NIT',null, ['id'=>'NIT','onkeypress'=>'return isNumberKey(this);'])!!}
    </div>
</div>
<div class="row">
    <div class="input-field col s12 m6 l6">
        <i class="mdi-content-content-paste prefix"></i>
        {!!Form::label('nroAutorizacion','Nro Autorizacion: ')!!}
        {!!Form::text('nroAutorizacion',null, ['id'=>'nroAutorizacion','onkeypress'=>'return isNumberKey(this);'])!!}
    </div>
    <div class="input-field col s12 m6 l6">
        <i class="mdi-editor-format-list-numbered prefix"></i>
        {!!Form::label('nroInicio','Nro  Inicio: *')!!}
        {!!Form::text('nroInicio',null, ['id'=>'nroInicio','onkeypress'=>'return isNumberKey(this);'])!!}
    </div>
</div>
<div class="row">
    <div class="input-field col s12 m6 l6">
        <i class="mdi-editor-format-list-numbered prefix"></i>
        {!!Form::label('nroFin','Nro  Fin: *')!!}
        {!!Form::text('nroFin',null, ['id'=>'nroFin','onkeypress'=>'return isNumberKey(this);'])!!}
    </div>
    <div class="input-field col s12 m6 l6">
        <i class="mdi-editor-merge-type prefix"></i>
        {!!Form::label('tipo','Tipo: ')!!}
        {!!Form::text('tipo',null, ['id'=>'tipo'])!!}
    </div>
</div>
<div class="row">
    <div class="input-field col s12 m6 l6">
        <i class="mdi-action-today prefix"></i>
        {!!Form::label('fechaInicio','Fecha Inicio: *')!!}
        {!!Form::date('fechaInicio',null, ['id'=>'fechaInicio','class'=>'datepicker'])!!}
    </div>
    <div class="input-field col s12 m6 l6">
        <i class="mdi-action-today prefix"></i>
        {!!Form::label('fechaFin','Fecha  Fin: *')!!}
        {!!Form::date('fechaFin',null, ['id'=>'fechaFin','class'=>'datepicker'])!!}
    </div>
</div>
<div class="row">
    <div class="input-field col s12 m6 l6">
        <i class="mdi-communication-vpn-key prefix"></i>
        {!!Form::label('llave','Llave: *')!!}
        {!!Form::text('llave',null, ['id'=>'llave'])!!}
    </div>
    <div class="col s12 m6 l6">
        <div class="row">
            <div class="input-field col s1">
                <i class="mdi-content-content-paste prefix"></i>
            </div>
            <div class="col s11">
                <h6>Habilitado</h6>
                <input name="group1" type="radio" id="test1" checked/>
                <label for="test1">Si</label>
                <input name="group1" type="radio" id="test2" />
                <label for="test2">No</label>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col s12 m6 l5">
        <div class="input-field"  >
            <a class="btn" href="/Gestionarlibroorden">VOLVER</a>
            <a class="btn" id="guardar">GUARDAR</a>
        </div>
    </div>
</div>
@stop
@section('scripts')
{!! Html::script('js/addlibroorden.js') !!}
@endsection