  <!-- Modal Structure -->
  <div id="modal1" class="modal modal-fixed-footer">
    <div class="modal-content">
      <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <input type="hidden" id="id">
      <h4>Actualizar Ingrediente</h4>
             <div class="divider"></div>
 {!!Form::label('Nombre','Nombre: ')!!}
  {!!Form::text('nombre',null, ['id'=>'nombre'])!!}
   
    </div>
    <div class="modal-footer">
      <a  class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>   
      {!!link_to('#', $title='Actualizar', $attributes = ['id'=>'actualizar', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
  </div>




  <div id="modal2" class="modal modal-fixed-footer">
    <div class="modal-content">
      <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <input type="hidden" id="id">
      <h4>Agregar Ingrediente</h4>
         <div class="divider"></div>    
 {!!Form::label('Nombre','Nombre: ')!!}
  {!!Form::text('nombre',null, ['id'=>'addnombre'])!!}
   
    </div>
    <div class="modal-footer">
         <a  class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
      {!!link_to('#', $title='Guardar', $attributes = ['id'=>'guardar', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
  </div>

