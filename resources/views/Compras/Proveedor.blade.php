@extends('Layouts.Panel')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Gestionar Proveedores</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li class="active">Gestionar Proveedores</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
@include('Modal.modalproveedor')
<h3><strong>Listado de  Proveedor</strong></h3>
<div class="row" style="padding-bottom: 20px;">
    <div class="col s6">       
        <a class="btn" href="#" id="nuevoprovedor">NUEVO PROVEEDOR</a>
    </div>
</div>

<table id="tablacategoria" class="centered display compact nowrap" cellspacing="0" width="100%">
    <thead>        
    <th>Nombre del Proveedor</th>
    <th>Direccion</th>
    <th>Telefono</th>
    <th>Contacto Referencia</th>
    <th>Editar</th>
    <th>Eliminar</th>
</thead>
<tfoot style=" display: table-header-group; background: white;">
<th>Nombre del Proveedor</th>
<th>Direccion</th>
<th>Telefono</th>
<th>Contacto Referencia</th>
<th></th>
<th></th>
</tfoot>
<tbody id="datos">
</tbody>
</table>

@stop
@section('scripts')
{!! Html::script('js/addproveedor.js') !!}   
@endsection