@extends('Layouts.Panel')
@section('Contenido')
@include('Modal.modalreporte')
<div id="breadcrumbs-wrapper" class=" grey lighten-3">
          <div class="container">
            <div class="row">
              <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Vista de Reporte</h5>
                <ol class="breadcrumb">
  
                    <li><a href="/index/">Inicio</a>
                  </li>        
                </ol>
              </div>
            </div>
          </div>
        </div>

          <h3>Listado de  Reporte :</h3>

      <div class="row">
  <div class="col s6">

 <input placeholder="Buscar Reporte" id="buscar" type="text" onKeyUp="Buscadordeproducto();" onkeypress="Buscadordeproducto();">          
 

</div>

  <a class="waves-effect waves-light btn" onclick="Buscadordeproducto()"><i class="material-icons">search</i></a>
</div>
<table class="centered" id="tabla">
<thead>
 
        
	<th>Nombre</th>
		 
	<th>Operacion</th>
	 
 
</thead>

<tbody id="datos">
<div id="pdf">
	   <tr>
            <td>Catalogo de  Producto</td>
            <td><a class="btn" id="producto" href="/pdf">Generar</a></td>
           
          </tr>
         </div> 
          <td>Reporte de Ventas del usuario actual</td>
            <td><a  id="caja" class="waves-effect waves-light btn modal-trigger" href="#modal1"">Generar</a></td>
           
          </tr>

               <td>Reporte de Venta todos los usuarios</td>
            <td><a  id="caja" class="waves-effect waves-light btn modal-trigger" href="#modal2"">Generar</a></td>
           
          </tr>
                 <tr>

               <td>Reporte de Venta por usuarios</td>
            <td><a  id="caja" class="waves-effect waves-light btn modal-trigger" href="#modal3"">Generar</a></td>
           
          </tr>

              <tr>

               <td>Flujo de caja usuario actual</td>
            <td><a  id="caja" class="waves-effect waves-light btn modal-trigger" href="#modal4"">Generar</a></td>
           
          </tr>

                      <tr>

               <td>Flujo de caja  de todos los usuarios</td>
            <td><a  id="caja" class="waves-effect waves-light btn modal-trigger" href="#modal5"">Generar</a></td>
           
          </tr>
                         <tr>

               <td>Flujo de caja  por usuario</td>
            <td><a  id="caja" class="waves-effect waves-light btn modal-trigger" href="#modal6"">Generar</a></td>
           
          </tr>
                         <tr>

               <td>Reporte de Egreso</td>
            <td><a  id="caja" class="waves-effect waves-light btn modal-trigger" href="#modal7"">Generar</a></td>
           
          </tr>
    <!-- CORE CSS 
            <tr>

               <td>Reporte de Compras</td>
            <td><a  id="caja" class="waves-effect waves-light btn modal-trigger" href="#modal8"">Generar</a></td>
           
          </tr>
-->  

</tbody>
 </table>

@stop
@section('scripts')
 {!! Html::script('js/addreporte.js') !!}
@endsection

