@extends('Layouts.PanelVentas')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li><a href="/listadeventa">Listado de Ventas POS</a></li>
                    <li class="active" style="color: black !important;"><strong>Nueva Venta POS</strong> - &nbsp; &nbsp; 
                        <strong>Codigo Venta:</strong> {!!$idventaultimo !!}  &nbsp; &nbsp; || &nbsp; &nbsp;  
                        <strong>Estado:</strong>{!!$actual !!}  &nbsp; &nbsp; || &nbsp; &nbsp; 
                        <strong>Fecha:</strong>{!!$fecha !!} &nbsp; &nbsp; || &nbsp; &nbsp;
                        <strong>Moneda:</strong>{!! $moneda1 !!}
                        <strong>Esta a:</strong>{!! $bs !!}&nbsp; &nbsp; || &nbsp; &nbsp;
                        <strong>Almacen:</strong><span id="nombrealmacenventa">{!! $a !!}</span></li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
<input type="hidden"  id="idproducto"> 
<input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
<input type="hidden" value="{{$idventaultimo}}" id="venta">
<input type="hidden" id="idCliente"> 
<input type="hidden" value="{{$idm}}" id="idmoneda"> 
<input type="hidden" id="almacen" value="{{ $idalmacen }}">
<div id="contenidoventa">
    <div class="row">
        <div class="col s6 m6 l6">               
            <div class="row">                
                <div class="input-field col s6">
                    <i class="mdi-action-account-circle prefix"></i>
                    <label for="cliNombre">SELECCIONE UN CLIENTE</label>
                    @if( $idCliente == null)
                    <input type="text" list="clienteslist" name="buscar_cliente" autocomplete="off" class="input-field" id="cliNombre" required placeholder="">
                    @else
                    <input type="text" list="clienteslist" name="buscar_cliente" autocomplete="off" class="input-field" id="cliNombre" value="{{$nombre}}" required placeholder="">
                    @endif
                    <datalist id="clienteslist">
                        @foreach($clientes as $datos)
                        <option data-id='{{ $datos->id }}' value="{{ $datos->nombre }}"> CODIGO: {{ $datos->id }}
                            @endforeach
                    </datalist>
                </div>
                
                <div class="col s1">
                    <br>
                    <a class="waves-effect waves-light btn modal-trigger tooltipped btn-floating" href="#modal2" id="agregarcliente" data-position="right" 
                       data-delay="50" data-tooltip="Nuevo Cliente">
                        <i class="material-icons">assignment_ind</i>
                    </a>
                </div>
                <div class="col s1">
                    <a style ='display:none;' class="waves-effect waves-light btn modal-trigger" href="#modalAct" id="actualizarcliente" >
                        <i class="material-icons">supervisor_account</i>
                    </a>
                </div>
                <div class="col s1">
                    <br>
                    <a style ='display:none;' class="waves-effect waves-light btn modal-trigger btn-floating tooltipped" href="#modalAct" id="actualizarcliente" data-position="right" 
                       data-delay="50" data-tooltip="Actualizar Cliente">
                        <i class="material-icons">mode_edit</i>
                    </a>
                </div>                
                    <div class="input-field col s4 m4 ">
                    
                    <label for="fecha"> FECHA :</label>
                    <input id="fecha" type="date" class="datepicker" >
                    </div>
                
            </div>
            
        </div>       
        <div class="col s12 m6 l6">
        @if ($p == "0")
        <div class="col s5 m5 l5">
            <label for="almacencombo">SELECCIONE EL ALMACEN PARA LA VENTA</label>
            <select id="almacencombo">
                <option>Cambiar Almacen a</option>
            </select>
        </div>
        @endif
        <div class="col s7 m7 l7">
            <label for="tipoventa">SELECCIONE EL TIPO DE PRECIO DE LOS PRODUCTOS A VENDER</label>
            <select id="tipoventa">
                <option value="Contado">Contado</option>
                <option value="Credito">Credito</option>
            </select>
        </div>
        </div>
        
    </div>
    <div class="row" id="creditlist">
        <ul id="task-card" class="collection with-header">
            <li class="collection-header naranja" style="padding: 5px !important;">
                <h5 class="task-card-title"><strong>BUSCAR PRODUTOS</strong></h5>
            </li>
            <li class="collection-item dismissable">
                <div class="row">
                    <div class="input-field col s12 m6 l3">
                        <i class="mdi-action-view-column prefix"></i>
                        <input id="codigoBarra" type="text" class="validate"  onchange="codigobarraagregardetalle()" placeholder="" onkeypress="return isNumberKey(this);">
                        <label for="codigoBarra">Codigo de Barras</label>
                    </div>
                    <div class="input-field col s12 m6  l3">
                        <i class="mdi-action-label-outline prefix"></i>
                        <input id="codigoInterno" type="text" class="validate"  placeholder="">
                        <label for="codigoInterno">Codigo Interno</label>
                    </div>
                    <div class="col s12 m12 l6">
                        <div class="row">
                            <div class="input-field col s12 m9  l9">
                                <i class="mdi-action-search prefix"></i>
                                <input id="nombreproducto" type="text" class="validate"  placeholder="">
                                <label for="nombre">Nombre</label>
                            </div>
                            <div class="input-field col s12 m3 l3">
                                <a class="waves-effect waves-light btn" id="buscarproducto" style="display: block; justify-content: space-between;" onclick="buscarproductoinput()"><i class="material-icons left" >search</i>BUSCAR</a>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
            <li class="collection-item dismissable">
                <table id="tablacategoria" class="striped responsive-table centered">
                    <thead>
                        <tr>
                            <th>Imagen</th>
                            <th>Codigo</th>
                            <th>Producto</th>
                            <th>Descripcion</th>
                            <th>Material</th>
                            <th>Color/Sabor</th>
                            <th>Talla/Tamaño</th>
                            <th>Marca</th>
                            <th>Stock</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Seleccionar</th>
                        </tr>
                    </thead>
                    <tbody id="resultadoprodcuto">
                    </tbody>
                </table>
            </li>
        </ul>
        <ul id="task-card" class="collection with-header">
            <li class="collection-header naranja" style="padding: 5px !important;">
                <h5 class="task-card-title"><strong>DETALLE DE LA VENTA</strong></h5>
            </li>
            <li class="collection-item">
                <table id="tabledetalle" class="striped responsive-table centered">
                    <thead>
                        <tr>
                            <th data-field="id">Imagen</th>
                            <th data-field="id">Producto</th>
                            <th data-field="name">Descripcion</th>
                            <th data-field="name">Color/Sabor</th>
                            <th data-field="price">Talla/Tamaño</th>
                            <th data-field="name">Cantidad</th>
                            <th data-field="price">Precio</th>
                            <th data-field="price">Subtotal</th>
                            <th data-field="price">Descuento</th>
                            <th data-field="price">Importe Descuento</th>
                            <th data-field="price">Total</th>
                            <th data-field="price">Asignar Descuento</th>
                            <th data-field="price">Editar</th>
                            <th data-field="price">Eliminar</th>
                            <th data-field="price">Info</th>
                        </tr>
                    </thead>
                    <tbody id="datos">
                        <tr></tr>         
                    </tbody>
                </table>
            </li>
            <li class="collection-item" >
                <div class="row">
                    <div class="col s12 m3 l3">
                        <ul id="issues-collection" class="collection" style="margin: 0px !important; border: 0px !important;">
                            <li class="collection-item avatar">
                                <i class="mdi-action-account-balance-wallet naranja circle"></i>
                                <h5>                                    
                                    <span class="collection-header" style="border-bottom: 0px !important;">TOTAL:</span>
                                    <span id="total"></span>                                    
                                </h5>
                            </li>
                        </ul>
                        <ul>
                            <li class="collection-item avatar">
                                <i class="mdi-action-trending-down naranja circle"></i>
                                <h5 >
                                    <span class="collection-header" style="border-bottom: 0px !important;">DESCUENTO%:</span>
                                    <span id="descuentoscliente"></span>
                                </h5>
                            </li>
                        </ul>
                    </div>
                    <div class="col s12 m6 l7">
                        <div class="row">
                            <div class="col s6 m6 l3">
                                <p><a class="waves-effect waves-light btn " href="#"  OnClick='cargarfactura();'>COBRAR</a></p>
                            </div>
                            <div class="col s6 m6 l3" >
                                @if($etapa == "proforma")
                                @else
                                <p><a class="btn" href="#" OnClick='cargarproforma();'>PROFORMA</a></p>
                                @endif
                            </div>
                            <div class="col s6 m6 l3">
                                <p><a class="btn" href="#" id="anularventa">ANULAR</a></p>
                            </div>
                            <div class="col s6 m6 l3 ">
                                <p><a class="btn" href="/listadeventa">VOLVER</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
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



<div id="productCredit" style="display: none;">
    <div class="row" id="datosVentas">
        <div class="col s12 m12 l12">
            <div class="card-panel">
                <h3 class="header2">Productos Contenidos Nro. <label id="credv"style="font-size: 40px;"></label></h3>
                <div class="row">
                    <div class="input-field col s12">
                        <a id="back" href="javascript:listarCreditosP();" class="waves-effect waves-green btn-flat" style="float: right; margin-top: -80px;">
                            <i class='mdi-navigation-arrow-back'></i>ATRAS
                        </a>
                        <div id="botonproductos">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <form class="col s12">
                        <!--div class="row" >
                            <div class="input-field col s6">
                                <i class="mdi-action-perm-identity prefix"></i>
                                <input id="clienteNombre" type="text" placeholder="" disabled style="color: black !important;">
                                <label for="first_name" style="color: black !important;">NOMBRE DEL COMBO</label>
                            </div>
                                                      
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <i class="mdi-editor-border-color prefix"></i>
                                <textarea id="observacionesventa" type="text" placeholder="" class="materialize-textarea" style="color: black !important;" disabled></textarea>
                                <label for="email" style="color: black !important;">OBSERVACIONES</label>
                            </div>
                        </div-->
                        <div class="row" style="margin-bottom: 20px;">
                            <div class="col s12">
                                <!--                                <div class="chip pink accent-2 white-text">
                                                                    <strong>A: Almacen</strong>
                                                                </div>
                                                                <div class="chip light-blue accent-3 white-text">
                                                                    <strong>M: Modista</strong>
                                                                </div>-->
                                <div class="chip light-green accent-3 white-text">
                                    <!--strong>E: Entregado</strong-->
                                </div>
                            </div>
                        </div>
                        <div class="row" id="productosdelcredito">

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@include('Modal.modalFactura')
@stop
@section('scripts')
{!! Html::script('js/addventa.js') !!}   
@endsection