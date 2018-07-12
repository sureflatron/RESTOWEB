@extends('Layouts.Panel')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Gestionar Egreso</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li><a href="/GestionarEgreso">Listado de Egresos</a></li>
                    <li class="active">Gestionar Egreso</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
<input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
<div class="col s12 m8 l6">
    <h3><strong>Formulario de  Egreso</strong></h3>
</div>
<div class="row">
    <div class="col s12 m12 l6 input-field">
        <i class="prefix mdi-editor-insert-invitation"></i>
        {!!Form::label('Fecha','Fecha: *')!!}
        <input type="date" value="{!!$fecha!!}" name="" id="fecha" class="datepicker" placeholder="">
    </div>
    <div class="col s12 m12 l6">
        {!!Form::label('Egreso','Tipo Egreso: *')!!}
        {!! Form::select('Egreso', $sucursal,null, ['id'=>'idTipoEgresos']) !!}
    </div>
</div>
<div class="row">
    <div class="col s12 m12 l6">
        {!!Form::label('Proveedor','Proveedor: *')!!}
        {!! Form::select('Proveedor', $proveedor,null, ['id'=>'idProveedors']) !!}
    </div>
    <div class="col s12 m12 l6 input-field">
        <i class="prefix mdi-action-account-box"></i>
        {!!Form::label('pagadoA','Pagado A: *')!!}
        {!!Form::text('pagadoA',null, ['id'=>'pagadoAs'])!!}
    </div>
</div>
<div class="row">
    <div class="col s12 m12 l6 input-field">
        <i class="prefix">Bs.</i>
        {!!Form::label('importe','Importe: *')!!}
        {!!Form::number('importe',null, ['id'=>'importes'])!!}
    </div>
    <div class="col s12 m12 l6">
        {!!Form::label('importe','Forma de Pago: *')!!}
        {!! Form::select('concepto', $concepto,null, ['id'=>'concepto'])!!}
    </div>
</div>
<div class="row" id="cuentabanco" style="display: none">
    <div class="col s12">
        {!!Form::label('glosa','Numero de Cuenta: ')!!}
        <select id="cuenta">
        </select>
    </div>
</div>
<div class="row" id="cuentacheque" style="display: none">
    <div class="col s12 input-field">
        <i class="prefix mdi-image-blur-linear"></i>
        {!!Form::label('glosa','Numero de Cuenta: ')!!}
        {!!Form::number('importe',null, ['id'=>'cheque'])!!}
    </div>
</div>
<div class="row">
    <div class="col s12 m12 l12 input-field">
        <i class="mdi-editor-border-color prefix"></i>
        {!!Form::label('glosa','Glosa: ')!!}
        {!!Form::textarea('glosa',null, ['id'=>'glosas','class'=>'materialize-textarea'])!!}
    </div>
</div>
<div class="row">
    <div class="col s12 m6 l3">
        <a href="#" class="btn" id=guardar>GUARDAR E IMPRIMIR</a>
    </div>
    <div class="col d12 m6 l2">
        <a href="/GestionarEgreso" class="btn">Volver</a>   
    </div>
</div>
@stop
@section('scripts')
{!! Html::script('js/addlistaregreso.js') !!}
@endsection