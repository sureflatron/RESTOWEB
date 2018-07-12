@extends('Layouts.PanelVentas')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Gestionar Producto</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li><a href="/Productos">Listado de Productos</a></li>
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
                <label for="categoria" class="label">Categoria *</label>
                <select id="categoria" class="browser-default">Selecione una Categoria</select>
            </div>
            <div class="col s12 m6 l6">
                <h6>Tipo de Producto</h6>
                <input name="group1" type="radio" id="test1" checked/>
                <label for="test1">Item</label>
                <input name="group1" type="radio" id="test2" />
                <label for="test2">Servicio</label>
                <input name="group1" type="radio" id="test5" />
                <label for="test5">Combo</label>
            </div>
        </div>
        <div class="row">
            <!--div class="col s12 m6 l6">
                <label for="categoria" class="label">SubCategoria *</label>
                <select id="subcategoria" class="browser-default">Selecione una Subcategoria</select>
            </div-->
            <!--div class="input-field col s12 m6 l6">
                <i class="mdi-action-turned-in-not prefix"></i>
                <input id="modelo" type="text" >
                <label for="modelo">Modelo:</label>
            </div-->
            <div class="col s12 m6 l6">
                <h6>¿Vender producto con Stock?</h6>
                <input name="group2" type="radio" id="test3" checked/>
                <label for="test3">SI</label>
                <input name="group2" type="radio" id="test4" />
                <label for="test4">NO</label>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <i class="mdi-action-view-week prefix"></i>
                <input id="incodBarra" type="text" onkeypress="return isNumberKey(this);">
                <label for="incodBarra">Codigo Barra: *</label>
            </div>
            <div class="input-field col s12 m6 l6">
                <i class="mdi-content-text-format prefix"></i>
                <input id="innombre" type="text" >
                <label for="nombre">Nombre: *</label>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12 m6 l6"> 
                <i class="mdi-editor-border-color prefix"></i>
                <input id="indescripcion" type="text" length="200" maxlength="200"/>
                <label for="indescripcion">Descripcion: *</label>
            </div>
             <div class="input-field col s12 m6 l6">
                <i class="mdi-editor-border-outer prefix"></i>
                <input id="incodInterno" type="text" >
                <label for="incodInterno">Codigo Interno: *</label>
            </div>
            <!--div class="input-field col s12 m6 l6">
                <i class="mdi-editor-format-color-fill prefix"></i>
                <input id="estilo" type="text" >
                <label for="estilo">Estilo:</label>
            </div-->
        </div>
        <!--div class="row">
            <div class="input-field col s12 m6 l6">
                <i class="mdi-image-blur-linear prefix"></i>
                <input id="corte" type="text" >
                <label for="corte">Corte:</label>
            </div>
           
        </div-->
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
                <select id="origen" class="browser-default"></select>
            </div>
            <div class="col s12 m6 l6">
                <label for="marca" class="active">Marca: *</label>
                <select id="marca" class="browser-default"></select>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <i class="mdi-image-texture prefix"></i>
                <input id="inmaterial" type="text" >
                <label for="inmaterial">Material: *</label>
            </div>
            <div class="input-field col s12 m6 l6">
                <i class="mdi-image-color-lens prefix"></i>
                <input id="incolor" type="text" >
                <label for="incolor">Color: *</label>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <i class="mdi-image-healing prefix"></i>
                <input id="inusado" type="text" value="NO">
                <label for="inusado">Usado:</label>
            </div>
            <div class="input-field col s12 m6 l6">
                <i class="mdi-toggle-check-box-outline-blank prefix"></i>
                <input id="inunidadesCaja" type="text" value="0" onkeypress="return isNumberKey(this);">
                <label for="inunidadesCaja">Unidades por Caja:</label>
            </div>
            <!--div class="input-field col s12 m6 l6">
                <i class="mdi-editor-format-size prefix"></i>
                <input id="intamano" type="text" >
                <label for="intamano">Tamaño/Talla: *</label>
            </div-->
        </div>
        <!--div class="row">
            <!--div class="input-field col s12 m6 l6">
                <i class="mdi-image-timelapse prefix"></i>
                <input id="inpeso" type="text" >
                <label for="inpeso">Peso:</label>
            </div>
            <div class="input-field col s12 m6 l6">
                <i class="mdi-toggle-check-box-outline-blank prefix"></i>
                <input id="inunidadesCaja" type="text" value="0" onkeypress="return isNumberKey(this);">
                <label for="inunidadesCaja">Unidades por Caja:</label>
            </div>
        </div-->
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <i class="mdi-hardware-keyboard-arrow-down prefix"></i>
                <input id="instockMin" type="number" value="2" onkeypress="return isNumberKey(this);">
                <label for="instockMin">Stock Minimo:</label>
            </div>
            <div class="input-field col s12 m6 l6">
                <i class="mdi-hardware-keyboard-arrow-up prefix"></i>
                <input id="instockMax" type="number" value="2" onkeypress="return isNumberKey(this);">
                <label for="instockMax">Stock Maximo:</label>
            </div>
        </div>
            
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <i class="mdi-image-healing prefix"></i>
                <input id="costo_inventario" type="number" value="0">
                <label for="costo_inventario">costo_inventario:</label>
            </div>
            <div class="input-field col s12 m6 l6">
                <i class="mdi-toggle-check-box-outline-blank prefix"></i>
                <input id="costo_pedido" type="number" value="0">
                <label for="costo_pedido">costo_pedido:</label>
            </div>          
        </div>    
            
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <span>Seleccionar imagen :</span>
                <div class="file-field input-field">
                    <div class="btn">
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
            </br>
            
            <div class="input-field col s12 m6 l6">
                <i class="mdi-action-turned-in-not prefix"></i>
                <input id="modelo" type="text" >
                <label for="modelo">Modelo:</label>
            </div>
                
        </div>
    </form>
</div>

<div class="row">
    <div class="input-field col s12 m6 l5" id="Guardaryvolveresconder" >
        <a class="btn" id="guardarvolver">Guardar</a>
        <a class="btn"   href="/Productos/">volver </a>
    </div>
</div>

<div class="row" id="ingredienteycomposicionmostrar" style='display:none;'>     
</div>

@stop
@section('scripts')
{!! Html::script('js/addnuevoproducto.js') !!}
@endsection
