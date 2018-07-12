<!-- Modal Structure -->
<div id="modal1" class="modal modal-fixed-footer">
    <div class="modal-content">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <input type="hidden" id="idactualizar">
        <h4><strong>Actualizar Tipo de Egreso</strong></h4>
        <div class="divider"></div>
        <div class="row">
            <div class="col s12 m12 l6 input-field">
                <i class="prefix mdi-editor-insert-invitation"></i>
                {!!Form::label('Fecha','Fecha: *')!!}
                <input type="text"   name="" id="fecha" class="datepicker" placeholder="">
            </div>
            <div class="col s12 m12 l6">
                <div class="row">
                    <div class="input-field col s1">
                        <i class="mdi-av-subtitles prefix"></i>
                    </div>
                    <div class="col s11">
                        {!!Form::label('Egreso','Tipo Egreso: *')!!}
                        {!! Form::select('Egreso', $sucursal,null, ['id'=>'idTipoEgreso']) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col s12 m12 l6">
                <div class="row">
                    <div class="input-field col s1">
                        <i class="mdi-action-account-balance prefix"></i>
                    </div>
                    <div class="col s11">
                        {!!Form::label('Proveedor','Proveedor: *')!!}
                        {!! Form::select('Proveedor', $proveedor,null, ['id'=>'idProveedor']) !!}
                    </div>
                </div>
            </div>
            <div class="col s12 m12 l6 input-field">
                <i class="prefix mdi-action-account-box"></i>
                {!!Form::label('pagadoA','Pagado A: *')!!}
                {!!Form::text('pagadoA',null, ['id'=>'pagadoA','placeholder'=>''])!!}
            </div>
        </div>
        <div class="row">
            <div class="col s12 m12 l6 input-field">
                <i class="mdi-editor-attach-money prefix"></i>
                {!!Form::label('importe','importe : *')!!}
                {!!Form::number('importe',null, ['id'=>'importe','placeholder'=>''])!!}
            </div>
            <div class="col s12 m12 l6">
                <div class="row">
                    <div class="input-field col s1">
                        <i class="mdi-editor-vertical-align-center prefix"></i>
                    </div>
                    <div class="col s11">
                        {!!Form::label('importe','Forma de Pago: *')!!}
                        {!! Form::select('concepto', $concepto,null, ['id'=>'concepto1'])!!}
                    </div>
                </div>
            </div>
        </div>
        <div  id="cuentabanco" style="display: none" class="col s12">
            <div class="row">
                <div class="input-field col s1">
                    <i class="mdi-file-folder-shared prefix"></i>
                </div>
                <div class="col s11">
                    {!!Form::label('glosa','Numero de Cuenta: ')!!}
                    <select id="cuenta">
                    </select>
                </div>
            </div>
        </div>
        <div id="cuentacheque" style="display: none" class="col s12 input-field">
            <i class="prefix mdi-image-blur-linear"></i>
            {!!Form::label('glosa','Numero de Cuenta: ')!!}
            {!!Form::number('importe',null, ['id'=>'cheque1','placeholder'=>''])!!}
        </div>
        <div class="col s12 input-field">
            <i class="mdi-editor-border-color prefix"></i>
            {!!Form::label('glosa','Glosa: ')!!}
            {!!Form::text('glosa',null, ['id'=>'glosa','class'=>'materialize-textarea','placeholder'=>''])!!}
        </div>
    </div>
    <div class="modal-footer">
        <a   class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
        {!!link_to('#', $title='Actualizar', $attributes = ['id'=>'actualizar', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
</div>