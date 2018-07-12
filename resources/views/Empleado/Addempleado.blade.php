@extends('Layouts.Panel')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Agregar Empleado</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li><a href="/Empleados">Gestionar Empleados</a></li>
                    <li class="active">Formulatio Empleado</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
<input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
<div class="col s6">
    <h3><strong>Formulario de  Empleado</strong></h3>
    <div class="divider"></div>
</div>
<div class="row">
    <form class="col s12">
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <i class="mdi-action-perm-identity prefix"></i>
                <input id="nombre" type="text"  length="100">
                <label for="nombre">Nombre: *</label>
            </div>
            <div class="input-field col s12 m6 l6">
                <div class="row">
                    <div class="input-field col s1">
                        <i class="mdi-action-accessibility prefix"></i>
                    </div>
                    <div class="col s11">
                        <label for="genero" class="active">Genero:</label>
                        <select id="genero">
                            <option value="Masculino">Masculino</option>
                            <option value="Femenino">Femenino</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <i class="mdi-action-event prefix"></i>
                <label for="fechanacimiento">Fecha de nacimiento: *</label> 
                <input type="date"  id="fechanacimiento" class="datepicker">
            </div>
            <div class="input-field col s12 m6 l6">
                <i class="mdi-action-picture-in-picture prefix"></i>
                <input type="text"  id="Docidentidad" length="20">
                <label for="Docidentidad">Documento de identidad: *</label>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <i class="mdi-communication-call prefix"></i>
                <input type="text"  id="telefonofijo" length="20"> 
                <label for="telefonofijo">Telefono Fijo: </label>
            </div>
            <div class="input-field col s12 m6 l6">
                <i class="mdi-communication-stay-primary-portrait prefix"></i>
                <input type="text"  id="celular" length="20">
                <label  for="celular">Celular: *</label>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <i class="mdi-communication-email prefix"></i>
                <input type="email"  id="correo">
                <label  for="email">Correo electronico: *</label>
            </div>
            <div class="input-field col s12 m6 l6">
                <div class="row">
                    <div class="input-field col s1">
                        <i class="mdi-file-folder-shared prefix"></i>
                    </div>
                    <div class="col s11">
                        <select id="Cargo">
                        </select>
                        <label for="Cargo">Cargo: *</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s1">
                <i class="mdi-editor-format-list-numbered prefix"></i>
            </div>
            <div class="col s11">
                {!!Form::label('Descuento','Porcentaje de Comision: *')!!}
                <div id="descuento"></div>
                <!--<input type="range" id="descuento"/>-->
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12">
                <div class="row">
                    <div class="input-field col s1">
                        <i class="mdi-action-history prefix"></i>
                    </div>
                    <div class="col s11">
                        {!!Form::label('turno','Turno: *')!!}
                        <select id="turno">
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="row">
    <div class="input-field col s12" id="Guardaryvolveresconder" >
        <a class="btn" id="guardar">Guardar y volver </a>
        <a class="btn" href="/Empleados"> volver </a>
    </div>
</div>
<div class="row" id="ingredienteycomposicionmostrar" style='display:none;'>
</div>

@stop
@section('scripts')
{!! Html::script('js/addempleado.js') !!}
@endsection
