<!-- Modal Structure -->
<div id="modal1" class="modal">
    <div class="modal-content">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <input type="hidden" id="id">
        <h4><strong>Agregar Tipo de Ingreso</strong></h4>
        <div class="divider"></div>
        <div class="col s12 input-field">
            <i class="prefix mdi-action-system-update-tv"></i>
            {!!Form::label('Nombre','Nombre: *')!!}
            {!!Form::text('nombre',null, ['id'=>'nombre'])!!}
        </div>
    </div>
    <div class="modal-footer">
        <a  id="closemodal" class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
        {!!link_to('#', $title='Guardar', $attributes = ['id'=>'guardar', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
</div>
<!-- Modal Structure -->
<div id="modal2" class="modal">
    <div class="modal-content">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <h4><strong>Actualizar tipo Ingreso</strong></h4>
        <div class="divider"></div>
        <div class="col s12 input-field">
            <i class="prefix mdi-action-system-update-tv"></i>
            {!!Form::label('Nombre','Nombre: *')!!}
            {!!Form::text('nombre',null, ['id'=>'nombres','placeholder'=>''])!!}
        </div>
    </div>
    <div class="modal-footer">
        <a   class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
        {!!link_to('#', $title='Actualizar', $attributes = ['id'=>'actualizar', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
</div>
