@extends('Layouts.PanelVentas')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">
                    <strong>Editar Alquiler</strong>&nbsp; &nbsp; || &nbsp; &nbsp; 
                    <strong>Codigo Alquiler:</strong> {!!$id !!}  &nbsp; &nbsp; || &nbsp; &nbsp;
                    <strong>Fecha:</strong>{!!$fecha !!}&nbsp; &nbsp; || &nbsp; &nbsp;
                    <strong>Moneda:</strong>{!! $moneda !!}
                    <strong>Esta a:</strong>{!! $bs !!}&nbsp; &nbsp; || &nbsp; &nbsp;
                    <strong>Almacen:</strong><span id="nombrealmacenventa">{!! $almacen !!}</span></h5>
                </h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li><a href="/listaralquiler">Lista de Alquiler</a></li>
                    <li class="active">Editar Alquiler</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
@include('Modal.modalAlquiler')
<input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
<input type="hidden" name="idalmacen" value="{{ $idalmacen }}" id="idalmacen">
<input type="hidden" name="idventa" value="{{ $id }}" id="idventa">
<input type="hidden" name="idCliente" value="" id="idCliente">
<div class="container">
    <div class="row">
        <div class="col s12 m4 l4">
            <div class="row">
                <div class="input-field col s10">
                    <i class="mdi-action-account-circle prefix"></i>
                    <label for="cliNombre">SELECCIONE UN CLIENTE</label>
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
                    <a class="waves-effect waves-light btn modal-trigger tooltipped btn-floating" href="#modal3" id="agregarcliente" data-position="right" 
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
            </div>
        </div>
        @if ($p == "0")
        <div class="col s12 m4 l4">
            <label for="almacencombo">SELECCIONE EL ALMACEN PARA LA VENTA</label>
            <select id="almacencombo"class="browser-default">
                <option>Cambiar Almacen a</option>
            </select>
        </div>
        @endif
        <div class="col s12 m4 l4 center-align">
            <div class="col s12" id="generadordeventa" style='display:none;'>
                <p><a class="btn animated infinite tada" href="#" id="generarventa" >NUEVO ALQUILER</a></p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col s12 m6">
            <div class="grid-example col s12" style="border-radius: 5px;">
                <span class="white-text"><strong>BUSCAR PRODUCTOS</strong></span>
            </div>
            <div class=" col s12 grey lighten-5" style="border-radius: 5px;">
                <div class="row">
                    <div class="input-field col s12 m12 l6">
                        <i class="mdi-action-view-week prefix"></i>
                        <input class="input-field" type="text" value="" id="barcode" placeholder="" onkeyup="buscarcodigodebarra();">
                        <label for="barcode">Codigo de Barra:</label>
                    </div>
                    <div class="input-field col col s12 m12 l6">
                        <i class="mdi-action-search prefix"></i>
                        <input class="input-field" type="text" value="" id="nombrepro" placeholder="" onkeyup="buscarnombre();">
                        <label for="nombre">Nombre de Producto:</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12">
                    <table class="bordered striped responsive-table centerd" id="servicios">
                        <thead>
                        <th>Imagen</th>
                        <th>Codigo de Barra</th>
                        <th>Nombre</th>
                        <th>Descripcion</th>
                        <th>Precio Alquiler</th>
                        <th>Agregar</th>
                        </thead>
                        <tbody id="datos">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col s12 m6">
            <div class="grid-example col s12" style="border-radius: 5px;">
                <span class="white-text"><strong>PRODUCTOS SELECCIONADOS</strong></span>
            </div>
            <div class="row" id="productosalquilados">
            </div>
        </div>
    </div>
    <div class="fixed-action-btn" style="bottom: 50px; right: 19px;">
        <a class="btn tooltipped waves-effect btn-floating btn-large animated infinite bounce" href="#" data-position="left" 
           data-delay="50" data-tooltip="COBRAR"
           onclick="javascript:cargardatos();">
            <i class="mdi-action-payment"></i>
        </a>
    </div>
</div> 
@stop
@section('scripts')
{!! Html::script('js/addalquiler.js') !!}
@endsection