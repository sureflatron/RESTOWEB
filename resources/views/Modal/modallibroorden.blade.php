<!-- Modal Structure -->
<div id="modal1" class="modal modal-fixed-footer">
    <div class="modal-content">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <input type="hidden" id="idactualizar">
        <h4><strong>Actualizar Libro Orden</strong></h4>
        <div class="divider"></div>
        <div class="row">
            <div class="col s12 m6 l6">
                <div class="row">
                    <div class="input-field col s1">
                        <i class="mdi-social-domain prefix"></i>
                    </div>
                    <div class="col s11">
                        {!!Form::label('Sucursal','Sucursal: *')!!}
                        {!! Form::select('idSucursal', $sucursal,null, ['id'=>'idSucursal']) !!}
                    </div>
                </div>
            </div>
            <div class="input-field col s12 m6 l6">
                <i class="mdi-social-person prefix"></i>
                {!!Form::label('NIT','NIT: *')!!}
                {!!Form::text('NIT',null, ['id'=>'NIT','placeholder'=>'','onkeypress'=>'return isNumberKey(this);'])!!}
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <i class="mdi-content-content-paste prefix"></i>
                {!!Form::label('nroAutorizacion','Nro Autorizacion: ')!!}
                {!!Form::text('nroAutorizacion',null, ['id'=>'nroAutorizacion','placeholder'=>'','onkeypress'=>'return isNumberKey(this);'])!!}
            </div>
            <div class="input-field col s12 m6 l6">
                <i class="mdi-editor-format-list-numbered prefix"></i>
                {!!Form::label('nroInicio','Nro  Inicio: *')!!}
                {!!Form::text('nroInicio',null, ['id'=>'nroInicio','placeholder'=>'','onkeypress'=>'return isNumberKey(this);'])!!}
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <i class="mdi-editor-format-list-numbered prefix"></i>
                {!!Form::label('nroFin','Nro  Fin: *')!!}
                {!!Form::text('nroFin',null, ['id'=>'nroFin','placeholder'=>'','onkeypress'=>'return isNumberKey(this);'])!!}
            </div>
            <div class="input-field col s12 m6 l6">
                <i class="mdi-editor-merge-type prefix"></i>
                {!!Form::label('tipo','Tipo: ')!!}
                {!!Form::text('tipo',null, ['id'=>'tipo','placeholder'=>''])!!}
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <i class="mdi-action-today prefix"></i>
                {!!Form::label('fechaInicio','Fecha Inicio: *')!!}
                {!!Form::date('fechaInicio',null, ['id'=>'fechaInicio','class'=>'datepicker','placeholder'=>''])!!}
            </div>
            <div class="input-field col s12 m6 l6">
                <i class="mdi-action-today prefix"></i>
                {!!Form::label('fechaFin','fecha  Fin: *')!!}
                {!!Form::date('fechaFin',null, ['id'=>'fechaFin','class'=>'datepicker','placeholder'=>''])!!}
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12">
                <i class="mdi-communication-vpn-key prefix"></i>
                {!!Form::label('llave','Llave: *')!!}
                {!!Form::text('llave',null, ['id'=>'llave','placeholder'=>''])!!}
            </div>
        </div>
        <div class="row">
            <div class="input-field col s1">
                <i class="mdi-content-content-paste prefix"></i>
            </div>
            <div class="col s11">
                <h6>Habilitado</h6>
                <input name="group1" type="radio" id="test1" />
                <label for="test1">Si</label>
                <input name="group1" type="radio" id="test2" />
                <label for="test2">No</label>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a   class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
        {!!link_to('#', $title='Actualizar', $attributes = ['id'=>'actualizar', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
</div>
