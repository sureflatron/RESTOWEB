<div id="mail-app" class="section">
    <input type="hidden" id="dolar" value="{{ $bs }} ">
    <div id="modal1" class="modal bottom-sheet modalF modal-fixed-footerF modal-fixed-footer">
        <div class="modal-content">
            <nav class="naranja">
                <div class="nav-wrapper">
                    <div class="left col s12 m5 l5">
                        <ul>
                            <li><a href="#!" class="email-menu"><i class="modal-action modal-close  mdi-hardware-keyboard-backspace"></i></a>
                            </li>
                            <li><a href="#!" class="email-type">COBRAR ALQUILER</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col s12 m7 l7">
                        <ul class="right">
                            <li><a href="#!" id="cobrarventa"><i class="mdi-content-send"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
        <div class="model-email-content" style="padding-top: 70px;">
            <div class="row">
                <div class="col s12 m6 l6">
                    <div id="modalcobro" class="row" style="display: block;">
                        <div class="col s12">
                            <h6><strong>CLIENTE:</strong></h6>
                        </div>
                        <div class="input-field col s12">
                            <i class="mdi-action-account-circle prefix"></i>
                            <input type="text" list="clienteslist2" name="buscar_cliente" autocomplete="off" class="input-field" id="cliNombre2" required>
                            <datalist id="clienteslist2">
                                @foreach($clientes as $datos)
                                <option data-id='{{ $datos->id }}' value="{{ $datos->nombre }}"> NIT: {{ $datos->nit }}
                                    @endforeach
                            </datalist>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12">
                            <h6><strong>FACTURADO A:</strong></h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12 m6 l6">
                            <i class="mdi-action-picture-in-picture prefix"></i>
                            {!!Form::label('NIT','NIT: ')!!}
                            <input type="text" value="0" id="nit" onkeyup="buscarcliente();" placeholder="" onkeypress="return isNumberKey(this);"/> 
                        </div>
                        <div class="input-field col s12 m6 l6">
                            <i class="mdi-action-perm-identity prefix"></i>
                            {!!Form::label('Nombre','Nombre: ')!!}
                            <input type="text" value="SIN NOMBRE" id="razonsocial" placeholder=""/>  
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12">
                            <h6><strong>GARANTIA POR ALQUILER:</strong></h6>
                        </div>
                        <div class="input-field col s12">
                            <i class="mdi-image-style prefix"></i>
                            <input id="impgarantia" type="text" value="0" placeholder="" onkeypress="return isNumberKey(this);"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12">
                            <h5><strong>TOTAL ALQUILER: </strong><i class="mdi-editor-attach-money prefix"></i><span id="totalfijo">0</span></h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12"> 
                            <table class="responsive-table striped">
                                <thead>
                                    <tr>
                                        <th data-field="id">Importe</th>
                                        <th data-field="name">Literal</th>
                                    </tr>
                                </thead>
                                <tbody id="datosfactura">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col s12 m6 l6">
                    <div class="row">
                        <div class="col s12">
                            <h6><strong>VENDER CON O SIN FACTURA:</strong></h6>
                        </div>
                        <div class="col s12">
                            <input name="pago" type="radio" id="sifactura" />
                            <label for="sifactura">FACTURA</label>
                            <input name="pago" type="radio" id="nofactura" checked/>
                            <label for="nofactura">RECIBO(Nota De Venta)</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12">
                            <h6><strong>FORMA DE PAGO:</strong></h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12">
                            <input  class="group1" name="group1" type="radio" id="test1" value="contado"  checked/>
                            <label for="test1">Efectivo</label>
                            <input  class="group1" name="group1" type="radio" id="test2" value="tarjeta"/>
                            <label for="test2">Tarjeta Debito</label>
                            <input  class="group1" name="group1" type="radio" id="test3" value="tarjeta"/>
                            <label for="test3">Tarjeta Credito</label>
                            <input  class="group1" name="group1" type="radio" id="test4" value="deposito"/>
                            <label for="test4">Deposito a Banco</label>
                            <input  class="group1" name="group1" type="radio" id="test5" value="cheque"/>
                            <label for="test5">Cheque</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12">
                            <div class="row" id="pagoEfectivo" >
                                <div class="col s12">
                                    <h6><strong>CLIENTE PAGO CON:</strong></h6>
                                </div>
                                <div class="col s12">
                                    <input  class="efec" name="efec" type="radio" id="bs" value="bs" checked />
                                    <label for="bs">Bs</label>
                                    <input  class="efec" name="efec" type="radio" id="sus" />
                                    <label for="sus">$us</label>
                                </div>
                                <div class="col s12">
                                    <div class="input-field col s12 m6 l6">
                                        <i class="mdi-action-payment prefix"></i>
                                        <label for="pago">PAGO CON:</label>
                                        <input type="text" class="importe_linea"  id="pago" onKeyUp="calcular_total();" onkeypress="calcular_total();" placeholder=""/> 
                                    </div>
                                    <div class="input-field col s12 m6 l6">
                                        <i class="mdi-editor-attach-money prefix"></i>
                                        <label for="cambio">CAMBIO:</label>
                                        <input disabled type="text" id="cambio" value="0" placeholder=""/> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col s12">
                            <div class="row" id="pagoTarjeta" style="display: none;">
                                <div class="col s12">
                                    <h6><strong>CLIENTE PAGO CON:</strong></h6>
                                </div>
                                <div class="col s12">
                                    <div class="input-field col s12 m6 l6">
                                        <i class="mdi-social-person-outline prefix"></i>
                                        <label for="titular">TITULAR:</label>
                                        <input type="text" class="importe_linea"  id="titular" placeholder=""/> 
                                    </div>
                                    <div class="input-field col s12 m6 l6">
                                        <i class="mdi-action-payment prefix"></i>
                                        <label for="tarjeta">NRO. DE TARJETA:</label>
                                        <input type="text" id="tarjeta"  placeholder=""/> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col s12">
                            <div class="row" id="pagoDeposito" style="display: none;">
                                <div class="col s12">
                                    <h6><strong>CLIENTE PAGO CON:</strong></h6>
                                </div>
                                <div class="col s12">
                                    <div class="input-field col s12 m6 l6">
                                        <i class="mdi-social-person-outline prefix"></i>
                                        <label for="deposita">QUIEN DEPOSITA:</label>
                                        <input type="text" class="importe_linea"  id="deposita" placeholder=""/> 
                                    </div>
                                    <div class="input-field col s12 m6 l6">
                                        <i class="mdi-editor-attach-money prefix"></i>
                                        <label for="nrotran">NRO. DE TRANSACCION:</label>
                                        <input type="text" id="nrotran"  placeholder=""/> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col s12">
                            <div class="row" id="pagoCheque" style="display: none;">
                                <div class="col s12">
                                    <h6><strong>CLIENTE PAGO CON:</strong></h6>
                                </div>
                                <div class="col s12">
                                    <div class="input-field col s12 m6 l6">
                                        <i class="mdi-social-person-outline prefix"></i>
                                        <label for="anombre">A NOMBRE:</label>
                                        <input type="text" class="importe_linea"  id="anombre" placeholder=""/> 
                                    </div>
                                    <div class="input-field col s12 m6 l6">
                                        <i class="mdi-editor-attach-money prefix"></i>
                                        <label for="cheque">NRO. DE CHEQUE:</label>
                                        <input type="text" id="cheque"  placeholder=""/> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12 m12 l12">
                                <i class="mdi-action-today prefix"></i>
                                {!!Form::label('FECHA DE ENTREGA','FECHA DE ENTREGA: *')!!}
                                <input type="date" name="" id='cobrarCada' class="datepicker" placeholder=""/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <i class="mdi-communication-forum prefix"></i>
                                {!!Form::label('OBSERVACIONES','OBSERVACIONES:')!!}
                                <textarea class="materialize-textarea" length="700" id='observaciones' maxlength="700"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="modal2" class="modal">
        <div class="modal-content">
            <nav class="naranja">
                <div class="nav-wrapper">
                    <div class="left col s12 m5 l5">
                        <ul>
                            <li><a href="#!" class="email-menu"><i class="modal-action modal-close  mdi-hardware-keyboard-backspace"></i></a>
                            </li>
                            <li><a href="#!" class="email-type">ACTUALIZAR CANTIDAD</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col s12 m7 l7">
                        <ul class="right">
                            <li><a href="javascript:cambiarcanidad();"><i class="modal-action modal-close  mdi-content-save"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
        <div class="model-email-content">
            <div class="row">
                <div class="col s12">
                    <h6><strong>Producto: </strong><span id="nombreprod"></span></h6>
                    <input type="hidden" id="iddetalleventa"/>
                </div>
                <div class="col s12">
                    <div class="row">
                        <div class="col s12 m6 l6">
                            <p>
                                <strong>Descripcion:</strong>
                                <span id="descripcionprod"></span>
                                <br>
                                <br>
                                <strong>Precio de Venta:</strong>
                                <span id="precioprod"></span>
                                <br>
                                <br>
                                <strong>Cantidad:</strong>
                            </p>
                            <div class="input-field">
                                <i class="prefix mdi-action-shopping-basket"></i>
                                <input type="text" id="cantidadproducto" placeholder="" onkeypress="return isNumberKey(this);"/>
                            </div>
                            <p>
                                <strong>Subtotal:</strong>
                                <span id="subtotalpord"></span>
                            </p>
                        </div>
                        <div class="col s12 m6 l6">
                            <img src="" id="imgprod" alt="ImagenProducto" width="100%" height="90%" class="card-image">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- MODAL -->
<div id="modal3" class="modal modal-fixed-footer">
    <div class="modal-content">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <input type="hidden" id="id">
        <h4><strong>Agregar Cliente</strong></h4>
        <div class="divider"></div>
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <i class="mdi-action-account-circle prefix"></i>
                {!!Form::label('Nombre','Nombre: *')!!}
                {!!Form::text('nombre',null, ['id'=>'nombre'])!!}
            </div>
            <div class="input-field col s12 m6 l6">
                <i class="mdi-action-label-outline prefix"></i>
                {!!Form::label('Abreviatura','Direccion: ')!!}
                {!!Form::text('abreviatura',null, ['id'=>'direccion'])!!}
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <i class="mdi-communication-phone prefix"></i>
                {!!Form::label('Nombre','Telefono Fijo: ')!!}
                {!!Form::text('nombre',null, ['id'=>'telefonoFijo'])!!}
            </div>
            <div class="input-field col s12 m6 l6">
                <i class="mdi-action-settings-cell prefix"></i>
                {!!Form::label('Abreviatura','Celular: ')!!}
                {!!Form::text('abreviatura',null, ['id'=>'celular'])!!}
            </div>
        </div>
        <div class="row">
            <div class="col s12 m6 l6">
                <div class="row">
                    <div class="input-field col s1">
                        <i class="mdi-social-location-city prefix"></i>
                    </div>
                    <div class="col s11">
                        {!!Form::label('Ciudad','Ciudad: *')!!}
                        {!! Form::select('Ciudad', $Ciudad,null,['id'=>'ciudad']) !!}
                    </div>
                </div>
            </div>
            <div class="input-field col s12 m6 l6">
                <i class="mdi-communication-email prefix"></i>
                {!!Form::label('Abreviatura','Correo: ')!!}
                {!!Form::email('abreviatura',null, ['id'=>'correo'])!!}
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <i class="mdi-social-person-outline prefix"></i>
                {!!Form::label('Abreviatura','Razon Social: *')!!}
                {!!Form::text('abreviatura',null, ['id'=>'razonSocial'])!!}
            </div>
            <div class="input-field col s12 m6 l6">
                <i class="mdi-social-person prefix"></i>
                {!!Form::label('Abreviatura','Nit: *')!!}
                {!!Form::text('abreviatura',null, ['id'=>'NIT'])!!}
            </div>
        </div>
        <div class="row">
            <div class="col s12 m6 l6">
                <div class="row">
                    <div class="input-field col s1">
                        <i class="mdi-social-location-city prefix"></i>
                    </div>
                    <div class="col s11">
                        {!!Form::label('Tipo de Cliente','Tipo de Cliente: *')!!}
                        {!! Form::select('Tipo de Cliente', $tipocliente ,null, ['id'=>'tipocliente']) !!}
                    </div>
                </div>
            </div>
            <div class="col s12 m6 l6">
                <div class="row">
                    <div class="input-field col s1">
                        <i class="mdi-social-location-city prefix"></i>
                    </div>
                    <div class="col s11">
                        {!!Form::label('Ciudad','Descuento: *')!!}
                        {!! Form::select('Ciudad', $descuento ,null, ['id'=>'descuento'])!!}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12">
                <i class="mdi-editor-mode-edit prefix"></i>
                {!!Form::label('PREFERENCIAS','PREFERENCIAS:')!!}
                <textarea class="materialize-textarea" length="700" id='preferencias' maxlength="700"></textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a   class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
        {!!link_to('#', $title='Guardar', $attributes = ['id'=>'nuevocliente', 'class'=>'btn btn-primary' ], $secure = null)!!}
    </div>
</div>