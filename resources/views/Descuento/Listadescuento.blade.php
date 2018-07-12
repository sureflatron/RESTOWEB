@extends('Layouts.Panel')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Listado de Descuentos</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li class="active">Listado de Descuentos</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
@include('Modal.modaldescuento')
<input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
<h3><strong>Listado de  Descuentos</strong></h3>
<div class="row" style="padding-bottom: 20px;">
    <div class="col s6">         
        <a class='waves-effect waves-light btn modal-trigger'  href='#modal1'   id="iddescuento">NUEVO DESCUENTO</a>
    </div>
</div>

<table id="tablacategoria" class="centered display compact nowrap" cellspacing="0" width="100%">
    <thead>
    <th>ID</th>
    <th>Nombre</th>
    <th>Descuento</th>
    <th>Fecha Inicio</th>
    <th>Fecha Fin</th>
    <th>Editar</th>
    <th>Eliminar</th>
</thead>
<tfoot style=" display: table-header-group; background: white;">
<th>ID</th>
<th>Nombre</th>
<th>Descuento</th>
<th>Fecha Fin</th>
<th>Fecha Fin</th>
<th></th>
<th></th>
</tfoot>
<tbody id="datos">
</tbody>
</table>

@stop
@section('scripts')
{!! Html::script('js/adddecuento.js') !!}
@endsection