@extends('Layouts.Panel')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Reporte del Estado de Inventario</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li class="active">Reporte del Estado de Inventario</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
<h4><strong>Reporte del Estado del Inventario</strong></h4>
<div class="row">
    <div class="col s12 m5 l3">
        <input name="group1" type="radio" id="test1" checked />
        <label for="test1"><strong>Exportar a PDF</strong></label>
    </div>
</div>
<div class="row">
    <div class="col s12">
        <input name="group1" type="radio" id="test2" />
        <label for="test2"><strong>Exportar a Excel</strong></label>
    </div>
</div>
<div class="row">
    <div class="col s12">
        <h6><strong>Sucursal:*</strong></h6>
    </div>
</div>
<div class="row">
    <div class="col s12">
        <select id="almacen"> 
        </select>
    </div>
</div>
<div class="row">
    <div class="col s12">
        <h6><strong>Almacen:*</strong></h6>
    </div>
</div>
<div class="row">
    <div class="col s12">
        <select id="almacenV"> 
        </select>
    </div>
</div>
<div class="row">
    <div class="col s12 m5 l3">
        {!!link_to('#', $title='Generar', $attributes = ['id'=>'estadoInventario', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
</div>
<div class="row">
    <div class="col s12">
        <table id="tablacategoria" class="centered display compact nowrap" cellspacing="0" width="100%">
            <thead>
            <th>Categoria</th>
            <th>Codigo de Barra</th>
            <th>Codigo Interno</th>
            <th>Nombre</th>
            <th>Precio</th>
            <th>Origen</th>
            <th>Color</th>
            <th>Talla</th>
            <th>Marca</th>
            <th>Operaciones</th>
            </thead>
            <tfoot style=" display: table-header-group; background: white;">
            <th>Categoria</th>
            <th>Codigo de Barra</th>
            <th>Codigo Interno</th>
            <th>Nombre</th>
            <th>Precio</th>
            <th>Origen</th>
            <th>Color</th>
            <th>Talla</th>
            <th>Marca</th>
            <th></th>   
            </tfoot>
            <tbody id="datosInv">
            </tbody>
        </table>
    </div>
</div>


@stop
@section('scripts')
{!! Html::script('js/addreporte.js') !!}
@endsection
