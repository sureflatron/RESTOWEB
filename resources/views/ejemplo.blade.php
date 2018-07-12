@extends('Layouts.Panel')
@section('Contenido')
<div class="row">
    <div class="col s12">
        <div class="row">
            <input type="number" id="nit" onKeyUp="buscarcliente();"  /> 
            <input type="text"   value="" id="razonsocial"  /> 
            <a class="waves-effect waves-light btn" id="guardar">button</a>
        </div>
    </div>
</div>
@stop
@section('scripts')
{!! Html::script('js/extra/autocompletar.js') !!}
@endsection