@extends('Layouts.PanelVentas')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Editar Inventario - Sucursal: {!! Session::get('sucursal') !!}</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li><a href="/gestionarinventario">Listado de invenatarios</a></li>
                    <li class="active">Editar Inventario</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
@include('Modal.modalinventario')
<input type="hidden"  id="idproducto"> 
<input type="hidden" name="_token" value="{{csrf_token()}}" id="token">
<input type="hidden" value="{!!$fecha!!}" name="" id="fechas">
<input type="hidden" value="{{$id}}" id="venta"> 
<input type="hidden" value="{{$idalmacen}}" id="almaceninv"/>
<input type="hidden" value="{{$idalmacendestino}}" id="almaceninvdestino"/>
<div class="row">
    <div class="col s12 grid-example">
        <h5>
            <strong>CODIGO INVENTARIO:</strong>&nbsp;&nbsp;{!!$id!!}
        </h5>
    </div>
    <div class="col s12">
        <div class="row">
            <div class="col s12">
                <h5><i class="mdi-action-today"></i>Fecha: *</h5>
                <input type="date" value="" name="" id="fecha" class="datepicker">
            </div>
            <div class="col s12">
                <h5><i class="mdi-action-tab"></i>Tipo Inventario: *</h5>
                <select id="tipo">
                    <option value="Egreso">Egreso</option>
                    <option value="Ingreso">Ingreso</option>
                    <option value="Traspaso">Traspaso</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col s12">
        <div class="row">
            <div class="col s12" id='origen'>
                <h5><i class="mdi-social-domain"></i>Almacen Origen: *</h5>
                <div class="select">
                    {!! Form::select('Almacen', $Almacen,null, ['id'=>'Almacen','class'=>'excluyent-select','name'=>'Almacen']) !!}
                </div>
            </div>
            <div class="col s12" id='destino'>
                <h5><i class="mdi-social-domain"></i>Almacen Destino: *</h5>
                <div class="select">
                    {!! Form::select('Almacen', $Almacen,null, ['id'=>'AlmacenDestino','class'=>'excluyent-select','name'=>'AlmacenDestino']) !!}
                </div>
            </div>
            <div class="col s12">
                <h5><i class="mdi-editor-border-color"></i>Motivo de Movimiento: *</h5>
                {!! Form::select('motivo', $motivo,null, ['id'=>'motivo']) !!}
            </div>
        </div>
    </div>
    <div class="col s12">
        <div class="row">
            <div class="col s12">
                <h5><i class="mdi-editor-mode-edit"></i>Glosa:</h5>
                <textarea id="glosa" class="materialize-textarea"></textarea>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col s12 grid-example" >
        <h5><strong>BUSCAR PRODUCTO</strong></h5>
    </div>
    <div class="col s12">
        <div class="input-field col s12 m6 l3">
            <i class="mdi-action-view-column prefix"></i>
            <input id="codigoBarra" type="text" class="validate" onchange="codigobarraagregardetalle()" placeholder="">
            <label for="codigoBarra">CODIGO DE BARRAS</label>
        </div>
        <div class="input-field col s12 m6 l3">
            <i class="mdi-action-label-outline prefix"></i>
            <input id="codigoInterno" type="text" class="validate" placeholder="">
            <label for="codigoInterno">CODIGO INTERNO</label>
        </div>
        <div class="input-field col s12 m6 l3">
            <i class="mdi-action-search prefix"></i>
            <input id="nombreproducto" type="text" class="validate" placeholder="">
            <label for="nombre">NOMBRE</label>
        </div>
        <div class="input-field col s12 m6 l3 center-align">
            <a class="waves-effect waves-light btn" id="buscarproducto"><i class="material-icons left">search</i>BUSCAR</a>
        </div>
    </div>
    <div class="col s12">
        <h5 class="center-align"><strong>Resultado de Busqueda</strong></h5>
    </div>
    <table class="striped responsive-table centered col s12">
        <thead>
            <tr>
                <th>Imagen</th>
                <th>Codigo</th>
                <th>Producto</th>
                <th>Descripcion</th>
                <th>Origen</th>
                <th>Material</th>
                <th>Color</th>
                <th>Talla/Tamaño</th>
                <th>Marca</th>
                <th>Stock</th>
                <th>Precio Venta</th>
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
<div class="row">
    <div class="col s12 grid-example" id="det">
        <h5><strong>DETALLE DE INVENTARIO</strong></h5>
    </div> 
    <table id="tablacategoria"  class="centered display compact nowrap col s12" cellspacing="0" width="100%">
        <thead>   
        <th>Imagen</th>
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
        <td>
        </td>
        </tbody>
    </table>
</div>
<div class="col s12">
    <a href="#" id="guardaryimprimir" class="btn">GUARDAR E IMPRIMIR</a>
    <a href="/gestionarinventario"   class="btn">Volver</a>
</div>

@stop
@section('scripts')
{!! Html::script('js/addinventarionueva.js') !!}   
@endsection