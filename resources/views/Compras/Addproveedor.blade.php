@extends('Layouts.Panel')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Agregar Proveedor</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li><a href="/GestionarProveedor">Gestionar Proveedores</a></li>
                    <li class="active">Agregar Proveedor</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
<input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
<h3><strong>Nuevo proveedor</strong></h3>
<div class="row">
    <div class="input-field col s12 m6 l6">
        <i class="mdi-action-account-box prefix"></i>
        {!!Form::label('Nombre','Nombre: *')!!}
        {!!Form::text('nombre',null, ['id'=>'nombre'])!!}
    </div>
    <!--</div>
    <div class="row">-->
    <div class="col s12 m6 l6">
        <label for="idCiudad" class="active">Ciudad: *</label>
        {!! Form::select('idCiudad', $sucursal,null, ['id'=>'idCiudad']) !!}
    </div>
</div>
<div class="row">
    <div class="input-field col s12 m6 l6">
        <i class="mdi-action-turned-in-not prefix"></i>
        {!!Form::label('direccion','Direccion: *')!!}
        {!!Form::text('direccion',null, ['id'=>'direccion'])!!}
    </div>
    <!--</div>
    <div class="row">-->
    <div class="input-field col s12 m6 l6">
        <i class="mdi-communication-call prefix"></i>
        {!!Form::label('telefono','Telefono: ')!!}
        {!!Form::text('telefono',null, ['id'=>'telefono','onkeypress'=>'return isNumberKey(this);'])!!}
    </div>
</div>
<div class="row">
    <div class="input-field col s12 m6 l6">
        <i class="mdi-communication-comment prefix"></i>
        {!!Form::label('paginaWeb','Pagina Web: ')!!}
        {!!Form::text('paginaWeb',null, ['id'=>'paginaWeb'])!!}
    </div>
    <!--</div>
    <div class="row">-->
    <div class="input-field col s12 m6 l6">
        <i class="mdi-communication-contacts prefix"></i>
        {!!Form::label('contactoRef','Contacto Ref: *')!!}
        {!!Form::text('contactoRef',null, ['id'=>'contactoRef'])!!}
    </div>
</div>
<div class="row">
    <div class="input-field col s12 m6 l6">
        <i class="mdi-communication-quick-contacts-dialer prefix"></i>
        {!!Form::label('telefonoContacto','Telefono Contacto: *')!!}
        {!!Form::text('telefonoContacto',null, ['id'=>'telefonoContacto','onkeypress'=>'return isNumberKey(this);'])!!}
    </div>
    <!--</div>
    <div class="row">-->
    <div class="input-field col s12 m6 l6">
        <i class="mdi-communication-quick-contacts-mail prefix"></i>
        {!!Form::label('correoContato','Correo Contato: ')!!}
        {!!Form::email('correoContato',null, ['id'=>'correoContato'])!!}
    </div>
</div>
<div class="row">
    <div class="col s12 m6 l5">
        {!!link_to('#', $title='Guadar', $attributes = ['id'=>'guardar', 'class'=>'btn btn-primary'], $secure = null)!!}
        {!!link_to('/GestionarProveedor', $title='Volver', $attributes = [  'class'=>'btn btn-primary'], $secure = null)!!} 
    </div>
</div>
@stop
@section('scripts')
{!! Html::script('js/addproveedor.js') !!}  
@endsection