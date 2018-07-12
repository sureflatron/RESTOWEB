@extends('Layouts.Panel')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Gestionar Ciudad</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li><a href="/GestionarPais">Listado de Paises</a></li>
                    <li class="active">Gestionar Ciudad</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
@include('Modal.modalCiudad')
<input type="hidden"  value="{{ $idpais }}" id="idpais">
<input type="hidden" name="_token" value="{{ csrf_token() }}" id="token"> 
<div class="row"> 
    <div class="col s12 m12 l12">
        <h4><strong>Agregar Ciudad a: </strong>{{ $nomPaiss }}</h4>
    </div>
</div>
<div class="row" style="padding-bottom: 20px;">
    <div class="input-field col s12 m2 l0">
        <i class="mdi-social-location-city prefix"></i>
        <label for="nombre">Nombre:</label>
        <input type="text"id="nombre" placeholder="">
    </div>
    <div class="input-field col s12 m2 l2">
        <a class="btn btn-floating waves-effect" id="guardar" href="#"><i class="mdi-content-add"></i></a>
    </div>
</div>
<table  id="tablacategoria" class="centered display compact nowrap" cellspacing="0" width="100%">
    <thead>
    <th>Ciudad</th>
    <th>Editar</th>
    <th>Eliminar</th>
</thead>
<tfoot  style=" display: table-header-group; background: white;">
<th>Ciudad</th>
<th></th>
<th></th>
</tfoot>
<tbody id="datos">
</tbody>
</table>

@stop
@section('scripts')
{!! Html::script('js/addciudad.js') !!}
@endsection
