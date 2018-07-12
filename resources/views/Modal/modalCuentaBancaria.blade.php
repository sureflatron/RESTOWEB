<div id="modal1" class="modal modal-fixed-footer">
    <div class="modal-content">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <input type="hidden" id="id">
        <h4><strong>Agregar Cuenta Bancaria</strong></h4>
        <div class="divider"></div>
        <div class="row">
            <div class="input-field col s1">
                <i class="mdi-action-account-balance prefix"></i>
            </div>
            <div class="col s11">
                {!!Form::label('Nombre','Banco: *')!!}
                {!! Form::select('banco', $banco,null, ['id'=>'banco']) !!}
            </div>
        </div>
        <div class="col s12 input-field">
            <i class="prefix mdi-image-filter-1"></i>
            {!!Form::label('Cuenta','Nro Cuenta: *')!!}
            {!!Form::text('nombre',null, ['id'=>'cuenta'])!!}
        </div>
        <div class="col s12 input-field">
            <i class="mdi-social-pages prefix"></i>
            {!!Form::label('Cuenta','Tipo de Cuenta: *')!!}
            {!!Form::text('nombre',null, ['id'=>'tipocuenta'])!!}
        </div>
        <div class="row">
            <div class="input-field col s1">
                <i class="mdi-editor-attach-money prefix"></i>
            </div>
            <div class="col s11">
                {!!Form::label('Cuenta','Moneda: *')!!}
                <select id="moneda">
                    <option value="Bs">Bs</option>
                    <option value="Sus">Dolares</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12">
                <i class="mdi-social-person-outline prefix"></i>
                {!!Form::label('Abreviatura','Razon Social/Titular: *')!!}
                {!!Form::text('abreviatura',null, ['id'=>'razonSocial'])!!}
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a  id="closemodal" class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
        {!!link_to('#', $title='Agregar', $attributes = ['id'=>'guardar', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
</div>


<div id="modal2" class="modal modal-fixed-footer">
    <div class="modal-content">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <input type="hidden" id="idactualizar">
        <h4><strong>Actualizar Cuenta Bancaria</strong></h4>
        <div class="divider"></div>
        <div class="row">
            <div class="input-field col s1">
                <i class="mdi-action-account-balance prefix"></i>
            </div>
            <div class="col s11">
                {!!Form::label('Nombre','Banco: *')!!}
                {!! Form::select('banco', $banco,null, ['id'=>'banco1']) !!}
            </div>
        </div>
        <div class="col s12 input-field">
            <i class="prefix mdi-image-filter-1"></i>
            {!!Form::label('Cuenta','Nro Cuenta: *')!!}
            {!!Form::text('nombre',null, ['id'=>'cuenta1','placeholder'=>''])!!}
        </div>
        <div class="col s12 input-field">
            <i class="mdi-social-pages prefix"></i>
            {!!Form::label('Cuenta','Tipo de Cuenta: *')!!}
            {!!Form::text('nombre',null, ['id'=>'tipocuenta1','placeholder'=>''])!!}
        </div>
        <div class="row">
            <div class="input-field col s1">
                <i class="mdi-editor-attach-money prefix"></i>
            </div>
            <div class="col s11">
                {!!Form::label('Cuenta','Moneda: *')!!}
                <select id="moneda1">
                    <option value="Bs">Bs</option>
                    <option value="Sus">Dolares</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12">
                <i class="mdi-social-person-outline prefix"></i>
                {!!Form::label('Abreviatura','Razon Social/Titular: *')!!}
                {!!Form::text('abreviatura',null, ['id'=>'razonSocials','placeholder'=>''])!!}
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a   class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
        {!!link_to('#', $title='Actualizar', $attributes = ['id'=>'actualizar', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
</div>