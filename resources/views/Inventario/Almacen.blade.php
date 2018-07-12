@extends('Layouts.Panel')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Listado de Almacen</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li class="active">Listado de Almacen</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
@include('Modal.modalalmacen')
<div class="container">
    <h3><strong>Listado de Almacen</strong></h3>
    <!-- Modal Trigger -->
    <div class="row" style="padding-bottom: 20px;">
        <div class="col s12">
            <a class="waves-effect waves-light btn modal-trigger" href="#modal1"">NUEVO ALMACEN</a>
        </div>
    </div>
    <table id="tablacategoria" class="centered display compact nowrap" cellspacing="0" width="100%">
        <thead>
        <th>Nombre</th>
        <th>Sucursal</th>
        <th>Responsable</th>
        <th>Editar</th>
        <th>Eliminar</th>
        </thead>
        <tfoot style=" display: table-header-group; background: white;">
        <th>Nombre</th>
        <th>Sucursal</th>
        <th>Responsable</th>
        <th></th>
        <th></th>
        </tfoot>
        <tbody id="datos">

        </tbody>
    </table> 
</div>

<input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
</div>

@stop
@section('scripts')
{!! Html::script('js/addalmacen.js') !!}   
@endsection
