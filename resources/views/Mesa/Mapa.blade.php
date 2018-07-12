@extends('Layouts.PanelVentas')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Tablero de Mesas</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li class="active">Tablero de Mesas</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
<input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
<h3><strong>Tablero de Mesas</strong></h3>
<div class="divider"></div>
<div class="container">
    <div class="row">
        <div class="col s12 m12 l4" id="cabecera">
            <div class="card-panel light-green accent-4" id="divlibre"> 
                <input type="radio" id="test1" name="group1" class="group1"/>
                <label for="test1"><span class="white-text text-darken-2">Libre</span></label>
            </div>
        </div>
        <div class="col s12 m12 l4">
            <div class="card-panel  light-blue darken-4" id="divreservado"> 
                <input type="radio" id="test2" name="group1" class="group1"/>
                <label for="test2"><span class="white-text text-darken-2">Reservado</span></label>
            </div>
        </div>
        <div class="col s12 m12 l4">
            <div class="card-panel red accent-2" id="divocupado"> 
                <input type="radio" id="test3" name="group1" class="group1"/>
                <label for="test3"><span class="white-text text-darken-2">Ocupado</span></label>
            </div>
        </div>
    </div>
    <div class="row" id="inicio">
    </div>
</div>

@stop
@section('scripts')
{!! Html::script('js/addmapamesa.js') !!}
@endsection