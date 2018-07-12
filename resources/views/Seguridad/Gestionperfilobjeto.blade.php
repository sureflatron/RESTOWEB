@extends('Layouts.Panel')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Gestionar Perfil-Objeto</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li><a href="/GestionarPerfil">Lista de Perfil</a></li>
                    <li class="active">Gestionar Perfil-Objeto</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
@include('Modal.modalperfilobjeto')
<input type="hidden"  value="{{ $idproducto }}" id="idperfil">
<input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
<div class="row">
    <div class="col s12">
        <h4>
            <strong>Gestionar Permisos Para El Perfil:</strong> {{ $producto }}
        </h4> 
    </div>
</div>
<div class="row">
    <div class="input-field col s12 m4 l2">
        {!! Form::select('idproducto', $objeto,null, ['id'=>'idobjeto']) !!}
        <label for="Ingrediente">Objeto</label>
    </div>
    <div class="input-field col s12 m4 l1" > 
        <input type="checkbox" id="Guardars" />
        <label for="Guardars">Guardar</label>
    </div>
    <div class="input-field col s12 m4 l1" > 
        <input type="checkbox" id="Modificar" />
        <label for="Modificar">Modificar</label>
    </div>
    <div class="input-field col s12 m4 l1" > 
        <input type="checkbox" id="Eliminar" />
        <label for="Eliminar">Eliminar</label>
    </div>
    <div class="input-field col s12 m4 l1" > 
        <input type="checkbox" id="Listar" />
        <label for="Listar">Listar</label>
    </div>
    <div class="input-field col s12 m4 l1" > 
        <input type="checkbox" id="VerReporte" />
        <label for="VerReporte">VerReporte</label>
    </div>
    <div class="input-field col s12 m4 l1" > 
        <input type="checkbox" id="Imprimir" />
        <label for="Imprimir">Imprimir</label>
    </div>
    <div class="input-field col s12 m2 l3">
        <a class="btn waves-effect btn-floating" id="guardar">+</a>
    </div>
</div>
<div class="row" style="padding-top: 30px;">
    <div class="col s12">
        <table id="tablacategoria" class="centered display compact nowrap" cellspacing="0" width="100%">
            <thead>
            <th>Objeto</th>
            <th>Guardar</th> 
            <th>Modificar</th>
            <th>Eliminar</th>
            <th>Listar</th>
            <th>VerReporte</th>
            <th>Imprimir</th> 
            <th>Editar</th>
            <th>ELiminar</th>
            </thead>
            <tfoot style=" display: table-header-group; background: white;">
            <th>Objeto</th>
            <th>Guardar</th> 
            <th>Modificar</th>
            <th>Eliminar</th>
            <th>Listar</th>
            <th>VerReporte</th>
            <th>Imprimir</th> 
            <th></th>
            <th></th>
            </tfoot>
            <tbody id="datos">
            <td></td>
            </tbody>
        </table>
    </div>
</div>
@stop
@section('scripts')
{!! Html::script('js/addperfilobjeto.js') !!}
@endsection