@extends('Layouts.Panel')
@section('Contenido')
@include('Modal.modalFactura')
  <div id="breadcrumbs-wrapper" class=" grey lighten-3">
          <div class="container">
            <div class="row">
              <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Editor de venta</h5>
                <ol class="breadcrumb">
                        
                    <li><a href="/index/">Inicio</a>
                  </li>   
                </ol>
              </div>
            </div>
          </div>
        </div>
 
      <input type="hidden"  id="idproducto"> 
       <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
                
{!!Form::hidden('nombre',$id, ['id'=>'venta'])!!}
<div class="row">
      <div class="grid-example col s12"><span class="flow-text">Nueva Venta</span></div>
      <div class="col s12 m8">
 <ul class="collapsible" data-collapsible="expandable">
    <li>
      <div class="collapsible-header" id="titulotipoprodcuto" >Tipo de producto</div>
      <div class="collapsible-body" id="tipoproducto">

    <div class="row" id="divrow2">
 
    </div>

      </div>

    </li>
    <li >
      <div class="collapsible-header"  id="tituloproductos">Producto</div>
      <div class="collapsible-body-a" id="productos">
  
 	  <div class="row" id="divrow">
  
    </div>

 	    
      </div>
    </li>
   
  </ul></div>

         <div class="col s12 m4">

        

<div class="row" id="datallleproducto">
    <div class="col s12 m6 l3"> <span id="nombreproducto"></span> </div>
    <div class="col s12 m6 l3">
        <input type="text" name="" value ="1" id="cantidadproducto"></div>

    <div class="col s12 m6 l3"> <a class="btn" id="agregardetalle">+</a></div>
 
  </div>

         	  <table>
        <thead>
          <tr>
              <th data-field="id">Producto</th>
              <th data-field="name">Cantidad</th>
              <th data-field="price">Precio</th>
                   <th data-field="price">Subtotal</th>
                    <th data-field="price">Operacion</th>
          </tr>
        </thead>

        <tbody id="datos">
          <tr>
             
          </tr>
        
         
        </tbody>
      </table>
       <div class="col s12 m6 l3"> <h5>Total : </h5> </div> 
        <div class="col s12 m6 l3">  <h5 id="total"> 0</h5>   </div> 

         </div>
    </div>

 
<div class="row">
    <div class="col s12 m6 l3" >
    <div id="generadordeventa" >
    <p><a  class="btn" href="#" id="generarventa" >Volver</a></p>
    </div>
    </div>
    <div class="col s12 m6 l3"><p><a class="waves-effect waves-light btn modal-trigger" href="#modal1"   id="cobrarventas" >Aceptar y cobrar</a></p></div>
    <div class="col s12 m6 l3"><p><a class="btn" href="#" id="anularventa">Anular</a></p></div>
     
  </div>

@stop

 @section('scripts')
 {!! Html::script('js/addeditarventa.js') !!}
  
   
@endsection