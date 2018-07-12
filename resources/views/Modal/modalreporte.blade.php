  <!-- Modal Structure -->
  <div id="modal1" class="modal modal-fixed-footer">
    <div class="modal-content">
      <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <input type="hidden" id="id">
      <h4>Reporte de Ventas del usuario actual</h4>
             <div class="divider"></div>
                {!!Form::label('FEchanicio','Fecha inicio: ')!!}
                <input type="date" name="" id="fechainicio">
 
 {!!Form::label('Fechafin','Fecha Fin: ')!!}
 
        <input type="date" name="" id="Fechafin">

 
 
    </div>
    <div class="modal-footer">
         <a   class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
      {!!link_to('#', $title='Generar', $attributes = ['id'=>'generarreportecaja', 'class'=>'btn btn-primary'], $secure = null)!!}
 
    </div>
  </div>

    <!-- Modal Structure -->
  <div id="modal2" class="modal modal-fixed-footer">
    <div class="modal-content">
      <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <input type="hidden" id="id">
      <h4>Reporte de Venta todos los usuarios </h4>
             <div class="divider"></div>
                {!!Form::label('FEchanicio','Fecha inicio: ')!!}
                <input type="date" name="" id="fechainicios">
 
 {!!Form::label('Fechafin','Fecha Fin: ')!!}
 
        <input type="date" name="" id="Fechafins">

 
       
 
    </div>
    <div class="modal-footer">
         <a   class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
      {!!link_to('#', $title='Generar', $attributes = ['id'=>'generarreportecajatotales', 'class'=>'btn btn-primary'], $secure = null)!!}
 
    </div>
  </div>

 
 
 
    <!-- Modal Structure -->
  <div id="modal3" class="modal modal-fixed-footer">
    <div class="modal-content">
      <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <input type="hidden" id="id">
      <h4>Reporte de Venta por usuarios</h4>
             <div class="divider"></div>
                {!!Form::label('FEchanicio','Fecha inicio: ')!!}
                <input type="date" name="" id="fechainicioss">
 
 {!!Form::label('Fechafin','Fecha Fin: ')!!}
 
        <input type="date" name="" id="Fechafinss">

  
    {!!Form::label('Cliente (opcional)','Lista de empleado  ; ')!!}
     <select   id="empleado">
     
    </select>
 
       
 
    </div>
    <div class="modal-footer">
         <a   class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
      {!!link_to('#', $title='Generar', $attributes = ['id'=>'generarreportecajatotalesporusuario', 'class'=>'btn btn-primary'], $secure = null)!!}
 
    </div>
  </div>

 

   <!-- Modal Structure -->
  <div id="modal4" class="modal modal-fixed-footer">
    <div class="modal-content">
      <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <input type="hidden" id="id">
      <h4>Flujo de caja  por usuario</h4>
             <div class="divider"></div>
                {!!Form::label('FEchanicio','Fecha inicio: ')!!}
                <input type="date" name="" id="fechainiciof">
 
 {!!Form::label('Fechafin','Fecha Fin: ')!!}
 
        <input type="date" name="" id="Fechafinf">

 
       
    </div>
    <div class="modal-footer">
         <a   class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
      {!!link_to('#', $title='Generar', $attributes = ['id'=>'flujoporusuaeio', 'class'=>'btn btn-primary'], $secure = null)!!}
 
    </div>
  </div>
   <!-- Modal Structure -->
  <div id="modal5" class="modal modal-fixed-footer">
    <div class="modal-content">
      <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <input type="hidden" id="id">
      <h4>Flujo de caja  de todos los usuarios</h4>
             <div class="divider"></div>
                {!!Form::label('FEchanicio','Fecha inicio: ')!!}
                <input type="date" name="" id="fechainiciofusu">
 
 {!!Form::label('Fechafin','Fecha Fin: ')!!}
 
        <input type="date" name="" id="Fechafinfusu">

 
       
    </div>
    <div class="modal-footer">
         <a   class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
      {!!link_to('#', $title='Generar', $attributes = ['id'=>'flujotodo', 'class'=>'btn btn-primary'], $secure = null)!!}
 
    </div>
  </div>
  <div id="modal6" class="modal modal-fixed-footer">
    <div class="modal-content">
      <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <input type="hidden" id="id">
      <h4>Flujo de caja  por usuario</h4>
             <div class="divider"></div>
                {!!Form::label('FEchanicio','Fecha inicio: ')!!}
                <input type="date" name="" id="fechainiciofu">
 
 {!!Form::label('Fechafin','Fecha Fin: ')!!}
 
        <input type="date" name="" id="Fechafinfu">

     {!!Form::label('','Lista de empleado  : ')!!}
     <select   id="empleados">
     
    </select>
 
       
    </div>
    <div class="modal-footer">
         <a   class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
      {!!link_to('#', $title='Generar', $attributes = ['id'=>'flujoporusuarios', 'class'=>'btn btn-primary'], $secure = null)!!}
 
    </div>
  </div>
   <!-- Modal Structure -->
  <div id="modal7" class="modal modal-fixed-footer">
    <div class="modal-content">
      <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <input type="hidden" id="id">
      <h4>Reporte de Egreso</h4>
             <div class="divider"></div>
                {!!Form::label('FEchanicio','Fecha inicio: ')!!}
                <input type="date" name="" id="fechainicioegreso">
 
 {!!Form::label('Fechafin','Fecha Fin: ')!!}
 
        <input type="date" name="" id="Fechafinegreso">


       
    </div>
    <div class="modal-footer">
         <a   class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
      {!!link_to('#', $title='Generar', $attributes = ['id'=>'egreso', 'class'=>'btn btn-primary'], $secure = null)!!}
 
    </div>
  </div>

    <!-- Modal Structure -->     <!-- Modal Structure -->     <!-- Modal Structure -->     <!-- Modal Structure -->     <!-- Modal Structure -->
  <div id="modal8" class="modal modal-fixed-footer">
    <div class="modal-content">
      <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <input type="hidden" id="id">
      <h4>Reporte de compras</h4>
             <div class="divider"></div>
                {!!Form::label('FEchanicio','Fecha inicio: ')!!}
                <input type="date" name="" id="fechainiciocompra">
 
 {!!Form::label('Fechafin','Fecha Fin: ')!!}
 
        <input type="date" name="" id="Fechafincompra">


       
    </div>
    <div class="modal-footer">
         <a   class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
      {!!link_to('#', $title='Generar', $attributes = ['id'=>'compra', 'class'=>'btn btn-primary'], $secure = null)!!}
 
    </div>
  </div>