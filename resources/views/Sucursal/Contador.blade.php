@extends('Layouts.Panel')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Listado de Contador</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li class="active">Listado de Contador</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
@include('Modal.modalcontador')
<h3><strong>Listado de Contador</strong></h3>
<table id="tabla" class="centered display compact nowrap" cellspacing="0" width="100%">
    <thead>
    <th>Sucursal</th>
    <th>Numero Orden</th>
    <th>Editar</th>
    <th>Colocar a 0</th>
</thead>
<tfoot style=" display: table-header-group; background: white;">
<th>Sucursal</th>
<th>Numero Orden</th>
<th></th>
<th></th>
</tfoot>
<tbody id="datos">

</tbody>
</table>

@stop
@section('scripts')
{!! Html::script('js/addcontador.js') !!}



@endsection