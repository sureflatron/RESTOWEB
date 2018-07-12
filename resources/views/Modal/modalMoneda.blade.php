<div id="modal1" class="modal">
    <div class="modal-content">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <input type="hidden" id="id">
        <h4><strong>Agregar Moneda</strong></h4>
        <div class="divider"></div>
        <div class="col s12 input-field">
            <i class="mdi-editor-border-color prefix"></i>
            {!!Form::label('Nombre','Moneda: *')!!}
            {!!Form::text('nombre',null, ['id'=>'moneda'])!!}
        </div>
        <div class='col s12 input-field'>
            <i class="mdi-toggle-radio-button-off prefix"></i>
            {!!Form::label('Bs','Bs: *')!!}
            {!!Form::number('nombre',null, ['id'=>'bs'])!!}
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
        <h4><strong>Actualizar Moneda</strong></h4>
        <div class="divider"></div>
        <div class="col s12 input-field">
            <i class="mdi-editor-border-color prefix"></i>
            {!!Form::label('Nombre','Moneda: *')!!}
            {!!Form::text('nombre',null, ['id'=>'nombre','placeholder'=>''])!!}
        </div>
        <div class='col s12 input-field'>
            <i class="mdi-toggle-radio-button-off prefix"></i>
            {!!Form::label('Bs','Bs: *')!!}
            {!!Form::number('nombre',null, ['id'=>'bss','placeholder'=>''])!!}
        </div>
    </div>
    <div class="modal-footer">
        <a   class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
        {!!link_to('#', $title='Actualizar', $attributes = ['id'=>'actualizar', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
</div>