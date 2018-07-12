@extends('Layouts.PanelVentas')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Listado de Productos</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li class="active">Listado de Productos</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
@include('Modal.modalproductoresto')
<div class="container">
    <div class="row">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <div class="col s12">
            <h3><strong>Listado  de Productos</strong></h3>
        </div>
    </div>
    <div class="row" style="padding-bottom: 20px;">
        <div class="col s12 m12 l3" style="padding: 10px;">
            <a class="btn" href="#" id="nuevoproducto">NUEVO PRODUCTO</a>
        </div>
        <!--        <div class="col s12 m12 l3" style="padding: 10px;">
                    <a class="btn waves-effect waves-light btn modal-trigger" href="#modal3" id="importar">IMPORTAR EXCEL</a>
                </div>-->
    </div>
    <div class="row">
        <div class="col s12">
            <!--<div class="table-responsive">-->
            <table id="tablacategoria" class="display compact nowrap" cellspacing="0" width="100%">
                <thead>
                <th>Tipo</th>
                <th>Categoria</th>
                <th>Nombre</th>
                <th>Descripcion</th>
                <th>Precio</th>
                <th>Editar</th>
                <th>Eliminar</th>
                <th>Combos</th>
                <th>Ingredientes</th>
                </thead>
                <tfoot style=" display: table-header-group; background: white;">
                <th>Tipo</th>
                <th>Categoria</th>
                <th>Nombre</th>
                <th>Descripcion</th>
                <th>Precio</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                </tfoot>
                <tbody id="datos">
                </tbody>
            </table>  
            <!--</div>-->
        </div>
    </div>
</div>

@stop
@section('scripts')
{!! Html::script('js/addproducto2.js') !!}
@endsection
