@extends('Layouts.Panel')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Reporte de Flujo de Caja Por Usuario</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li class="active">Reporte de Flujo de Caja Por Usuarios</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
<h4>Flujo de caja  de todos los usuarios</h4>
<div class="row">
    <div class="col s12 m5 l3">
        {!!Form::label('Fechanicio','Fecha inicio: ')!!}
        <input type="date" name="" id="fechainiciofusu">
    </div>
</div>
<div class="row">
    <div class="col s12 m5 l3">
        {!!Form::label('Fechafin','Fecha Fin: ')!!} 
        <input type="date" name="" id="Fechafinfusu">
    </div>
</div>
<div class="row">
    <div class="col s12 m5 l3">
        {!!link_to('#', $title='Generar', $attributes = ['id'=>'flujotodo', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
</div>

@stop
@section('scripts')
{!! Html::script('js/addreporte.js') !!}
@endsection
