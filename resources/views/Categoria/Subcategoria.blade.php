@extends('Layouts.Panel')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Gestionar Ciudad</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li><a href="/Categoria">Listado de Categorias</a></li>
                    <li class="active">Gestionar Categoria</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
@include('Modal.modalSubcategoria')
<input type="hidden"  value="{{ $idcategoria }}" id="idcategoria">
<input type="hidden" name="_token" value="{{ csrf_token() }}" id="token"> 
<div class="row"> 
    <div class="col s12 m12 l12">
        <h4><strong>Agregar Subcategoria a: </strong>{{ $nomCat }}</h4>
    </div>
</div>
<div class="row" style="padding-bottom: 20px;">
    <div class="input-field col s12 m12 l12">
        <i class="mdi-social-location-city prefix"></i>
        <label for="nombre">Nombre:</label>
        <input type="text"id="nombre" placeholder="">
    </div>
    <div class="input-field col s12 m12 l12">
         <span>Seleccionar imagen :</span>
        <form action="#">
            <div class="file-field input-field">
                <div class="btn">
                    <span><i class="material-icons">open_in_browser</i></span>
                    <input type="file" id="seleccionarImagen">
                </div>
                <div class="file-path-wrapper">
                    <input class="file-path validate" type="text" id="nombreimg">
                </div>
            </div>
        </form>
        <img id="vistaPrevia" src="/images/productoavatar.png" style="width: 180px;"/> 
        <span>Imagen de tamaño 64x64 px</span>
    </div>
    
    <div class="input-field col s12 m2 l2">
        <a class="btn" id="guardar" href="#">Agregar<!--i class="mdi-content-add"></i--></a>
    </div>
</div>
<table  id="tablacategoria" class="centered display compact nowrap" cellspacing="0" width="100%">
     <thead>
    <th>Subcategoria</th>
    <th>Imagen</th>
    <th>Editar</th>
    <th>Eliminar</th>
        </thead>
<tfoot  style=" display: table-header-group; background: white;">
<th>Subcategoria</th>
<th></th>
<th></th>
<th></th>
</tfoot>
<tbody id="datos">
</tbody>
</table>

@stop
@section('scripts')
{!! Html::script('js/addsubcategoria.js') !!}
@endsection
