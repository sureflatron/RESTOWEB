@extends('Layouts.Reporte')
@section('Contenido')
  <div id="breadcrumbs-wrapper" class=" grey lighten-3">
    <div class="container">
      <div class="row">
        <div class="col s12 m12 l12">
          <h5 class="breadcrumbs-title"  style="text-align:center;">
            KARDEX DE INVENTARIO
          </h5>
          <h6 style="text-align:center;">
            De {{ $fechaInicio  }} a  {{ $fechaFin }}
          </h6>
          <ol class="breadcrumb" >       
            <li>
              <a onClick="imprimir()"  id="desaparece"  href="#" >Imprimir a PDF</a>
            </li>     
            <li>
              <a href="/descargarreportekardex/{{ $fechaInicio  }}/{{ $fechaFin }}/{{ $idProducto }}/{{ $idAlmacen }}">
                Exportar a excel
              </a>
            </li> 
            <li>
              <a href="/GestionarReporte">Volver</a>
            </li> 
          </ol>
        </div>
      </div>
    </div>
  </div>

  <input type="hidden"  id="fechaFin" value="{{$fechaFin}}">
  <input type="hidden" id="fechaInicio"  value="{{$fechaInicio}}">
  <input type="hidden" id="idProducto"  value="{{$idProducto}}">
  <input type="hidden" id="idAlmacen"  value="{{$idAlmacen}}">

  <div class="row">
    <div class="col s12">
    </div>
      <div class="col s12 m4 l2"><p></p></div>
      <div class="col s12 m4 l8">
        <div style="page-break-after: always;" id="imprmir">
          <div>
            <div>
            PRODUCTO:
            <label id="nombre"></label>
            </div>
            <div>
            DESCRIPCION:
            <label id="descripcion"></label>
            </div>
            <div>
              CODIGO INTERNO:
              <label id="codigoInterno"></label>
            </div>
            <div>
            CODIGO DE BARRA:
            <label id="codigoBarra"></label>
            </div>
          </div>
          <table BORDER=1 WIDTH=100%>
            <thead>
              <tr>
                <th>Fecha</th>
                <th>Id Transaccion</th>
                <th>Origen Transaccion</th>
                <th>Ingreso</th>
                <th>Egreso</th>
                <th>Saldos</th>
              </tr>
            </thead>
            <tbody id="datos">
            </tbody>
          </table>
          <div style="page-break-after: always;"></div>
        </div> 
      </div>
    <div class="col s12 m4 l2"></div>
  </div>

@stop
@section('scripts') 
   {!!Html::script('js/extra/reportekardexinventario.js')!!}
@endsection
