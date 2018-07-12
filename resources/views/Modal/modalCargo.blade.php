<div id="modal1" class="modal">
    <div class="modal-content">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <input type="hidden" id="id">
        <h4><strong>Agregar Cargo</strong></h4>
        <div class="divider"></div>
        <div class="input-field col s12">
            <i class="mdi-action-picture-in-picture prefix"></i>
            {!!Form::label('Nombre','Nombre: *')!!}
            {!!Form::text('nombre',null, ['id'=>'nombre'])!!}
        </div>
    </div>
    <div class="modal-footer">
        <a  id="closemodal" class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
        {!!link_to('#', $title='Agregar', $attributes = ['id'=>'guardar', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
</div>


<div id="modal2" class="modal">
    <div class="modal-content">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <input type="hidden" id="idactualizar">
        <h4><strong>Actualizar Cargo</strong></h4>
        <div class="divider"></div>
        <div class="input-field col s12">
            <i class="mdi-action-picture-in-picture prefix"></i>
            {!!Form::label('Nombre','Nombre: *')!!}
            {!!Form::text('nombre',null, ['id'=>'nombrenuevo','placeholder'=>''])!!}
        </div>
    </div>
    <div class="modal-footer">
        <a   class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
        {!!link_to('#', $title='Actualizar', $attributes = ['id'=>'actualizar', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
</div>