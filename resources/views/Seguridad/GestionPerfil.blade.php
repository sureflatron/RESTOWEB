@extends('Layouts.Panel')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Lista de Perfiles</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li class="active">Lista de Perfiles</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
@include('Modal.modalperfil')
<h3><strong>Listado de  Perfil</strong></h3>
<div class="row" style="padding-bottom: 20px;">
    <div class="col s6">
        <a class="waves-effect waves-light btn modal-trigger" href="#modal1">NUEVO PERFIL</a>
    </div>
</div>
<table id="tablacategoria" class="centered display compact nowrap" cellspacing="0" width="100%">
    <thead>
    <th>Nombre</th>
    <th>Editar</th>
    <th>Eliminar</th>
    <th>Permisos</th>
</thead>
<tfoot style=" display: table-header-group; background: white;">
<th>Nombre</th>
<th></th>
<th></th>
<th></th>
</tfoot>
<tbody id="datos">
</tbody>
</table>

@stop
@section('scripts')
{!! Html::script('js/addperfil.js') !!}
@endsection