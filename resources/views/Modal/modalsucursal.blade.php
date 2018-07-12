<!-- Modal Structure -->
<div id="modal1" class="modal modal-fixed-footer">
    <div class="modal-content">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <h4>Agregar Sucursal</h4>
        <div class="divider"></div>
        <div class="col s12">
            {!!Form::label('Empresa','Empresa: *')!!}
            {!! Form::select('Empresa', $empresa,null, ['id'=>'empresa']) !!}
        </div>
        <div class="col s12">
            {!!Form::label('Ciudad','Ciudad: *')!!}
            {!! Form::select('Ciudad', $Ciudad,null,['id'=>'ciudad']) !!}
        </div>
        <div class="input-field col s12">
            <i class="mdi-social-location-city prefix"></i>
            {!!Form::label('Nombre','Nombre: *')!!}
            {!!Form::text('nombre',null, ['id'=>'nombre'])!!}
        </div>
        <div class="input-field col s12">
            <i class="mdi-maps-place prefix"></i>
            {!!Form::label('Dirrecion','Dirrecion: *')!!}
            {!!Form::text('Dirrecion',null, ['id'=>'dirrecion'])!!}
        </div>
        <div class="input-field col s12">
            <i class="mdi-notification-phone-in-talk prefix"></i>
            {!!Form::label('Telefono','Telefono: ')!!}
            {!!Form::text('Telefono',null, ['id'=>'telefono','onkeypress'=>'return isNumberKey(this);'])!!}
        </div>
    </div>
    <div class="modal-footer">
        <a  id="closemodal" class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
        {!!link_to('#', $title='Guardar', $attributes = ['id'=>'guardar', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
</div>

<!-- Modal Structure -->
<div id="modal2" class="modal modal-fixed-footer">
    <div class="modal-content">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <input type="hidden" id="idactualizar">
        <h4>Actualizar Sucursal</h4>
        <div class="divider"></div>
        <div class="col s12">
            {!!Form::label('Empresa','Empresa: *')!!}
            {!! Form::select('Empresa', $empresa,null, ['id'=>'empresas']) !!}
        </div>
        <div class="col s12">
            {!!Form::label('Ciudad','Ciudad: *')!!}
            {!! Form::select('Ciudad', $Ciudad,null, ['id'=>'ciudads']) !!}
        </div>
        <div class="input-field col s12">
            <i class="mdi-social-location-city prefix"></i>
            {!!Form::label('Nombre','Nombre: *')!!}
            {!!Form::text('nombre',null, ['id'=>'nombres','placeholder'=>''])!!}
        </div>
        <div class="input-field col s12">
            <i class="mdi-maps-place prefix"></i>
            {!!Form::label('Dirrecion','Dirrecion: *')!!}
            {!!Form::text('Dirrecion',null, ['id'=>'dirrecions','placeholder'=>''])!!}
        </div>
        <div class="input-field col s12">
            <i class="mdi-notification-phone-in-talk prefix"></i>
            {!!Form::label('Telefono','Telefono: ')!!}
            {!!Form::text('Telefono',null, ['id'=>'telefonos','placeholder'=>'','onkeypress'=>'return isNumberKey(this);'])!!}
        </div>
    </div>
    <div class="modal-footer">
        <a   class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
        {!!link_to('#', $title='Actualizar', $attributes = ['id'=>'actualizar', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
</div>


