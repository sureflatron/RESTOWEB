@extends('Layouts.PanelVentas')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Planilla de Gastos</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li><a href="/Gestionarcompras">Lista de Compras</a></li>
                    <li class="active">Planilla de Gastos</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
@include('Modal.modaladdPlanilaGasto')

<div class="row">
    <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
    <input type="hidden" value="{{$idCompra}}" id="idcompra"> 
    <input type="hidden" value="" id="idGasto"> 

    <center><h4><strong>Planilla de Gastos</strong></h4></center>   
    <div class="divider"></div>
    <div class="row">
        <div class="input-field col s12 m4 l6">
            <strong>Compra Nro :&nbsp; &nbsp;{{$idCompra}}</strong>
        </div>
        <div class="input-field col s12 m4 l6">
            <strong>Fecha:&nbsp; &nbsp;{{$fecha}}</strong>
        </div>

    </div>
    <div class="row">

        <div class="input-field col s12 m6 l6">
            <span>Proveedor:</span>
            <!--{!! Form::select('Proveedor', $proveedor,null, ['id'=>'proveedor','placeholder'=>'Seleccione un Proveedor'])!!}-->

            <select id="proveedor">
            </select>

        </div>
        <div class="input-field col s12 m6 l6">
            <span>Gasto:</span>
            <!--                    @php
                                //dd($gasto);
                                if ( empty($gasto)){
                                $gasto =[''=>"Seleccione una opcion"];
                                }
                                @endphp-->
            <select id="gastoCompra">
            </select>

            <!--{!! Form::select('Gasto', $gasto,null, ['id'=>'gastoCompra'])!!}-->
        </div>
        <div class="input-field col s12 m6 l6">
            <span>Forma de Pago:</span>
            <select id="concepto">
            </select>
            <!--{!! Form::select('Forma de Pago', $concepto,null, ['id'=>''])!!}-->
        </div>
        <div class="input-field col s12 m6 l6">
            <span>Importe:</span>
            {!!Form::text('Importe',null, ['id'=>'importe'])!!}
        </div>
    </div>
    <div class="row">
        <div class="input-field col s12 m6 l6" id="cuentabanco" style="display: none">
            <span>Nro de Cuenta Bancaria:</span>
            <select id="cuentaBancaria">
            </select>
        </div>
        <div class="input-field col s12 m6 l6" id="cuentacheque" style="display: none">
            <span>Nro de Cuenta:</span>
            {!!Form::number('Cheque',null, ['id'=>'cheque','placeholder'=>'Numero de Cuenta'])!!}

        </div>
        <div class="input-field col s12 m6 l6">
            <span>Afecta el Gasto:</span>
            <div class="row">
                <div class="input-field col s12 m6 l6">
                    <input name="group1" type="radio" id="test1" checked />
                    <label for="test1"><strong>SI</strong></label>
                </div>
                <div class="input-field col s12 m6 l6">
                    <input name="group1" type="radio" id="test2" />
                    <label for="test2"><strong>NO</strong></label>
                </div>
            </div>
        </div>


    </div>
</div>

<div class="row">
    <center>
        <div class="row" style="padding-bottom: 20px;">
            <div class="col s12">   
                <a class="btn" href="#" id="agregarGasto">AGREGAR</a>
            </div>
        </div>
    </center>
</div>


<div class="row">
    <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
    <h3><strong>Listado</strong></h3>
    <table class="centered display compact nowrap" cellspacing="0" width="100%" id="tablaGasto">
        <thead>
        <th>Fecha</th>
        <th>Hora</th>
        <th>Gasto</th>
        <th>Proveedor</th>
        <th>Forma de Pago</th>
        <th>Importe</th>
        <th>Afecta Costo</th>
        <th>Editar</th>
        <th>Eliminar</th>
        </thead>
        <tfoot style=" display: table-header-group; background: white;">
        <th>Fecha</th>
        <th>Hora</th>
        <th>Gasto</th>
        <th>Proveedor</th>
        <th>Forma de Pago</th>
        <th>Importe</th>
        <th>Afecta Costo</th>

        <th></th>
        <th></th>
        </tfoot>
        <tbody id="datosGasto">

        </tbody>
    </table>
</div>
<br>
<div class="row">
    <center>
        <div class="row" style="padding-bottom: 20px;">
            <div class="col s6">   
                <a class="btn" href="#" id="generarPlanillaGasto">GENERAR PLANILLA GASTO</a>
            </div>
            <div class="col s6">   
                <a class="btn" href="/Gestionarcompras" id="">VOLVER</a>
            </div>
        </div>
    </center>
</div>

@stop
@section('scripts')
{!! Html::script('js/addcompras.js') !!}   
@endsection