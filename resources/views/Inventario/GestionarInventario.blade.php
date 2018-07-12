@extends('Layouts.PanelVentas')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Listado de invenatarios</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li class="active">Listado de invenatarios</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
<input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
<h3><strong>Listado de  Inventario</strong></h3>
<div class="row" style="padding-bottom: 20px;">
    <div class="col s12">
        <a class="btn" href="#" id="nuevainventario">NUEVO INVENTARIO</a>
    </div>
</div>

<table id="tablacategoria" class="centered display compact nowrap" cellspacing="0" width="100%">
    <thead>      
    <th>ID</th>
    <th>Fecha</th>
    <th>Hora</th>
    <th>Tipo de inventario</th>
    <th>Estado</th>
    <th>Almacen Origen</th>
    <th>Almacen Destino</th>
    <th>Motivo</th>
    <th>Registrado</th>
    <th>Editar</th>
    <th>Imprimir</th>
    <th>Eliminar</th>
</thead>
<tfoot  style=" display: table-header-group; background: white;">
<th>ID</th>
<th>Fecha</th>
<th>Hora</th>
<th>Tipo de inventario</th>
<th>Estado</th>
<th>Almacen Origen</th>
<th>Almacen Destino</th>
<th>Motivo</th>
<th>Registrado</th>
<th></th>
<th></th>
<th></th>
</tfoot>
<tbody id="datos">

</tbody>
</table>

@stop
@section('scripts')
{!! Html::script('js/addinventario.js') !!} 
@endsection