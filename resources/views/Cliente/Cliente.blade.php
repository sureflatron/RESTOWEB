@extends('Layouts.Panel')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Listado de Clientes</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li class="active">listado de Clientes</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
@include('Modal.modalcliente')
<div class="container">
    <h3><strong>Listado de Cliente</strong></h3>
    <!-- Modal Trigger -->
    <div class="row" style="padding-bottom: 20px;">
        <div class="col s12">
            <button class="waves-effect waves-light btn modal-trigger" data-target="modal1">NUEVO CLIENTE</button>
        </div>
    </div>
    <table id="tablacategoria" class="display compact nowrapcentered" cellspacing="0" width="100%">
        <thead>
        <th>Nombre</th>
        <th>Dirección</th>
        <th>Teléfono</th>
        <th>Celular</th>
        <th>Correo electrónico </th>
        <th>Ciudad</th>
        <th>Tipo Cliente</th>
        <th>Preferencias</th>
        <th>Editar</th>
        <th>Eliminar</th>
        </thead>
        <tfoot style=" display: table-header-group; background: white;">
        <th>Nombre</th>
        <th>Dirección</th>
        <th>Teléfono</th>
        <th>Celular</th>
        <th>Correo electrónico </th>
        <th>Ciudad</th>
        <th>Tipo Cliente</th>
        <th>Preferencias</th>
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
{!! Html::script('js/addcliente.js') !!} 
@endsection