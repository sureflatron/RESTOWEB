@extends('Layouts.Panel')
@section('Contenido')
@include('Modal.modalproducto')
  <div id="breadcrumbs-wrapper" class=" grey lighten-3">
          <div class="container">
            <div class="row">
              <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Registrar Producto</h5>
                <ol class="breadcrumb">
                  <li><a href="/Categoria/">Categoria</a>
                  </li>        
                    <li><a href="/index/">Inicio</a>
                  </li>   
                </ol>
              </div>
            </div>
          </div>
        </div>


<div class="container">


     <div class="row">
     <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
    <div class="col s6">
       <h4 >Listado  de Productos : </h4>
      </div>
     </div>
    <div class="row">
  <div class="col s6">

 <input placeholder="Buscar producto" id="buscar" type="text">          
       <a class="btn" href="/AddProducto/">+</a>

</div>

  <a class="waves-effect waves-light btn" onclick="Buscadordeproducto()">Buscar</a>
</div>

        </div>
 


     <table class="centered" id="tablacategoria">
<thead>
 
    <th>Categoria</th>
    <th>Nombre</th>
  <th>Descripcion</th>
    <th>Precio</th>
  
  <th>Operacion</th>
   
 
</thead>

<tbody id="datos">
 
</tbody>
 </table>  

 

 
  
    </div>

  

 

<div class="divider"></div>
 
@stop

@section('scripts')
 {!! Html::script('js/addproducto.js') !!}
   
 
   
@endsection
