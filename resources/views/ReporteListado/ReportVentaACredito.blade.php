@extends('Layouts.Panel')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Reporte de Ventas a Credito</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li class="active">Reporte de Ventas a Credito</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
<div class="row">
    <div class="col s12">
        <h4><strong>Reporte de Ventas a Credito</strong></h4>
    </div>
</div>
<div class="row">
    <div class="col s12 m6 l6">
        <p>
            <input name="group1" type="radio" id="test1" checked/>
            <label for="test1">Exportar a PDF</label>
        </p>
        <p>
            <input name="group1" type="radio" id="test2"/>
            <label for="test2">Exportar a Excel</label>
        </p>
    </div>
    <div class="col s12 m6 l6">
        <p>
            <input class="group2" name="group2" type="radio" id="test3" value="credito" checked/>
            <label for="test3">Generar Reportes de Ventas al Credito</label>
        </p>
        <p>
            <input class="group2" name="group2" type="radio" value="atrazo" id="test4" />
            <label for="test4">Generar Reporte de Cuotas retrasadas a la fecha</label>
        </p>
        <p>
            <input class="group2" name="group2" type="radio" id="test5" value="ambos"/>
            <label for="test5">Consolidado</label>
        </p>
    </div>
</div>
<div class="row" id="filtrosfecha">
    <div class="input-field col s12 m6 l6">
        <i class="mdi-editor-insert-invitation prefix"></i>
        <label for='fechainicios'>Fecha de Inicio</label>
        <input type="date" name="" id="fechainicios" class="datepicker" placeholder="">
    </div>
    <!--</div>
    <div class="row">-->
    <div class=" input-field col s12 m6 l6">
        <i class="mdi-editor-insert-invitation prefix"></i>
        <label for='Fechafins'>Fecha de Fin</label>
        <input type="date" name="" id="Fechafins" class="datepicker" placeholder="">
    </div>
</div>
<div class="row">
    <div class="col s12 m6 l6">
        <label for="empleado">Lista de Empleados:</label>
        <select id="empleado" class="browser-default"> 
        </select>
    </div>
    <!--</div>
    <div class="row">-->
    <div class="col s12 m6 l6">
        <label for="almacen">Lista de Sucursales:</label>
        <select id="almacen" class="browser-default"><option value="null">Selecione un Almacen</option></select>
    </div>
</div>
<br>
<div class="row">
    <div class="input-field col s12">
        <i class="mdi-action-account-circle prefix"></i>
        <label for="cliNombre">Lista de Clientes:</label>
        <input type="text" list="clienteslist" name="buscar_cliente" autocomplete="off" class="input-field" id="cliNombre" required placeholder="Escriba un Cliente">
        <datalist id="clienteslist">
            @foreach($clientes as $datos)
            <option data-id='{{ $datos->id }}' value="{{ $datos->nombre }}"> NIT: {{ $datos->nit }}
                @endforeach
        </datalist>
    </div>
</div>
<div class="row">
    <div class="col s12">
        {!!link_to('#', $title='Generar', $attributes = ['id'=>'ventaCredito', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
</div>
@stop
@section('scripts')
{!! Html::script('js/addreporte.js') !!}
@endsection   