@extends('Layouts.PanelVentas')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Listado de Compras</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li class="active">Listado de Compras</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
<input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
<h3><strong>Listado de  Compras</strong></h3>
<div class="row" style="padding-bottom: 20px;">
    <div class="col s12">   
        <a class="btn" href="#" id="nuevacompra">NUEVA COMPRA</a>
    </div>
</div>
<table class="centered display compact nowrap" cellspacing="0" width="100%" id="tabla">
    <thead>      
    <th>ID</th>
    <th>Fecha</th>
    <th>Hora</th>
    <th>Almacen</th>
    <th>Proveedor</th>
    <th>Estado</th>
    <th>OrdenEstado</th>
    <!--th>Registrado</th-->
    <th>FaltanDias</th>
    <th>Total</th>
    <th>Forma Pago</th>
    <th>Saldo</th>
    <th>Agregar Gasto</th>
    <th>Editar</th>
    <th>Imprimir</th>
    <th>Eliminar</th>
</thead>
<tfoot style=" display: table-header-group; background: white;">
<th>ID</th>
<th>Fecha</th>
<th>Hora</th>
<th>Almacen</th>
<th>Proveedor</th>
<th>Estado</th>
<th>OrdenEstado</th>
<th>FaltanDias</th>
<th>Total</th>
<th>Forma Pago</th>
<th>Saldo</th>
<th></th>
<th></th>
<th></th>
<th></th>
</tfoot>
<tbody id="datos">

</tbody>
</table>

@stop
@section('scripts')
{!! Html::script('js/addcompras.js') !!}   
@endsection