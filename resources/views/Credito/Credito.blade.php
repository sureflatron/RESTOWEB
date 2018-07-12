@extends('Layouts.PanelVentas')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Lista de Creditos</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li class="active">Lista de Creditos</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
<input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
<input type="hidden" id="credito">
<div id="creditlist">
    <h3><strong>Listado de  Creditos</strong></h3>
    <table id="tablacategoria" class="centered display compact nowrap" cellspacing="0" width="100%">
        <thead>
        <th>ID</th>
        <th>Fecha</th>
        <th>Hora</th>
        <th>Cliente</th>
        <th>Facturado a:</th>
        <th>Total</th>
        <th>Cobrado</th>
        <th>Saldo</th>
        <!--<th>Fecha de Entrega</th>-->
        <th>Observaciones</th>
        <th>Cuotas</th>
        <th>Factura</th>
        <th>Productos</th>
<!--        <th>Editar</th>
        <th>Cancelar</th>-->
        </thead>
        <tfoot style=" display: table-header-group; background: white;">
        <th>ID</th>
        <th>Fecha</th>
        <th>Hora</th>
        <th>Cliente</th>
        <th>Facturado a:</th>
        <th>Total</th>
        <th>Cobrado</th>
        <th>Saldo</th>
        <!--<th>Fecha de Entrega</th>-->
        <th>Observaciones</th>
        <th></th>
        <th></th>
        <th></th>
<!--        <th></th>
        <th></th>-->
        </tfoot>
        <tbody id="datos">

        </tbody>
    </table>
</div>
<div id="credidetails" style="display: none;">
    <div class="row">
        <div class="col s12 m10 l10">
            <h3>Cobro de Cuotas del Credito: <label id="cred"style="font-size: 40px;"></label></h3>
        </div>
        <div class="col s12 m2 l2 right-align">
            <a id="back" href="javascript:listarCreditos();" class="waves-effect waves-green btn-flat right-aligned" style="float: right;"><i class='mdi-navigation-arrow-back'></i>ATRAS</a>
        </div>
    </div>
    <div class="divider"></div>
    <div class="row">
        <div class="col s12 m5 l5" id="procesodecobro">
            <div class="row">
                <div class="col s12 naranja bold white-text center-align">
                    <h5>COBRAR CREDITO</h5>
                </div>
            </div>
            <div class="row" >
                <div class="input-field col s12">
                    <i class="mdi-action-label-outline prefix"></i>
                    <label for="monto" class="active">INGRESE EL MONTO AL CANCELAR:</label>
                    <input id="monto" type="text" class="validate" placeholder="" onkeypress="return NumCheck(event, this)" onkeyup="calcularPago();">
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <i class="mdi-action-label prefix"></i>
                    <label for="cambio" class="active">CAMBIO:</label>
                    <input id="cambio" type="text" class="validate" placeholder="" style="color: black !important;" disabled="">
                </div>
            </div>
            <div class="row">
                <div class="col s12">
                    <h6><strong>FORMA DE PAGO</strong></h6>
                </div>
                <div class="col s12 m6 l6">
                    <p>
                        <input  class="group1" name="group1" type="radio" id="test1" value="contado"  checked/>
                        <label for="test1">Efectivo</label></p>
                    <p>
                        <input  class="group1" name="group1" type="radio" id="test2" value="tarjeta"/>
                        <label for="test2">Tarjeta Debito</label></p>
                    <p>
                        <input  class="group1" name="group1" type="radio" id="test3" value="tarjeta"/>
                        <label for="test3">Tarjeta Credito</label></p>
                </div>
                <div class="col s12 m6 l6">
                    <p>
                        <input  class="group1" name="group1" type="radio" id="test4" value="deposito"/>
                        <label for="test4">Deposito a Banco</label></p>
                    <p>
                        <input  class="group1" name="group1" type="radio" id="test5" value="cheque"/>
                        <label for="test5">Cheque</label></p>
                </div>
                <div class="row">
                    <div class="col s12 center-align">
                        <a class="btn waves-effect" href="javascript:guardarcobro();" id="save"><i class="mdi-editor-attach-money"></i>COBRAR</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col s12" id="listadodecuotasacobrar">
            <div class="row">
                <div class="col s12 naranja bold white-text center-align">
                    <h5>LISTA DE CUOTAS</h5>
                </div>
            </div>
            <div class="col s12" style="padding-top: 20px;">
                <input type="hidden" id="saldo">
                <table id="tablacuotas" class="responsive-table centered striped bordered">
                    <thead>
                    <th>Fecha Vencimineto</th>
                    <th>Total Cuota</th>
                    <th>Total Cobrado</th>
                    <th>Saldo</th>
                    <th>Cobrar</th>
                    <th>Historico</th>
                    </thead>
                    <tbody id="data"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div id="productCredit" style="display: none;">
    <div class="row" id="datosVentas">
        <div class="col s12 m12 l12">
            <div class="card-panel">
                <h3 class="header2">Productos en la Venta Nro. <label id="credv"style="font-size: 40px;"></label></h3>
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
                        <div class="row" >
                            <div class="input-field col s6">
                                <i class="mdi-action-perm-identity prefix"></i>
                                <input id="clienteNombre" type="text" placeholder="" disabled style="color: black !important;">
                                <label for="first_name" style="color: black !important;">NOMBRE DEL CLIENTE</label>
                            </div>
                            <!--                            <div class="input-field col s6" >
                                                            <i class="mdi-action-event prefix"></i>
                                                            <input id="fechaEntrega" type="text" placeholder="" disabled style="color: black !important;">
                                                            <label for="last_name" style="color: black !important;">FECHA DE ENTREGA</label>
                                                        </div>-->
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <i class="mdi-editor-border-color prefix"></i>
                                <textarea id="observacionesventa" type="text" placeholder="" class="materialize-textarea" style="color: black !important;" disabled></textarea>
                                <label for="email" style="color: black !important;">OBSERVACIONES</label>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 20px;">
                            <div class="col s12">
                                <!--                                <div class="chip pink accent-2 white-text">
                                                                    <strong>A: Almacen</strong>
                                                                </div>
                                                                <div class="chip light-blue accent-3 white-text">
                                                                    <strong>M: Modista</strong>
                                                                </div>-->
                                <div class="chip light-green accent-3 white-text">
                                    <strong>E: Entregado</strong>
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
@include('Modal.modalCredito')
@stop
@section('scripts')
{!! Html::script('js/addcredito.js') !!}   
@endsection