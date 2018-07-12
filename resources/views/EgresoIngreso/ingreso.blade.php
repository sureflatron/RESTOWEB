@extends('Layouts.Panel')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Listado de Ingresos</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li class="active">Listado de Ingresos</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
@include('Modal.modalingreso')
<div class="container">
    <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
    <h3><strong>Listado de  Ingreso</strong></h3>
    <!-- Modal Trigger -->
    <div class="row" style="padding-bottom: 20px;">
        <div class="col s12">       
            <a class="btn" href="#" id="nuevo">NUEVO INGRESO</a>
        </div>
    </div>
    <table  id="tablacategoria" class="centered display compact nowrap" cellspacing="0" width="100%">
        <thead>
        <th>ID</th>
        <th>Fecha</th>
        <th>Hora</th>
        <th>Estado</th>
        <th>Recibido de</th>
        <th>Importe</th>
        <th>Forma de Pago</th>
        <th>Registrado</th>
        <th>Tipo Ingreso</th>
        <th>Editar</th>
        <th>Eliminar</th>
        <th>Imprimir</th>
        </thead>
        <tfoot style=" display: table-header-group; background: white;">
        <th>ID</th>
        <th>Fecha</th>
        <th>Hora</th>
        <th>Estado</th>
        <th>Recibido de</th>
        <th>Importe</th>
        <th>Forma de Pago</th>
        <th>Registrado</th>
        <th>Tipo Ingreso</th>        
        <th></th>
        <th></th>
        <th></th>
        </tfoot>
        <tbody id="datos">
        </tbody>
    </table>  
</div>
<div class="divider"></div>

@stop
@section('scripts')
{!! Html::script('js/addlistaringreso.js') !!}
@endsection
