@extends('Layouts.PanelVentas')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Listado de Productos Por Sucursal</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li class="active">Listado de Productos Por Sucursal</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
<input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
<div class="container">    
    <div class="col s12">
        <h3><strong>Listado  de Productos Por Sucursal</strong></h3>
    </div>        
    <div class="row">
        <div class="col s12 m6 l6">
            <label for="sucursal">Listado de Sucursales:</label>
            <select id="sucursal">                       
            </select>                           
        </div>                       
    </div>   
    <div class="row" id="cargando">
        <div class="col s12">
            <div class="progress">
                <div class="indeterminate"></div>
            </div>
            <div class="progress">
                <div class="indeterminate"></div>
            </div>
            <div class="progress">
                <div class="indeterminate"></div>
            </div>
        </div>
    </div>
    <div class="row" id="listado" style="display: none;">
        <div class="col s12">
            <table id="tablacategoria" class="display compact nowrap" cellspacing="0" width="100%">
                <thead>
                <th>Categoria</th>
                <th>Codigo de Barra</th>
                <th>Codigo Interno</th>
                <th>Nombre</th>
                <th>Precio Contado</th>
                <th>Precio Credito</th>
                <th>Origen</th>
                <th>Marca</th>
                <th>Tipo</th>
                <th>Sucursal</th>
                <th>Agregar</th>                
                </thead>
                <tfoot style=" display: table-header-group; background: white;">
                <th>Categoria</th>
                <th>Codigo de Barra</th>
                <th>Codigo Internos</th>
                <th>Nombre</th>
                <th>Precio Contado</th>
                <th>Precio Credito</th>
                <th>Origen</th>
                <th>Marca</th>
                <th>Tipo</th>
                <th>Sucursales</th>
                <th></th>                
                </tfoot>
                <tbody id="datos">
                </tbody>
            </table>              
        </div>
    </div>
</div>    

@stop
@section('scripts')
{!! Html::script('js/productosucursaladd2.js') !!}
@endsection
