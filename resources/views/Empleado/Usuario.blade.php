@extends('Layouts.Panel')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Gestionar Usuarios</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li><a href="/Empleados">Gestionar Empleados</a></li>
                    <li class="active">Gestionar Usuario</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido') 
<input type="hidden"  value="{{ $idempleado }}" id="idempleado">
<input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
<div class="row">
    <div class="col s12">
        <h4>
            <strong>Agregar Usuario a:</strong>{{ $nombreempleado }}
        </h4>
    </div>
</div>
<div class="row">
    <div class="input-field col s12">
        <img style="margin: auto;" class="circle responsive-img valign profile-image materialboxed" width="200" height="200" href="" src="../images/avatar.jpg" id="vistaPrevia2">
    </div>
    <form class="col s12">
        <div class="row">
            <div class="input-field col s12">
                <div class="file-field input-field">
                    <div class="btn">
                        <span>Foto</span>
                        <input type="file" id="seleccionarImagen2">
                    </div>
                    <div class="file-path-wrapper">
                        <input class="file-path validate" type="text" id="nombreimg2">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12">
                <i class="prefix mdi-action-account-box"></i>
                <label for="usuario">Usuario:</label>
                <input id="usuario" type="text" placeholder="">
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12">
                <i class="mdi-action-lock prefix"></i>
                <label for="password">Contraseña:</label>
                <input id="password" type="password"  name="password" placeholder="">
            </div>
            <div class=" col s12">
                <input type="checkbox" id="show"/> 
                <label for="show">Mostrar password.</label>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12 m12 l6">
                <span >Perfil :</span>
                {!! Form::select('Perfil', $perfil,null, ['id'=>'perfil']) !!}
            </div>
            <div class="input-field col s12 m12 l6">
                <span for="sucursal">Sucursal :</span>
                {!! Form::select('sucursals', $sucursal,null, ['id'=>'sucursal']) !!}
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12 m12 l6">
                <div class="row">
                    <div class="col s12 m12 l6">
                        <h6>¿Puede Vender?</h6>
                        <input name="group2" type="radio" id="test3" checked/>
                        <label for="test3">Si</label>
                        <input name="group2" type="radio" id="test4" />
                        <label for="test4">No</label>
                    </div>
                    <div class="col s12 m12 l6">
                        <h6>Ventas Multi Almacen</h6>
                        <input name="group1" type="radio" id="test1" checked/>
                        <label for="test1">Si</label>
                        <input name="group1" type="radio" id="test2" />
                        <label for="test2">No</label>
                    </div>
                </div>
            </div>
            <div class="input-field col s12 m12 l6">
                <span for="">Almacen Por Defecto: *</span>
                {!! Form::select('almacen', $almacen,null, ['id'=>'almacen','disabled'=>'disabled']) !!}
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12 m12 l6">
                <i class="mdi-editor-insert-invitation prefix"></i>
                <label for="fechainicio">Fecha de Inicio:</label>
                <input type="date"  id="fechainicio"  class="datepicker" placeholder=""/>
            </div>
            <div class="input-field col s12 m12 l6">
                <i class="mdi-editor-insert-invitation prefix"></i>
                <label class="fechafin">Fecha de Fin:</label>
                <input type="date"  id="fechafin" class="datepicker" placeholder=""/>
            </div>
        </div>
        <div class="input-field col s2">
            <a class="waves-effect waves-light btn" href="/Empleados">Volver</a>
        </div>
        <div class="input-field col s2">
            <a class="waves-effect waves-light btn" id="actualizar">Actualizar</a>
        </div>
        <div class="input-field col s2">
            <a class="waves-effect waves-light btn" id="guardar">Guardar</a>
        </div>
    </form>
</div>

@stop
@section('scripts')
{!! Html::script('js/addusuario.js') !!}
@endsection