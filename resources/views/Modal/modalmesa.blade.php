 

  <!-- Modal Structure -->

   <div id="modal1" class="modal modal-fixed-footer">
    <div class="modal-content">
      <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
       
      <h4>Agregar Mesa</h4>
       
 

 {!!Form::label('nummesa','Numero de mesa: ')!!}
  {!!Form::text('nummesa',null, ['id'=>'nummesa'])!!}
  <div></div>

 {!!Form::label('Ubicacion','Ubicacion: ')!!}
  {!!Form::text('Ubicacion',null, ['id'=>'ubicacion'])!!}
  <div></div>

 {!!Form::label('Capacidad','Capacidad: ')!!}
  {!!Form::number('Capacidad',null, ['id'=>'capacidad'])!!}
  <div></div>

 {!!Form::label('Estado','Estado: ')!!}
 <div>
   <input class="with-gap" name="group1" type="radio" id="test0" checked />
    <label for="test0">Libre</label>
     <input class="with-gap" name="group1" type="radio" id="test1"   />
    <label for="test1">Reservada</label>
     <input class="with-gap" name="group1" type="radio" id="test2"   />
    <label for="test2">Ocupada</label>
    </div>
 
  <div></div>
   {!!Form::label('Sucursal','Sucursal: ')!!}
   {!! Form::select('sucursal', $Sucursal,null, ['id'=>'sucursal']) !!} 
</div>
    <div class="modal-footer">
         <a   class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
      {!!link_to('#', $title='Guardar', $attributes = ['id'=>'guardar', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
  </div>

  <!-- Modal Structure -->
  <div id="modal2" class="modal modal-fixed-footer">
    <div class="modal-content">
      <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <input type="hidden" id="id">
      <h4>Actualizar Mesa</h4>
             <div class="divider"></div>
   
 

 {!!Form::label('nummesa','Numero de mesa: ')!!}
  {!!Form::text('nummesa',null, ['id'=>'nummesas'])!!}
  <div></div>

 {!!Form::label('Ubicacion','Ubicacion: ')!!}
  {!!Form::text('Ubicacion',null, ['id'=>'ubicacions'])!!}
  <div></div>

 {!!Form::label('Capacidad','Capacidad: ')!!}
  {!!Form::number('Capacidad',null, ['id'=>'capacidads'])!!}
  <div></div>

 {!!Form::label('Estado','Estado: ')!!}
 <div>
   <input class="with-gap" name="group2" type="radio" id="test0s"   />
    <label for="test0s">Libre</label>
     <input class="with-gap" name="group2" type="radio" id="test1s"   />
    <label for="test1s">Reservada</label>
     <input class="with-gap" name="group2" type="radio" id="test2s"   />
    <label for="test2s">Ocupada</label>
    </div>
 
  <div></div>
   {!!Form::label('Sucursal','Sucursal: ')!!}
   {!! Form::select('sucursal', $Sucursal,null, ['id'=>'sucursals']) !!} 
    </div>
    <div class="modal-footer">
         <a   class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
      {!!link_to('#', $title='Actualizar', $attributes = ['id'=>'actualizar', 'class'=>'btn btn-primary'], $secure = null)!!}
 
    </div>
  </div>

