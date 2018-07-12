@extends('Layouts.Panel')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Listado de Categorias</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li class="active">Listado de Categorias</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
@include('Modal.modalcategoria')
<div class="container">
    <h3><strong>Listado de Categoria </strong></h3>
    <!-- Modal Trigger -->
    <div class="row" style="padding-bottom: 20px;">
        <div class="col s12">   
            <a class="waves-effect waves-light btn modal-trigger" href="#modal2">NUEVA CATEGORIA</a>
        </div>
    </div>
    <div id="recargar"> 
        <table id="tablacategoria" class="centered display compact nowrap" cellspacing="0" width="100%">
            <thead>
            <th>ID</th>
            <th>Nombre</th>
            <th>Imagen</th>
            <th>Editar</th>
            <th>Eliminar</th>
            <th>Subcategoria</th>
            </thead>
            <tfoot style=" display: table-header-group; background: white;">
            <th>ID</th>
            <th>Nombre</th>   
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            </tfoot>
            <tbody id="datos">
            </tbody>
        </table> 
    </div>

    <div class="input-field col s6 m6 l6">
        @if(Session::has('message'))
        <div id="message">{{ Session::get('message') }}</div>
        @endif
    </div>
</div>
<input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
<!--/div-->

@stop
@section('scripts')
{!! Html::script('js/addcategoria.js') !!} 
@endsection