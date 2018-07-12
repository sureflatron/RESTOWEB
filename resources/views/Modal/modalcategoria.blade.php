<!-- Modal Structure -->
<div id="modal1" class="modal">
    <div class="modal-content">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <input type="hidden" id="id">
        <h4><strong>Actualizar Categoria</strong></h4>
        <div class="divider"></div>
        <div class="input-field col s12">
            <i class="mdi-content-text-format prefix"></i>
            {!!Form::label('Nombre','Nombre: *')!!}
            {!!Form::text('nombre',null, ['id'=>'nombre','placeholder'=>''])!!}
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
        <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
        {!!link_to('#', $title='Actualizar', $attributes = ['id'=>'actualizar', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
</div>
<div id="modal2" class="modal">
    <div class="modal-content">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token"> 
        <h4><strong>Agregar Categoria</strong></h4>
        <div class="divider"></div> 
        <div class="input-field col s12">
            <i class="mdi-content-text-format prefix"></i>
            {!!Form::label('Nombre','Nombre: *')!!}
            {!!Form::text('nombre',null, ['id'=>'esteeselnombre'])!!}
        </div>
        <span>Seleccionar imagen :</span>
        <form action="#">
            <div class="file-field input-field">
                <div class="btn">
                    <span><i class="material-icons">open_in_browser</i></span>
                    <input type="file" id="seleccionarImagen">
                </div>
                <div class="file-path-wrapper">
                    <input class="file-path validate" type="text" id="nombreimg">
                </div>
            </div>
        </form>
        <img id="vistaPrevia" src="/images/productoavatar.png" style="width: 180px;"/> 
        <span>Imagen de tamaño 64x64 px</span>
    </div>
    <div class="modal-footer">
        <a href="#!" id="closemodal" class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
        {!!link_to('#', $title='Guardar', $attributes = ['id'=>'guardar', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
</div>