<!-- Modal Structure -->
<div id="modal1" class="modal">
    <div class="modal-content">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <input type="hidden" id="idactualizar">
        <h4><strong>Actualizar Subcategoria</strong></h4>
        <div class="divider"></div>
        <div class="input-field col s12">
            <i class="mdi-communication-business prefix"></i>
            {!!Form::label('Nombre','Nombre: *')!!}
            {!!Form::text('nombre',null, ['id'=>'nombres','placeholder'=>''])!!}
        </div>
        
            
                <span>Seleccionar imagen :</span>
                <form action="#">
                    <div class="file-field input-field">
                        <div class="btn">
                            <span><i class="material-icons">open_in_browser</i></span>
                            <input type="file" id="seleccionarImagen2">
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path validate" type="text" id="nombreimg2">
                        </div>
                    </div>
                 </form>
        <img id="vistaPrevia2" src="/images/productoavatar.png" style="width: 180px;"> 
            
        
    </div>
    <div class="modal-footer">
        <a   class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
        {!!link_to('#', $title='Actualizar', $attributes = ['id'=>'actualizar', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
</div>

