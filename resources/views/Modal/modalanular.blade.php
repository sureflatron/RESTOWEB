<div id="modal1" class="modal">
    <div class="modal-content">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <input type="hidden" id="id">
        <h4><strong>Definición de Motivo Para Eliminar Factura</strong></h4>
        <div class="divider"></div>
        <div class="input-field col s12">
            <i class="mdi-editor-mode-edit prefix"></i>
            {!!Form::label('Motivo','Motivo: *')!!}
            <textarea id="motivo" class="materialize-textarea" length="250"></textarea>
        </div>
    </div>
    <div class="modal-footer">
        <a   class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
        {!!link_to('#', $title='Anular', $attributes = ['id'=>'anular', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
</div>
<div id="modal2" class="modal">
    <div class="modal-content">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <input type="hidden" id="ideditar">
        <h4><strong>Editar Factura</strong></h4>
        <div class="divider"></div>
        <div class="input-field col s12">
            <i class="mdi-action-label-outline prefix"></i>
            {!!Form::label('nit','Nit: *')!!}
            <input type="text" id="nit"  name="nit" placeholder="" >  
        </div>
        <div class="input-field col s12">
            <i class="mdi-social-person prefix"></i>
            {!!Form::label('Razon social','Razon social: *')!!}
            <input type="text" id="razonsocial" placeholder="">  
        </div>
    </div>
    <div class="modal-footer">
        <a   class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
        {!!link_to('#', $title='Editar', $attributes = ['id'=>'editar', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
</div>
