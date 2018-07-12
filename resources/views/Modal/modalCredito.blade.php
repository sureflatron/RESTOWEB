<div id="modal5" class="modal modal-fixed-footer">
    <div class="modal-content">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <h3><strong>LISTA DE PAGOS</strong></h3>
        <table id="cuotasCredito" class="responsive-table striped centered">
            <thead>
            <th>Codigo</th>
            <th>Fecha de Pago</th>
            <th>Importe</th>
            <th>Forma de Pago</th>
<!--            <th>Editar</th>
           <th>Eliminar</th> -->
            </thead>
            <tbody id="cuotasdatos">
            </tbody>
        </table>
    </div>
    <div class="modal-footer">
        <a  href="#" class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
    </div>
</div>
<div id="modal2" class="modal">
    <div class="modal-content">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <h3><strong>DATOS FACTURA</strong></h3>
        <div class="row">
            <div class="col s12">
                <div class="input-field col s12">
                    {!!Form::label('NIT','NIT: ')!!}
                    <input type="number" value="0" id="nit" onkeyup="buscarcliente();" onkeydown="buscarcliente();" onchange="buscarcliente();"/> 
                </div>
                <div class="input-field col s12">
                    {!!Form::label('Nombre','Nombre: ')!!}
                    <input type="text"   value="SIN NOMBRE" id="razonsocial" onfocus="buscarcliente();"/> 
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a  href="#" class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
        {!!link_to('#', $title='GUARDAR', $attributes = ['id'=>'guardar', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
</div>

<div id="modal7" class="modal modal-fixed-footer">
    <div class="modal-content">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <h4><strong>ACTUALIZAR FECHA DE ENTREGA</strong></h4>
        <div class="divider"></div>
        <div class="row">
            <input type="hidden" id="idventafecha">
            <div class="input-field col s12">
                <i class="mdi-editor-insert-invitation prefix"></i>
                <label for="importecuota">FECHA DE ENTREGA:</label>
                <input type="text" id="fechadeentregafe" class="datepicker" placeholder="">
            </div>
            <div class="input-field col s12">
                <i class="mdi-social-person prefix"></i>
                <label for="nombrecliefe">CLIENTE:</label>
                <input type="text" id="nombrecliefe" placeholder="" disabled style="color: black !important;">
            </div>
            <div class="input-field col s12">
                <i class="mdi-editor-attach-money prefix"></i>
                <label for="totalfe">TOTAL VENTA:</label>
                <input type="text" id="totalfe" placeholder="" disabled style="color: black !important;">
            </div>
            <div class="input-field col s12">
                <i class="mdi-editor-border-color prefix"></i>
                <label for="totalfe">OBSERVACIONES:</label>
                <textarea class="materialize-textarea" length="700" id='observacionesfe' maxlength="700" placeholder="" disabled style="color: black !important;"></textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a  href="#" class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
        {!!link_to('#', $title='GUARDAR', $attributes = ['id'=>'actfecha', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
</div>

<div id="mail-app" class="section">
    <div id="modal6" class="modal">
        <div class="modal-content">
            <nav class="naranja">
                <div class="nav-wrapper">
                    <div class="left col s12 m5 l5">
                        <ul>
                            <li><a href="#!" class="email-menu"><i class="modal-action modal-close  mdi-hardware-keyboard-backspace"></i></a>
                            </li>
                            <li><a href="#!" class="email-type">ACTUALIZAR MONTO COBRADO</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col s12 m7 l7">
                        <ul class="right">
                            <li><a href="javascript:actualizarmonto();"><i class="modal-action modal-close  mdi-content-save"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
        <div class="model-email-content">
            <div class="row left-align card-content">
                <input type="hidden" id="cuotapagadaid">
                <input type="hidden" id="saldocuota">
                <div class="input-field col s12">
                    <i class="mdi-action-label prefix"></i>
                    <label for="importecuota">IMPORTE COBRADO:</label>
                    <input type="text" id="importecuotacobrado" placeholder="">
                </div>
                <div class="input-field col s12">
                    <i class="mdi-action-label-outline prefix"></i>
                    <label for="saldocuota">SALDO:</label>
                    <input type="text" id="saldocuotacobrado" placeholder="" disabled style="color: black !important;">
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modal8" class="modal">
    <div class="modal-content">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <input type="hidden" id="idventcancel">
        <h3><strong>CANCELAR CREDITO</strong></h3>
        <div class="divider"></div>
        <div class="row">
            <div class="input-field col s12">
                <i class="mdi-communication-forum prefix"></i>
                <label for="observacionescancel" class="active">Observaciones</label>
                <textarea class="materialize-textarea" length="700" id='observacionescancel' maxlength="700" placeholder=""></textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a  href="#" class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
        {!!link_to('#', $title='GUARDAR', $attributes = ['id'=>'guardarcancel', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
</div>