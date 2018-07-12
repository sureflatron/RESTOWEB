 
<!-- Modal Structure -->
<div id="modal1" class="modal">
    <div class="modal-content">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <input type="hidden" id="id">
        <h4><strong>Actualizar Proveedor</strong></h4>
        <div class="divider"></div>
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <i class="mdi-action-account-box prefix"></i>
                {!!Form::label('Nombre','Nombre: *')!!}
                {!!Form::text('nombre',null, ['id'=>'nombre','placeholder'=>''])!!}
            </div>
            <div class="col s12 m6 l6">
                <div class="row">
                    <div class="input-field col s1">
                        <i class="mdi-action-store prefix"></i>
                    </div>
                    <div class="col s11">
                        <span >Ciudad:*</span>
                        {!! Form::select('idCiudad', $sucursal,null, ['id'=>'idCiudad']) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12 m6 l6 ">
                <i class="mdi-action-turned-in-not prefix"></i>
                {!!Form::label('direccion','Direccion: *')!!}
                {!!Form::text('direccion',null, ['id'=>'direccion','placeholder'=>''])!!}
            </div>
            <div class="input-field col s12 m6 l6 ">
                <i class="mdi-communication-call prefix"></i>
                {!!Form::label('telefono','Telefono: ')!!}
                {!!Form::text('telefono',null, ['id'=>'telefono','placeholder'=>'','onkeypress'=>'return isNumberKey(this);'])!!}
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <i class="mdi-communication-comment prefix"></i>
                {!!Form::label('paginaWeb','Pagina Web: ')!!}
                {!!Form::text('paginaWeb',null, ['id'=>'paginaWeb','placeholder'=>''])!!}
            </div>
            <div  class="input-field col s12 m6 l6">
                <i class="mdi-communication-contacts prefix"></i>
                {!!Form::label('contactoRef','Contacto Ref: *')!!}
                {!!Form::text('contactoRef',null, ['id'=>'contactoRef','placeholder'=>''])!!}
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <i class="mdi-communication-quick-contacts-dialer prefix"></i>
                {!!Form::label('telefonoContacto','Telefono Contacto: *')!!}
                {!!Form::text('telefonoContacto',null, ['id'=>'telefonoContacto','placeholder'=>'','onkeypress'=>'return isNumberKey(this);'])!!}
            </div>
            <div class="input-field col s12 m6 l6">
                <i class="mdi-communication-quick-contacts-mail prefix"></i>
                {!!Form::label('correoContato','Correo Contato: ')!!}
                {!!Form::text('correoContato',null, ['id'=>'correoContato','placeholder'=>''])!!}
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
        {!!link_to('#', $title='Actualizar', $attributes = ['id'=>'actualizar', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
</div>
