@extends('Layouts.Panel')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Listado de Horarios</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li><a href="/Gestionarturno">Listado Turno</a></li>
                    <li class="active">Listado de Horarios</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
@include('Modal.modalHorario')
<input type="hidden"  value="{{ $idturno }}" id="idturno">
<input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
<div class="row">
    <div class="col s12">
        <h4><strong>Agregar Horario al Turno:</strong>  &nbsp;&nbsp; {{ $turno }}</h4>
    </div>
</div>
<div class="row">
    <div class="col s12 m2 l0">
        <label for="dias">Seleccione el Dia:</label>
        <select id="dias">
            <option value="Lunes">Lunes</option>
            <option value="Martes">Martes</option>
            <option value="Miercoles">Miércoles</option>
            <option value="Jueves">Jueves</option>
            <option value="Viernes">Viernes</option>
            <option value="Sabado">Sábado</option>
            <option value="Domingo">Domingo</option>
        </select>
    </div>
    <div class="col s12 m2 l0">
        <label for='horaentrada'>Hora de Entrada: </label>
        <input type="time"  id="horaentrada"  step="1800" placeholder="">
    </div>
    <div class="col s12 m2 l0">
        <label for='horasalida'>Hora de Salida: </label>
        <input type="time"  id="horasalida"   step="1800" placeholder="">
    </div>
    <div class="input-field col s12 m2 l2">    <a class="btn btn-floating waves-effect" id="guardar" href="#"><i class="mdi-content-add-box"></i></a></div>
</div>
<table  id="tablacategoria" class="centered display compact nowrap" cellspacing="0" width="100%">
    <thead>
    <th>Dia </th>
    <th>Hora de  Entrada </th>
    <th>Hora de salida </th>	 
    <th>Editar</th>
    <th>Eliminar</th>
</thead>
<tfoot  style=" display: table-header-group; background: white;">
<th>Dia </th>
<th>Hora de  Entrada </th>
<th>Hora de salida </th>	 
<th></th>
<th></th>
</tfoot>
<tbody id="datos">
</tbody>
</table>

@stop
@section('scripts')
{!! Html::script('js/addhorario.js') !!}
@endsection