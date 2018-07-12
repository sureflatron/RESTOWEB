@extends('Layouts.Reporte')
@section('Contenido')
<div id="breadcrumbs-wrapper" class=" grey lighten-3">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title"  style="text-align:center;">Reporte de Venta Cruzadas</h5>
                <ol class="breadcrumb" >


                    <li><a onClick="imprimir()"  id="desaparece"  href="#" >Imprimir a PDF</a>
                    </li>     
                    <li><a     href="/descargarVentacruzada/{{ $id }}">Exportar a excel</a>
                    </li> 
                    <li><a href="/GestionarReporte">Volver</a>
                    </li> 

                </ol>
            </div>
        </div>
    </div>
</div>



<input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">

<input type="hidden"  value="{{ $id }}" id="id">
<div class="row">
    <div class="col s12"><p> </p></div>
    <div class="col s12 m4 l2"><p></p></div>
    <div class="col s12 m4 l8">   <div style="page-break-after: always;" id="imprmir">

            <h4>Producto seleccionado: {{$producto}}</h4>

            <div>
                <div class="col s6"> 

                </div>



            </div>
            <table border="2px" class="bordered">  
                <tr id="medio2" >

                    <TH    >Producto {{$producto}} Se Vende Con :</TH>
                    <TH   >Cantidad</TH>
                </tr>


                <tbody  id="datos">


                </tbody>
            </table>
        </div>
    </div>
    <div class="col s12 m4 l2"><p>  </a> </p></div>


</div>


@stop
@section('scripts')

{!!Html::script('js/extra/Ventacruzdas.js')!!}
@endsection

