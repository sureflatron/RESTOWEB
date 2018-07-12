@extends('Layouts.PanelVentas')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li><a href="/listadeventares">Listado de Ventas RESTO</a></li>
                    <li class="active" style="color: black !important;"<strong>Nueva Venta RESTO</strong> - 
                        <strong>Codigo Venta:</strong> {!!$idventaultimo !!}  &nbsp; &nbsp; || &nbsp; &nbsp;  
                        <strong>Estado:</strong>{!!$actual !!}  &nbsp; &nbsp; || &nbsp; &nbsp; 
                        <strong>Fecha:</strong>{!!$fecha !!} &nbsp; &nbsp;
                        <strong>Almacen:</strong><span id="nombrealmacenventa">{!! $a !!}</span></li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
@include('Modal.modalFacturaResto')
<input type="hidden"  id="idproducto"> 
<input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
<input type="hidden" value="{{$idventaultimo}}" id="venta"> 
<input type="hidden" id="almacen" value="{{ $idalmacen }}">
<input type="hidden" id="idCliente" value="4"> 
<input type="hidden" value="{{$idm}}" id="idmoneda"> 
<div id="contenidoventa">
    <div class="row">
        <div class="col s12 m4 l4">
            <label for="mesa">SELECCIONE UNA MESA PARA LA VENTA:</label>
            {!! Form::select('mesa', $mesa,null, ['id'=>'mesa','placeholder' => 'Seleccione una Mesa']) !!}
        </div>
        @if ($p == "0")
        <div class="col s12 m4 l4">
            <label for="almacencombo">SELECCIONE ALMACEN PARA LA VENTA:</label>
            <select id="almacencombo">
                <option>Cambiar Almacen a</option>
            </select>
        </div>
        @endif
        <div class="col s12 m4 l4">
            <label for="tipoventa">SELECCIONE EL TIPO DE PRECIO DE LOS PRODUCTOS A VENDER</label>
            <select id="tipoventa">
                <option value="Contado">Contado</option>
                <option value="Credito">Credito</option>
            </select>
        </div>
        <!--        <div class="col s12 m3 l3 center-align" id="generadordeventa" style='display:none;'>
                    <p><a class="btn animated infinite jello" href="#" id="generarventa">
                            <i class="mdi-action-autorenew"></i>NUEVA VENTA</a></p>
                </div>-->
    </div>
    <div class="row">
        <div class="col s12 m12 l6">
            <div class="row">
                <div class="grid-example col s12">
                    <span class="flow-text"><strong>PRODUCTOS</strong></span>
                </div>
            </div>
            <div class="row">
                <div class="cl s12">
                    <ul class="collapsible collapsible-accordion" data-collapsible="accordion">
                        <li>
                            <div class="collapsible-header active" id="titulotipoprodcuto" >
                                <i class="mdi-action-dns"></i>TIPO DE PRODUCTO
                            </div>
                            <div class="collapsible-body" id="tipoproducto">
                                <div class="row" style="text-align: center;">
                                    <div class="row" id="divrow2" ></div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="collapsible-header"  id="tituloproductos">
                                <i class="mdi-action-dashboard"></i>PRODUCTOS
                            </div>
                            <div class="collapsible-body" id="productos">
                                <div class="row" style="text-align: center;">
                                    <div class="row" id="sdivrow"></div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col s12 m12 l6">
            <div class="row">
                <div class="grid-example col s12">
                    <span class="flow-text"><strong>AGREGAR PRODUCTO</strong></span>
                </div>
            </div>
            <div class="row" id="datallleproducto">
                <div class="col s12 m4 l4 center-align">
                    <br>
                    <span id="nombreproducto">Producto Seleccionado</span>
                </div>
                <div class="input-field col s12 m4 l4 center-align">
                    <i class="mdi-content-inbox prefix"></i>
                    <label for='cantidadproducto'>CANTIDAD</label>
                    <input type="text" name="" value ="1" id="cantidadproducto" onkeypress="return isNumberKey(this);">
                </div>
                <div class="col s12 m4 l4 left-align">
                    <br>
                    <a class="btn waves-effect btn-floating" id="agregardetalle"><i class="mdi-image-control-point"></i></a>
                </div>
            </div>
            <div class="row">
                <div class="grid-example col s12">
                    <span class="flow-text"><strong>DETALLE DE VENTA</strong></span>
                </div>
            </div>
            <div class="row">
                <div class="col s12">
                    <table class="striped centered responsive-table">
                        <thead>
                            <tr>
                                <th data-field="id">Producto</th>
                                <th data-field="name">Cantidad</th>
                                <th data-field="price">Precio</th>
                                <th data-field="price">Subtotal</th>
                                <th data-field="price">Eliminar</th>
                            </tr>
                        </thead>
                        <tbody id="datos">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col s12">
                <h5><strong>Total:</strong><span id="total">0</span></h5>
            </div> 
            <div class="col s12">
                <div class="row">
                    <div class="col s12 m6 s3" style="padding: 10px; text-align: center;"> 
                        <a class="waves-effect waves-light btn " href="#"  OnClick='cargarfactura();'>Cobrar</a>
                    </div>  
                    <div class="col s12 m6 s3" style="padding: 10px; text-align: center;">
                        <a class="btn" href="#" id="anularventa">Anular</a>
                    </div>
                    <div class="col s12 m6 s3" style="padding: 10px; text-align: center;">
                        <a class="waves-effect waves-light btn modal-trigger" href="#modal3">Programar</a>
                    </div>
                    <div class="col s12 m6 s3" style="padding: 10px; text-align: center;">
                        <a  class="btn" href="/listadeventares/" tooltips >volver</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col s12 m6 l6 center-align offset-l3 offset-m3" id="generadordeventa" style='display:none ;'>
        <div class="browser-window">
            <div class="top-bar">
                <div class="circles">
                    <div id="close-circle" class="circle"></div>
                    <div id="minimize-circle" class="circle"></div>
                    <div id="maximize-circle" class="circle"></div>
                </div>
            </div>
            <div class="content">
                <div class="row">
                    <div id="site-layout-example-top " class="col s12 grey lighten-2">
                        <p class="flat-text-logo center black-text caption-uppercase">LA VENTA SE REALIZO CON EXITO :)</p>
                    </div>
                    <div id="site-layout-example-right" class="col s12 m12 l12 grey lighten-2">
                        <div class="row center">
                            <h1 class="col s12"><a  class="btn waves-effect waves-light animated infinite jello" href="#" id="generarventa">
                                    <i class="mdi-action-autorenew"></i>NUEVA VENTA</a></h1>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('scripts')
{!! Html::script('js/addventares.js') !!}
@endsection