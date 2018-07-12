@extends('Layouts.Panel')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Listado de Cuentas Bancarias</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li class="active">Listado de Cuentas Bancarias</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
@include('Modal.modalCuentaBancaria')
<h3><strong>Listado de Cuentas Bancarias</strong></h3>
<div class="row" style="padding-bottom: 20px;">
    <div class="col s12">
        <a class="waves-effect waves-light btn modal-trigger" href="#modal1">NUEVA CUENTA BANCARIA</a>
    </div>
</div>
<table id="tablacategoria" class="centered display compact nowrap" cellspacing="0" width="100%">
    <thead>
    <th>Banco</th>
    <th>Nro. de Cuenta</th>
    <th>Razon Social/Titular</th>
    <th>Tipo de Cuenta</th>
    <th>Moneda</th>
    <th>Editar</th>
    <th>Eliminar</th>
</thead>
<tfoot style=" display: table-header-group; background: white;">
<th>Banco</th>
<th>Nro. de Cuenta</th>
<th>Razon Social/Titular</th>
<th>Tipo de Cuenta</th>
<th>Moneda</th>
<th></th>
<th></th>
</tfoot>
<tbody id="datos">
</tbody>
</table>

@stop
@section('scripts')
{!! Html::script('js/addcuentabancaria.js') !!}   
@endsection