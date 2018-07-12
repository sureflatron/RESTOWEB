<!-- Modal Structure -->
<div id="modal1" class="modal">
    <div class="modal-content">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <input type="hidden" id="idcompo">
        <h4><strong>Actualizar Composicion</strong></h4>
        <div class="divider"></div>
        <div class="row">
            <div class="col s12">
                <h5><strong>Nombre: </strong><span id='ingredientelabel'></span></h5>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12">
                <i class="prefix mdi-action-assignment"></i>
                {!!Form::label('cantidadnueva','Cantidad: ')!!}
                {!!Form::text('abreviatura',null, ['id'=>'cantidadnueva','placeholder'=>'','onkeypress'=>'return isNumberKey(this);'])!!}
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a  class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
        {!!link_to('#', $title='Actualizar', $attributes = ['id'=>'actualizar', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
</div>
