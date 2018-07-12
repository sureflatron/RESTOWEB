<!-- Modal Structure -->
<div id="modal1" class="modal">
    <div class="modal-content">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <input type="hidden" id="id">
        <h4><strong>Actualizar Tipo de Cliente</strong></h4>
        <div class="divider"></div>
        <div class="input-field col s12">
            <i class="mdi-content-text-format prefix"></i>
            {!!Form::label('Nombre','Nombre: *')!!}
            {!!Form::text('nombre',null, ['id'=>'nombre','placeholder'=>''])!!}
        </div>
        <div class="input-field col s12">
            <i class="mdi-editor-mode-edit prefix"></i>
            {!!Form::label('Abreviatura','Abreviatura: *')!!}
            {!!Form::text('abreviatura',null, ['id'=>'Abreviatura','placeholder'=>''])!!}
        </div>
    </div>
    <div class="modal-footer">
        <a class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
        {!!link_to('#', $title='Actualizar', $attributes = ['id'=>'actualizar', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
</div>
<!-- Modal Structure -->
<div id="modal2" class="modal">
    <div class="modal-content">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <h4><strong>Agregar </strong></h4> 
        <div class="divider"></div>
        <div class="input-field col s12">
            <i class="mdi-content-text-format prefix"></i>
            {!!Form::label('Nombre','Nombre: *')!!}
            {!!Form::text('nombre',null, ['id'=>'esteeselnombre'])!!}
        </div>
        <div class="input-field col s12">
            <i class="mdi-editor-mode-edit prefix"></i>
            {!!Form::label('Abreviatura','Abreviatura: *')!!}
            {!!Form::text('abreviatura',null, ['id'=>'esteesAbreviatura'])!!}
        </div>
    </div>
    <div class="modal-footer">
        <a id="closemodal" class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
        {!!link_to('#', $title='Guardar', $attributes = ['id'=>'guardar', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
</div>