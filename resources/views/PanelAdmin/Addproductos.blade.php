@extends('Layouts.Panel')
@section('Contenido')

<div id="breadcrumbs-wrapper" class=" grey lighten-3">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Nuevo  Producto</h5>
                <ol class="breadcrumb">

                    <li><a href="/index/">Inicio</a>
                    </li>   
                </ol>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
<div class="col s6">
    <h4 >Formulario de  Producto : </h4>
</div>

<div class="row">
    <form class="col s12">
        <div class="row">
            <div class="input-field col s6">
                <select       id="categoria">
                </select>
                <label>Categoria</label>
            </div>

        </div>


        <div class="row">
            <div class="input-field col s12">
                <input id="innombre" type="text" >
                <label for="nombre">Nombre :</label>
            </div>
            <div class="input-field col s12">

                <textarea id="indescripcion" class="materialize-textarea" length="200" c></textarea>
                <label for="descripcion">Descripcion :</label>
            </div>
            <div class="input-field col s12">
                <input id="inprecioVenta" type="text" onKeypress="if (event.keyCode < 45 || event.keyCode > 57)
                            event.returnValue = false;" class="active">
                <label for="precioVenta">Precio Venta :</label>
            </div>
        </div>
    </form>
</div>
<div class="row">


    <div class="input-field col s6" id="Guardaryvolveresconder" >
        <a class="btn"   href="/Productos/">volver </a>
        <a class="btn" id="guardarvolver">Guardar</a>

    </div>

</div>


<div class="row" id="ingredienteycomposicionmostrar" style='display:none;'>


</div>

@stop
@section('scripts')
{!! Html::script('js/addnuevoproducto.js') !!}
@endsection
