<!-- Modal Structure -->
<div id="modal1" class="modal  modal-fixed-footer">
    <div class="modal-content">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <input type="hidden" id="id">
        <h4>Actualizar Empresa</h4>
        <div class="divider"></div>
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <i class="mdi-communication-business prefix"></i>
                {!!Form::label('Nombre','Nombre: *')!!}
                {!!Form::text('nombre',null, ['id'=>'nombre','placeholder'=>''])!!}
            </div>
            <div class="input-field col s12 m6 l6">
                <i class="mdi-av-web prefix"></i>
                {!!Form::label('web','Sitio Web: ')!!}
                {!!Form::text('web',null, ['id'=>'web','placeholder'=>''])!!}
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <i class="mdi-communication-email prefix"></i>
                {!!Form::label('correo','Correo Email: ')!!}
                {!!Form::text('correo',null, ['id'=>'correo','placeholder'=>''])!!}
            </div>
            <div class="input-field col s12 m6 l6">
                <i class="mdi-communication-comment prefix"></i>
                {!!Form::label('correo','Actividad: ')!!}
                {!!Form::text('correo',null, ['id'=>'actividad','maxlength' => 300,'placeholder'=>''])!!}
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <i class="mdi-action-perm-identity prefix"></i>
                {!!Form::label('correo','Propietario : ')!!}
                {!!Form::text('correo',null, ['id'=>'propietario','maxlength' => 50,'placeholder'=>''])!!}
            </div>
            <div class="input-field col s12 m6 l6">
                <div class="row">
                    <div class="input-field col s1">
                        <i class="mdi-content-content-paste prefix"></i>
                    </div>
                    <div class="col s11">
                        <h6>¿Desea vender con Stock?</h6>
                        <input name="group1" type="radio" id="test1"/>
                        <label for="test1">SI</label>
                        <input name="group1" type="radio" id="test2"/>
                        <label for="test2">NO</label>
                    </div>
                </div>
            </div>
        </div>
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
        <img id="vistaPrevia2" src="/images/productoavatar.png" style="height: 200px; text-align:center;" /> 
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
        {!!link_to('#', $title='Actualizar', $attributes = ['id'=>'actualizar', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
</div>

<div id="modal2" class="modal modal-fixed-footer">
    <div class="modal-content">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">  
        <h4>Agregar Empresa</h4>
        <div class="divider"></div>
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <i class="mdi-communication-business prefix"></i>
                {!!Form::label('Nombre','Nombre: *')!!}
                {!!Form::text('nombre',null, ['id'=>'nombres'])!!}
            </div>
            <div class="input-field col s12 m6 l6">
                <i class="mdi-av-web prefix"></i>
                {!!Form::label('web','Sitio Web: ')!!}
                {!!Form::text('web',null, ['id'=>'webs'])!!}
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <i class="mdi-communication-email prefix"></i>
                {!!Form::label('correo','Correo Email: ')!!}
                {!!Form::text('correo',null, ['id'=>'correos'])!!}
            </div>
            <div class="input-field col s12 m6 l6">
                <i class="mdi-communication-comment prefix"></i>
                {!!Form::label('correo','Actividad: ')!!}
                {!!Form::text('correo',null, ['id'=>'actividads','maxlength' => 300])!!}
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <i class="mdi-action-perm-identity prefix"></i>
                {!!Form::label('correo','Propietario: ')!!}
                {!!Form::text('correo',null, ['id'=>'propietarios','maxlength' => 50])!!}
            </div>
            <div class="input-field col s12 m6 l6">
                <div class="row">
                    <div class="input-field col s1">
                        <i class="mdi-content-content-paste prefix"></i>
                    </div>
                    <div class="col s11">
                        <h6>¿Decea vender con Stock?</h6>
                        <input name="group3" type="radio" id="test3"/>
                        <label for="test3">SI</label>
                        <input name="group3" type="radio" id="test4"/>
                        <label for="test4">NO</label>
                    </div>
                </div>
            </div>
        </div>
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
        <img id="vistaPrevia" src="/images/productoavatar.png"/> 
        <span>Imagen de tamaño 64x64 px</span>
    </div>
    <div class="modal-footer">
        <a id="closemodal" href="#" class="waves-effect waves-red btn-flat modal-action modal-close">Cerrar</a>
        {!!link_to('#', $title='Guardar', $attributes = ['id'=>'guardar', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
</div>