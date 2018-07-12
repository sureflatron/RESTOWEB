@extends('Layouts.PanelVentas')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Listado de Compras a Creditos</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li class="active">Listado de Compras a Credito</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
@include('Modal.modalCompra')
<input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
<h3><strong>Listado de  Creditos</strong></h3>
<div id="tablacont">
    <table class="centered" id="tabla">
        <thead>      
        <th>IdCompra</th>
        <th>Fecha</th>
        <th>Hora</th>
        <th>Proveedor</th>
        <th>Total</th>
        <th>Pagado</th>
        <th>Saldo</th>
        <th>Cuotas</th> 
        </thead>
        <tbody id="datos">
        </tbody>
    </table>
</div>
<div id="credidetails" style="display: none;">
    <h5>Cobro de Cuotas del Credito: <label id="cred"style="font-size: 25px;"></label></h5>
     <div class="row" >
        <div class="col s12">
            <div class="input-field col s12 right-align">
                <a id="back" href="javascript:listarCreditos();" class="waves-effect waves-green btn-flat right-aligned" style="float: right;"><i class='mdi-navigation-arrow-back'></i>ATRAS</a>
            </div>
        </div>
    </div>
    <div class="row" style="padding-top: 20px;">
      <input type="hidden" id="saldo">
        <table id="tablacuotas" class="centeres">
            <thead>
            <th>Fecha Vencimineto</th>
            <th>Total Cuota</th>
            <th>Total Cobrado</th>
            <th>Saldo</th>
            <th>Cobrar</th>
            <th>Historico</th>
            </thead>
            <tbody id="data">
            </tbody>
        </table>

          <div id="contentdiv" class="card" style="display: none;">
            <div class="card-content">
                <div class="col s12 m4 l4 right-align offset-l8 offset-m8" style="background: white; padding: 20px; border-radius: 10px;">
                    <div class="row">
                        <div class="col s12 center-align">
                            <h5 class="card-title"><Strong>COBRAR CUOTA</Strong></h5>
                        </div>
                    </div>
                    <div class="row left-align card-content">
                        <input type="hidden" id="cuotaapagarcredito">
                        <div class="input-field col s12">
                            <i class="mdi-action-label prefix"></i>
                            <label for="importecuota">IMPORTE DE LA CUOTA:</label>
                            <input type="text" id="importecuota" placeholder="" disabled style="color: black !important;">
                        </div>
                        <div class="input-field col s12">
                            <i class="mdi-action-label-outline prefix"></i>
                            <label for="saldocuota">SALDO:</label>
                            <input type="text" id="saldocuotass" placeholder="" disabled style="color: black !important;">
                        </div>
                        <div class="input-field col s12">
                            <i class="mdi-action-payment prefix"></i>
                            <label for="importepagar">IMPORTE A PAGAR:</label>
                            <input type="text" id="importepagar" placeholder="">
                        </div>
                        <div class="col s12">
                            <div class="row">
                                <div class="col s12">
                                    <h6><strong>FORMA DE PAGO</strong></h6>
                                </div>
                                <div class="col s12 m6 l6">
                                    <p>
                                        <input  class="group1" name="group1" type="radio" id="test1" value="contado"  checked/>
                                        <label for="test1">Efectivo</label></p>
                                   
                                </div>
                               
                            </div>
                        </div>
                        <br>
                        <div class="col s12 center-align">
                            <a class="btn waves-effect" href="javascript:guardarcobro();" id="save"><i class="mdi-editor-attach-money"></i>COBRAR</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('scripts')
{!! Html::script('js/addcreditoscompras.js') !!}   
@endsection