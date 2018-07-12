@extends('Layouts.Panel')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Combo de Productos</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li><a href="/Productos">Listado de Productos</a></li>
                    <li class="active">Combo de Productos</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
@include('Modal.modalcomposicionproducto')
<input type="hidden"  value="{{ $idproducto }}" id="Idproducto">
<input type="hidden" id="idcomposicion">
<input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
<div class="row">
    <div class="col s12">
        <h4><strong>Agregar Productos al Combo: </strong>{{ $nombreproducto }}</h4>
    </div>
</div>
<div class="row">
    <div class="input-field col s12 m4 l4">
        <label for="productonombre">Producto</label>
        <input type="text" list="productolist" name="buscar_producto" autocomplete="off" class="input-field" id="productonombre" value="" required>
        <datalist id="productolist">
            @foreach($productos as $datos)
            <option data-id='{{ $datos->id }}' value="{{ $datos->nombre }}">
            @endforeach
        </datalist>
    </div>
    <div class="input-field col s12 m4 l4" >
        <label for="nuevacantidad">Cantidad :</label> 
        {!!Form::text('nuevacantidad',null, ['id'=>'nuevacantidad','onkeypress'=>'return isNumberKey(this);'])!!}
    </div>
    <div class="input-field col s12 m2 l4">
        <a class="btn" id="guardar">Agregar</a>
    </div>
</div>
<div class="row">
    <div class="col s12">
        <table class="centered striped responsive-table">
            <thead>
            <th>Producto</th>
            <th>Descripcion</th>
            <th>Cantidad</th>	 
            <th>Editar</th>
            <th>Eliminar</th>
            </thead>
            <tbody id="datos">
            </tbody>
        </table>
    </div>
</div>
@stop
@section('scripts')
{!! Html::script('js/addcompo.js') !!}
@endsection