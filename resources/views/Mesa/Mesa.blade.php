@extends('Layouts.Panel')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Listado de Mesas</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li class="active">Listado de Mesas</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
@include('Modal.modalmesa')
<div class="container">
    <h3><strong>Listado de Mesa</strong></h3>
    <!-- Modal Trigger -->
    <div class="row">
        <div class="col s6">
            <!--<input placeholder="Buscar Mesa" id="buscar" type="text">-->          
            <a class="waves-effect waves-light btn modal-trigger" href="#modal1">NUEVA MESA</a>
        </div>
        <!--<a class="waves-effect waves-light btn" onclick="Buscadordeproducto()"><i class="material-icons">search</i></a>-->
    </div>
    <table id="tablacategoria" class="centered display compact nowrap" cellspacing="0" width="100%">
        <thead>
        <th>ID</th>
        <th>Num. Mesa</th>
        <th>Ubicacion</th>
        <th>Capacidad</th>
        <th>Estado</th>
        <th>Sucursal</th>
        <th>Editar</th>
        <th>Eliminar</th>
        </thead>
        <tfoot style=" display: table-header-group; background: white;">
        <th>ID</th>
        <th>Num. Mesa</th>
        <th>Ubicacion</th>
        <th>Capacidad</th>
        <th>Estado</th>
        <th>Sucursal</th>
        <th></th>
        <th></th>
        </tfoot>
        <tbody id="datos">
        </tbody>
    </table>
</div>
<input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
@stop
@section('scripts')
{!! Html::script('js/addmesa.js') !!}
@endsection