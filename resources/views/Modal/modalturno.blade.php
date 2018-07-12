<div id="modal1" class="modal">
    <div class="modal-content">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <input type="hidden" id="id">
        <h4><strong>Agregar Turno</strong></h4>
        <div class="divider"></div>
        <div class="input-field col s12">
            <i class="mdi-action-turned-in-not prefix"></i>
            {!!Form::label('Nombre','Nombre: *')!!}
            {!!Form::text('nombre',null, ['id'=>'nuevonombre'])!!}
        </div>
        {!!Form::label('Nombre','Min. Tolerancia: ')!!}
        <div id="rangomin"></div>
        <h5>Rango de 0 a 15 min</h5>
    </div>
    <div class="modal-footer">
        <a id="closemodal"  class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
        {!!link_to('#', $title='Agregar', $attributes = ['id'=>'guardar', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
</div>


<div id="modal2" class="modal">
    <div class="modal-content">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <input type="hidden" id="idactualizar">
        <h4><strong>Actualizar Turno</strong></h4>
        <div class="divider"></div>
        <div class="input-field col s12">
            <i class="mdi-action-turned-in-not prefix"></i>
            {!!Form::label('Nombre','Nombre: *')!!}
            {!!Form::text('nombre',null, ['id'=>'actualizarnombre','placeholder'=>''])!!}
        </div>
        {!!Form::label('Nombre','Min. Tolerancia: ')!!}
        <div id='actualizarangomin'></div>
        <h5>Rango de 0 a 15 min</h5>
    </div>
    <div class="modal-footer">
        <a   class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
        {!!link_to('#', $title='Actualizar', $attributes = ['id'=>'actualizar', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
</div>