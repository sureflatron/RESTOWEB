@extends('Layouts.PanelVentas')
@section('Contenido')
<input type="hidden"  value="{{ $idempleado }}" id="idempleado">
<input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
<br>
<div class="col s12 center-align">
    <h4><strong>Datos de Usuario</strong></h4>
    <span><i class="mdi-action-perm-identity cyan-text text-darken-2"></i>{!! Session::get('nombre') !!}</span><br>
    <span><i class="mdi-editor-insert-emoticon cyan-text text-darken-2"></i>{!!  Session::get('Cargo') !!}</span>
</div>
<div class="col s12 center-align">
    <div class="input-field col s12">
        <img  class="circle responsive-img valign profile-image materialboxed"  style="margin: auto;" width="100" height="100" src="../images/avatar.jpg" id="vistaPrevia2">
    </div>
</div>
<div class="col s12 center-align">
    <div class="row">
        <div class="col s12 m12 l6 offset-l3">
            <ul class="collapsible collapsible-accordion" data-collapsible="accordion">
                <li>
                    <div class="collapsible-header"><i class="mdi-image-camera"></i>Cambiar Imagen de Perfil</div>
                    <div class="collapsible-body">
                        <div class="input-field col s12">
                            <div class="file-field input-field">
                                <div class="btn">
                                    <i class="mdi-image-camera-alt"></i>
                                    <input type="file" id="seleccionarImagen2">
                                </div>
                                <div class="file-path-wrapper">
                                    <input class="file-path validate" type="text" id="nombreimg2">
                                </div>
                            </div>
                        </div>
                        <div class="col s12 center-align">
                            <a class="waves-effect waves-light btn" id="cambiarimagen"><i class="mdi-content-send"></i>Cambiar Imagen</a>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="collapsible-header"><i class="mdi-action-lock"></i>Cambiar Contraseña</div>
                    <div class="collapsible-body">
                        <div class="col s12">
                            <div class="row">
                                <div class="input-field col s12">
                                    <i class="mdi-action-lock prefix"></i>
                                    <label for="password">Contraseña Actual:</label>
                                    <input id="passwordold" type="password"  placeholder="">
                                </div>
                                <div class="input-field col s12">
                                    <i class="mdi-action-label prefix"></i>
                                    <label for="password">Nueva Contraseña:</label>
                                    <input id="passwordnew" type="password"  placeholder="">
                                </div>
                                <div class="input-field col s12">
                                    <i class="mdi-action-label-outline prefix"></i>
                                    <label for='password2' >Repita Su Nueva Contraseña:</label>
                                    <input  id="passwordnewrepeat" type="password" placeholder="" >
                                </div>
                                <div class="input-field col s12 center-align">
                                    <a class="waves-effect waves-light btn" id="actualizars"><i class="mdi-content-send"></i>Actualizar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
@stop
@section('scripts')
{!! Html::script('js/addusuario.js') !!}
@endsection