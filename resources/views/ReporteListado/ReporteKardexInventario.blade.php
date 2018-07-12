@extends('Layouts.Panel')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Reporte Kardex de inventario</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li class="active">Reporte Kardex de Inventario</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
<h4><strong>Reporte Kardex de Inventario</strong></h4>
<div class="row">
    <div class="col s12">
        <p>
            <input name="group1" type="radio" id="test1" checked />
            <label for="test1">Exportar a PDF</label>
        </p>
        <p>
            <input name="group1" type="radio" id="test2" />
            <label for="test2">Exportar a Excel</label>
        </p>
    </div>
</div>
<div class="row">
    <div class="input-field col s12 m6 l6">
        <i class="mdi-editor-insert-invitation prefix"></i>
        <label for="fechainicio">Fecha de Inicio: *</label>
        <input type="date" name="" id="fechainicio" class="datepicker" placeholder="">
    </div>
    <div class="input-field col s12 m6 l6">
        <i class="mdi-editor-insert-invitation prefix"></i>
        <label for="Fechafin">Fecha Fin: *</label>
        <input type="date" name="" id="Fechafin" class="datepicker" placeholder="">
    </div>
</div>
<div class="row">
    <div class="col s12 m6 l6">
        <label for="almacen">Sucursal: *</label>
        <select id="almacen" class="browser-default"> 
        </select>
    </div>
    <div class="col s12 m6 l6">
        <label for="almacenV">Almacen: *</label>
        <select id="almacenV" class="browser-default"> 
        </select>
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
            <th></th>
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
            <tbody id="datos">
            </tbody>
        </table>
    </div>
</div>
@stop
@section('scripts')
{!! Html::script('js/addreporte.js') !!}
@endsection    

