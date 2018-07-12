@extends('Layouts.PanelVentas')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">
                    <strong>Editar Proforma</strong> - 
                    <strong>Sucursal:</strong>{!! Session::get('sucursal') !!} &nbsp; &nbsp; || &nbsp; &nbsp; 
                    <strong>Codigo Proforma:</strong> {!!$id !!}  &nbsp; &nbsp; || &nbsp; &nbsp;
                    <strong>Estado:</strong> {!!$actual !!}  &nbsp; &nbsp; || &nbsp; &nbsp;
                    <strong>Fecha:</strong>{!!$fecha !!}&nbsp; &nbsp; || &nbsp; &nbsp;
                    <strong>Almacen:</strong><span id="nombrealmacenventa">{!! $a !!}</span></h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li><a href="/listadeventa">Listado de Ventas POS</a></li>
                    <li class="active">Editar Venta POS</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
@include('Modal.modalProforma')
<input type="hidden"  id="idproducto"> 
<input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
<input type="hidden" value="{{$id}}" id="venta"> 
<input type="hidden" value="{{$idm}}" id="idmoneda"> 
<input type="hidden" id="almacen" value="{{ $idalmacen }}">
@if( $idCliente == null)
<input type="hidden" id="idCliente">
@else
<input type="hidden" id="idCliente" value="{{$idCliente}}" > 
@endif
<div class="row">
    <div class="col s12 m4 l4">
        <div class="row">
            <div class="input-field col s10">
                <i class="mdi-action-account-circle prefix"></i>
                <label for="cliNombre">SELECCIONE UN CONCESIONARIO</label>
                @if( $idCliente == null)
                <input type="text" list="clienteslist" name="buscar_cliente" autocomplete="off" class="input-field" id="cliNombre" required placeholder="">
                @else
                <input type="text" list="clienteslist" name="buscar_cliente" autocomplete="off" class="input-field" id="cliNombre" value="{{$nombre}}" required placeholder="">
                @endif
                <datalist id="clienteslist">
                    @foreach($clientes as $datos)
                    <option data-id='{{ $datos->id }}' value="{{ $datos->nombre }}"> NIT: {{ $datos->nit }}
                        @endforeach
                </datalist>
            </div>
            <div class="col s1">
                <br>
                <a class="waves-effect waves-light btn modal-trigger tooltipped btn-floating" href="#modal2" id="agregarcliente" data-position="right" 
                   data-delay="50" data-tooltip="Nuevo Concesionario">
                    <i class="material-icons">assignment_ind</i>
                </a>
            </div>
            <div class="col s1">
                <br>
                <a style ='display:none;' class="waves-effect waves-light btn modal-trigger btn-floating tooltipped" href="#modalAct" id="actualizarcliente" data-position="right" 
                   data-delay="50" data-tooltip="Actualizar Concesionario">
                    <i class="material-icons">mode_edit</i>
                </a>
            </div>
        </div>
    </div> 
    @if ($p == "0")
    <div class="col s12 m4 l4">
        <label for="almacencombo">SELECCIONE EL ALMACEN PARA LA PROFORMA</label>
        <select id="almacencombo"class="browser-default">
            <option>Cambiar Almacen a</option>
        </select>
    </div>
    @endif
    <div class="col s12 m4 l4 center-align" id="generadordeventa" style='display:none ;'>
        <a  class="btn waves-effect waves-light animated infinite jello" href="#" id="generarventa">
            <i class="mdi-action-autorenew"></i>NUEVA PROFORMA</a>
    </div>
</div>
<div class="row">
    <div class="grid-example col s12">
        <span class="flow-text"><strong>NUEVA PROFORMA</strong></span>
    </div>
    <div class="col s12">
        <div class="row">
            <div class="input-field  col s12 m6 l3">
                <i class="mdi-action-view-column prefix"></i>
                <input id="codigoBarra" type="text" class="validate"  onchange="codigobarraagregardetalle()" placeholder="" onkeypress="return isNumberKey(this);">
                <label for="codigoBarra">Codigo de Barras</label>
            </div>
            <div class="input-field col s12 m6 l3">
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
                        <a class="waves-effect waves-light btn " style="display: block; justify-content: space-between;" id="buscarproducto" onclick="buscarproductoinput()"><i class="material-icons left">search</i>BUSCAR</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col s12">
        <div class="row">
            <div class="col s12 m12 l12">
                <table id="tablebusqueda" class="striped responsive-table centered">
                    <thead>
                        <tr>
                            <th>Imagen</th>
                            <th>Codigo</th>
                            <th>Producto</th>
                            <th>Descripcion</th>
                            <th>Material</th>
                            <th>Color/Sabor</th>
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
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col s12" id="detventa">
            <div class="grid-example col s12" style="text-align: center;">
                <span><strong>DETALLE DE PROFORMA</strong></span>
            </div>
            <div class="row">
                <div class="col s12 m12 l12">
                    <table id="tabledetalle" class="striped responsive-table centered">
                        <thead>
                            <tr>
                                <th data-field="id">Imagen</th>
                                <th data-field="id">Producto</th>
                                <th data-field="name">Descripcion</th>
                                <th data-field="name">Color/Sabor</th>
                                <th data-field="name">Cantidad</th>
                                <th data-field="price">Precio</th>
                                <th data-field="price">Subtotal</th>
                                <th data-field="price">Descuento</th>
                                <th data-field="price">Importe Descuento</th>
                                <th data-field="price">Total</th>
                                <th data-field="price">Asignar Descuento</th>
                                <th data-field="price">Editar</th>
                                <!--<th data-field="price">Chasis</th>-->
                                <th data-field="price">Eliminar</th>
                            </tr>
                        </thead>
                        <tbody id="datos">        
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col s2 m2 l1">
                <h5><strong>Total:</strong></h5>
            </div> 
            <div class="col s6 m6 l3">
                <h5 id="total">0</h5>
            </div> 
        </div>
    </div>
</div>
<center>
    <div class="row">
        <div class="col s6 m3 l3">
            <p><a class="waves-effect waves-light btn modal-trigger" href="#modal1"  OnClick='cargarfactura();'>COBRAR ANTICIPADO</a></p>
        </div>
        <div class="col s6 m3 l3" >
            @if($etapa == "proforma")

            @else
            <!--<p><a class="btn" href="#" id="proforma">PROFORMA</a></p>-->
            @endif
        </div>
        <div class="col s6 m3 l3">
            <p><a class="btn" href="#" id="anularventa">ANULAR</a></p>
        </div>
        <div class="col s6 m3 l3">
            <p><a class="btn" href="/listadeproforma/">VOLVER</a></p>
        </div>
    </div>
</center>
@stop
@section('scripts')
{!! Html::script('js/addproforma.js') !!} 
@endsection
