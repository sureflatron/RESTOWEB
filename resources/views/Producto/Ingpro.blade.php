@extends('Layouts.Panel')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Ingrediente de un Producto</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li><a href="/ProductosResto">Listado de Productos</a></li>
                    <li class="active">Ingrediente de un Producto</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
@include('Modal.modaldetalle')
<input type="hidden"  value="{{ $idproducto }}" id="Idproducto">
<input type="hidden" id="idingrediente">
<input type="hidden" id="idunidad">
<input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">

<div class="row">
    <div class="col s12">
        <h4><strong>Agregar Ingredientes a:</strong>{{ $producto }}</h4>
    </div>
</div>
<div class="row">
    <div class="input-field col s12 m12 l4">
        <label for="productonombre">Producto</label>
        <input type="text" list="productolist" name="buscar_producto" autocomplete="off" class="input-field" id="productonombre" value="" required>
        <datalist id="productolist">
            @foreach($ingrediente as $datos)
            <option data-id='{{ $datos->id }}' value="{{ $datos->nombre }}">
                @endforeach
        </datalist>
    </div>
    <div class="input-field col s12 m12 l2">
        <label for="unidadnombre">Unidad</label>
        <input type="text" list="unidadlist" name="buscar_unidad" autocomplete="off" class="input-field" id="unidadnombre" value="" required>
        <datalist id="unidadlist">
            @foreach($unidads as $dato)
            <option data-id='{{ $dato->id }}' value="{{ $dato->abreviatura }}">
                @endforeach
        </datalist>
    </div>
    <div class="input-field col s12 m12 l2" >
        {!!Form::text('nuevacantidad',null, ['id'=>'nuevacantidad','onkeypress'=>'return isNumberKey(this);'])!!}
        <label for="nuevacantidad">Cantidad :</label> 
    </div>
    <div class="input-field col s12 m12 l2" >
        <label for="costo">Costo :</label> 
        {!!Form::text('Costo',null, ['id'=>'costo','onKeypress'=>'return NumCheck(event, this)'])!!}
    </div>
    <div class="input-field col s12 m12 l2">   
        <a class="btn" id="guardar">AGREGAR</a>   
    </div>
</div>

<table class="centered striped responsive-table" >
    <thead>
    <th>Producto</th>
    <th>Descripcion</th>
    <th>Unidad</th>	 
    <th>cantidad</th>
    <th>Costo</th>
    <th>Editar</th>
    <th>Eliminar</th>
</thead>
<tbody id="datos">
</tbody>
</table>
<div class="row"> 
    <div class="col s12">
        <h5><strong>Total Costo Aproximado: </strong><span id='total'>0</span></h5>
    </div>
</div> 

@stop
@section('scripts')
{!! Html::script('js/addingpros.js') !!}
@endsection