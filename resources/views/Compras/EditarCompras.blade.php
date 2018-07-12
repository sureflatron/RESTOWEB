@extends('Layouts.PanelVentas')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Editar Compra - Sucursal {!! Session::get('sucursal') !!}</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li><a href="/Gestionarcompras">Listado de Compras</a></li>
                    <li class="active">Editar Compra</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
@include('Modal.modaladdcompras')
<input type="hidden"  id="idproducto"> 
<input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
<input type="hidden" value="{!!$fecha !!}" name="" id="fechas">
<input type="hidden" value="{{$id}}" id="venta">
<div class="row">
    <div class="col s12 grid-example">
        <h5><strong>Codigo Compra:</strong>  {!!$id !!}  </h5>
    </div>
    <div class="col s12">
        <div class="row">
            <div class="col s12 m12 l4">
                <h5><i class="mdi-action-today"></i>Fecha: *</h5>
                <input type="date" value="" name="" id="fecha" class="datepicker"> 
            </div>
            <div class="col s12 m12 l4">
                <h5><i class="mdi-action-tab"></i>Almacen: *</h5>
                {!! Form::select('almacen', $almacen,null, ['id'=>'almacen']) !!}
            </div>
            <div class="col s12 m12 l4">
                <h5><i class="mdi-editor-border-color"></i>Proveedor: *</h5>
                {!! Form::select('proveedor', $proveedor,null, ['id'=>'proveedor']) !!}
            </div>
        </div>
        <div class="row">
           <div class="col s9 m9 l9">
            <h5><i class="mdi-editor-mode-edit"></i>Glosa:</h5>
            <textarea type="text" id="glosa" class="materialize-textarea"
                       placeholder="Introduzca una descripcion en la Orden De Compra"></textarea>
           </div> 
            <div class="col s3 m3 l3">
            <h5><i class="mdi-editor-mode-edit"></i>TiempoDeOrden:</h5>
            </br>
            </br>
            
            <input type="number" id="tiempodeorden" class="materialize-textarea" 
                   placeholder="Dias que tarda la orden"/>
           </div> 
        </div>
    </div>
</div>

<div class="row">
    <div class="col s12 grid-example">
        <h5><strong>BUSCAR PRODUCTO</strong></h5>
    </div>
    <div class="row">
        <div class="input-field col s3">
            <i class="mdi-action-view-column prefix"></i>
            <input id="codigoBarra" type="text" class="validate" onchange="codigobarraagregardetalle()" placeholder="">
            <label for="codigoBarra">CODIGO DE BARRAS</label>
        </div>
        <div class="input-field col s3">
            <i class="mdi-action-label-outline prefix"></i>
            <input id="codigoInterno" type="text" class="validate" placeholder="">
            <label for="codigoInterno">CODIGO INTERNO</label>
        </div>
        <div class="input-field col s3">
            <i class="mdi-action-search prefix"></i>
            <input id="nombreproducto" type="text" class="validate" placeholder="">
            <label for="nombre">NOMBRE</label>
        </div>
        <div class="input-field col s3 center-align">
            <a class="waves-effect waves-light btn" id="buscarproducto"><i class="material-icons left">search</i>BUSCAR</a>
        </div>
    </div>
    <div class="col s12">
        <h5 style="text-align: center;"><strong>Resultado de Busqueda</strong></h5>
    </div>
    <div class="col s12">
        <table class="responsive-table centered dotted">
            <thead>
                <tr>
                    <th>Codigo Interno</th>
                    <th>Producto</th>
                    <th>n_pedidos</th>
                    <!--th>Material</th-->
                    <!--th>Color</th-->
                    <th>S_MAX</th>
                    <th>S_Min</th>
                    <th>Q_P_D</th>
                    <th>Stock_A</th>
                    <!--th>Talla/Tamaño</th-->
                    <th>Marca</th>
                    <th>Unidad de Medidad</th>
                    <th>Cantidad</th>
                    <th>Costo</th>
                    <th>Seleccionar</th>
                </tr>
            </thead>
            <tbody id="resultadoprodcuto">
            </tbody>
        </table>
    </div>
</div>
<div class="row">
    <div class="col s12 grid-example" id="det">
        <h5><strong>DETALLE DE COMPRA</strong></h5>
    </div>
    <div class="col s12">
        <table  id="tablacategoria" class="centered" >
            <thead>
            <th>Producto</th>
            <th>Descripcion</th>
            <th>Color</th>
            <th>Talla/Tamaño</th>
            <th>Marca</th>  
            <th>cantidad</th>
            <th>Costo</th>
            <th>SubTotal</th>
            <th>Editar</th>
            <th>Eliminar</th>
            </thead>
            <tbody id="datos">
            <td></td>
            </tbody>
        </table>
    </div>
</div>
<div class="row"> 
    <div class="col s12 m6 l3">
        <h5><Strong>Total: </strong>
            <span id="total">0</span></h5>
    </div>
</div>
<form action="#">
    <p>
        <input name="group1" type="radio" id="test1" onclick="ocultar();"  />
        <label for="test1">Contado</label>
        <input name="group1" type="radio" id="test2" onclick=" mostrar();"  />
        <label for="test2">Credito</label>
        <input name="group1" type="radio" id="test3" onclick="ocultar();"  />
        <label for="test3">Tarjeta Debito</label>
        <input name="group1" type="radio" id="test4" onclick="ocultar();"  />
        <label for="test4">Tarjeta Credito</label>
        <input name="group1" type="radio" id="test5" onclick="ocultar();"  />
        <label for="test5">Deposito a banco</label>
        <input name="group1" type="radio" id="test6" onclick="ocultar();"  />
        <label for="test6">Cheque</label>
    </p>
</form>

<div id="pagoCredito" class="row" style="display:  none;">
    <div class="input-field col s6">
        {!!Form::label('A Cuenta','A Cuenta: ')!!}
        <input type="number" value="0" id='aCuenta' onkeypress='return NumCheck(event, this);' onKeyUp="calcular_totalCredito();"/>
    </div>
    <div class="input-field col s6">
        {!!Form::label('Saldo','Saldo: ')!!}
        <input disabled type="number" value="0" id='saldo'/>
    </div>
    <div class="input-field col s6">
        {!!Form::label('Numero de Cuotas','Numero de Cuotas: ')!!}
        <input type="number" value="0" id='nrCuotas' onkeypress='return isNumberKey(this);'/>
    </div>
    <div class="input-field col s6">
        {!!Form::label('Cobro Cada','Cobro Cada: ')!!}
        <input type="number" value="0" id='cobrarCada' onkeypress='return isNumberKey(this);'/>
    </div>
</div>
<div class="col s12">
    <a href="#" id="actualizaryimprimir" class="btn">GUARDAR E IMPRIMIR</a>
    <a href="/Gestionarcompras"   class="btn">Volver</a>
</div>


@stop
@section('scripts')
{!! Html::script('js/addcomprasnueva.js') !!} 
@endsection