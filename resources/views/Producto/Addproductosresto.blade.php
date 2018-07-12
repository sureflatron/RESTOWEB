@extends('Layouts.PanelVentas')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Gestionar Producto</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li><a href="/ProductosResto">Listado de Productos</a></li>
                    <li class="active">Gestionar Producto</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
<input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
<div class="col s12">
    <h3><strong>Formulario de  Producto</strong></h3>
</div>
<div class="row">
    <form class="col s12">
        <div class="row">
            <div class="col s12 m6 l6">
                <label for="categoria" class="active">Categoria *</label>
                <select id="categoria"></select>
            </div>
            <div class="col s12 m6 l6">
                <h6>Tipo de Producto</h6>                        
                <input name="group1" type="radio" id="test3" checked/>
                <label for="test3">Comida</label>
                <input name="group1" type="radio" id="test4" />
                <label for="test4">Ingrediente</label>
                <input name="group1" type="radio" id="test1" />
                <label for="test1">Item</label>
                <input name="group1" type="radio" id="test2" />
                <label for="test2">Combo</label>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <i class="mdi-content-text-format prefix"></i>
                <input id="innombre" type="text" >
                <label for="nombre">Nombre: *</label>
            </div>
            <div class="col s12 m6 l6">
                <h6>Venta Directa</h6>                        
                <input name="group2" type="radio" id="test8" checked/>
                <label for="test8">SI</label>
                <input name="group2" type="radio" id="test9" />
                <label for="test9">NO</label>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12 m6 l6"> 
                <i class="mdi-editor-border-color prefix"></i>
                <input id="indescripcion" type="text" length="200" maxlength="200" class="materialize-textarea"/>
                <label for="indescripcion">Descripcion: *</label>
            </div>
            <div class="col s12 m6 l6">
                <h6>¿Vender producto con Stock?</h6>
                <input name="group3" type="radio" id="test5" checked/>
                <label for="test5">SI</label>
                <input name="group3" type="radio" id="test6" />
                <label for="test6">NO</label>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <i class="mdi-editor-attach-money prefix"></i>
                <input id="inprecioVenta" type="text" onkeypress="return NumCheck(event, this)" class="active" >
                <label for="precioVenta">Precio Venta Efectivo: *</label>
            </div>
            <div class="input-field col s12 m6 l6">
                <i class="mdi-editor-attach-money prefix"></i>
                <input id="inprecioVentaCredito" type="text" onkeypress="return NumCheck(event, this)" class="active" >
                <label for="inprecioVentaCredito">Precio Venta Credito: *</label>
            </div>
        </div>
        <div class="row">
            <div class="col s12 m6 l6">
                <label for="origen" class="active">Origen: *</label>
                <select id="origen" ></select>
            </div>
            <div class="col s12 m6 l6">
                <label for="marca" class="active">Marca: *</label>
                <select id="marca" ></select>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="input-field col s12 m4 l4">
                <i class="mdi-hardware-keyboard-arrow-down prefix"></i>
                <input id="instockMin" type="text" value="2" onkeypress="return isNumberKey(this);">
                <label for="instockMin">Stock Minimo:</label>
            </div>
            <div class="input-field col s12 m4 l4">
                <i class="mdi-hardware-keyboard-arrow-up prefix"></i>
                <input id="instockMax" type="text" value="2" onkeypress="return isNumberKey(this);">
                <label for="instockMax">Stock Maximo:</label>
            </div>
            <div class="input-field col s12 m4 l4">
                <i class="mdi-toggle-check-box-outline-blank prefix"></i>
                <input id="inunidadesCaja" type="text" value="0" onkeypress="return isNumberKey(this);">
                <label for="inunidadesCaja">Unidades por Caja:</label>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12 m12 l12">
                <div class="file-field input-field">
                    <div class="btn">
                        <label for="seleccionarImagen" class="active">Seleccione una imagen:</label>
                        <span><i class="material-icons">open_in_browser</i></span>
                        <input type="file" id="seleccionarImagen">
                    </div>
                    <div class="file-path-wrapper">
                        <input class="file-path validate" type="text" id="nombreimg">
                    </div>
                </div>
                <img id="vistaPrevia" src="/images/productoavatar.png" style="width: 200px;"/>
                <span>Imagen de tamaño 64x64 px</span>
            </div>
        </div>
    </form>
</div>
<div class="row">
    <div class="input-field col s12 m6 l5" id="Guardaryvolveresconder" >
        <a class="btn" id="guardarvolver">Guardar</a>
        <a class="btn"   href="/ProductosResto/">volver </a>
    </div>
</div>

@stop
@section('scripts')
{!! Html::script('js/addnuevoproducto2.js') !!}
@endsection
