<div id="modalGasto" class="modal">
    <div class="modal-content">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <input type="hidden" id="idactualizar">
        <h4><strong>Actualizar Moneda</strong></h4>
        <div class="divider"></div>
        <div class="row">
            <div class="col s12 m6 l6">
                <i class="mdi-action-account-box prefix"></i>
                {!!Form::label('Proveedor','Proveedor:')!!}
                {!! Form::select('Proveedor', $proveedor,null, ['id'=>'proveedorModal','placeholder'=>'Seleccione un Proveedor'])!!}
            </div>
            <div class="col s12 m6 l6">
                <i class="mdi-action-store prefix"></i>
                    {!!Form::label('Gasto','Gasto:')!!}
                    {!! Form::select('Gasto', $gasto,null, ['id'=>'gastoCompraModal'])!!}
            </div>
        </div>

        <div class="row">
            <div  class="col s12 m6 l6">
                {!!Form::label('Importe','Importe:')!!}
                {!!Form::number('Importe',null, ['id'=>'importeModal'])!!}
            </div>
            <div class="col s12 m6 l6">
                <i class="mdi-communication-comment prefix"></i>
                {!!Form::label('Afecta el Gasto','Afecta el Gasto:')!!}
                <div class="row">
                    <div class="input-field col s12 m6 l6">
                        <input name="group1Modal" type="radio" id="test1Modal" checked />
                        <label for="test1Modal"><strong>SI</strong></label>
                    </div>
                    <div class="input-field col s12 m6 l6">
                        <input name="group1Modal" type="radio" id="test2Modal" />
                        <label for="test2Modal"><strong>NO</strong></label>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col s12 m6 l6">
                <i class="mdi-communication-comment prefix"></i>
                {!!Form::label('Forma de Pago','Forma de Pago:')!!}
                {!! Form::select('Forma de Pago', $concepto,null, ['id'=>'conceptoModal'])!!}
            </div>
            <div class="col s12 m6 l6" id="cuentabancoModal" style="display: none">
                <i class="mdi-action-turned-in-not prefix"></i>
                {!!Form::label('Nro de Cuenta Bancaria','Nro de Cuenta Bancaria:')!!}
                <select id="cuentaBancariaModal">
                </select>
            </div>
            <div class="col s12 m6 l6" id="cuentachequeModal" style="display: none">
                <i class="mdi-communication-call prefix"></i>
                {!!Form::label('Nro de Cuenta','Nro de Cuenta:')!!}
                {!!Form::number('Cheque',null, ['id'=>'chequeModal','placeholder'=>'Numero de Cuenta'])!!}
            </div>
        </div>

    </div>
    <div class="modal-footer">
        <a   class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
        {!!link_to('#', $title='Actualizar', $attributes = ['id'=>'actualizarGasto', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
</div>