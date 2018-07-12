<!-- Modal Structure -->
<div id="modal1" class="modal">
    <div class="modal-content">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <input type="hidden" id="id">
        <h4><strong>Actualizar Contador</strong></h4>
        <div class="divider"></div>
        <h5 id="nombre">Actualizar Contador</h5>
        {!!Form::label('Contador','Contador: *')!!}
        {!!Form::text('Contador',null, ['id'=>'contador'])!!}
    </div>
    <div class="modal-footer">
        <a   class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
        {!!link_to('#', $title='Actualizar', $attributes = ['id'=>'actualizar', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
</div>