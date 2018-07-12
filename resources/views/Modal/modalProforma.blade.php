<div id="modal1" class="modal bottom-sheet modalF modal-fixed-footerF modal-fixed-footer">
    <div class="modal-content">
        <div class="row">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
            <input type="hidden" id="id">
            <input type="hidden" id="id">
            <input type="hidden" id="iddescuento">
            <input type="hidden" id="totaldefacturas">
            <input type="hidden" id="totaldefacturassindescuento">
            <input type="hidden" id="descuentoasignado" value="0">
            <h4 style="text-align: center;"><strong>Proceso Cobro de Proforma</strong></h4>
            <div class="divider"></div>
            <div class="col s12 m6 l6">
                <div class="row">
                    <div id="modalcobro" class="row" style="display: block;">
                        <div class="col s12">
                            <h6><strong>CONCESIONARIO:</strong></h6>
                        </div>
                        <div class="input-field col s12">
                            <i class="mdi-action-account-circle prefix"></i>
                            @if( $idCliente == null)
                            <input type="text" list="clienteslist2" name="buscar_cliente" autocomplete="off" class="input-field" id="cliNombre2" required>
                            @else
                            <input type="text" list="clienteslist2" name="buscar_cliente" autocomplete="off" class="input-field" id="cliNombre2" value="{{$nombre}}" required>
                            @endif
                            <datalist id="clienteslist2">
                                @foreach($clientes as $datos)
                                <option data-id='{{ $datos->id }}' value="{{ $datos->nombre }}"> NIT: {{ $datos->nit }}
                                    @endforeach
                            </datalist>
                        </div>
                    </div>
                    <div class="col s12">
                        <h6><strong>FACTURADO A:</strong></h6>
                    </div>
                    <div class="input-field col s12 m6 l6">
                        <i class="mdi-action-picture-in-picture prefix"></i>
                        {!!Form::label('NIT','NIT: ')!!}
                        @if($nit == null)
                        <input type="text" value="0" id="nit" onkeyup="buscarcliente();" placeholder="" onkeypress="return isNumberKey(this);"/> 
                        @else
                        <input type="text" value="{{ $nit }}" id="nit" onkeyup="buscarcliente();" placeholder="" onkeypress="return isNumberKey(this);"/> 
                        @endif
                    </div>
                    <div class="input-field col s12 m6 l6">
                        <i class="mdi-action-perm-identity prefix"></i>
                        {!!Form::label('Nombre','Nombre: ')!!}
                        @if($nit == null)
                        <input type="text" value="SIN NOMBRE" id="razonsocial" placeholder=""/>  
                        @else
                        <input type="text" value="{{ $razon }}" id="razonsocial" placeholder=""/>  
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col s12">
                        <h5><strong>TOTAL PROFORMA: </strong><i>&nbsp; $us &nbsp;&nbsp;</i><span id="totalfijo">0</span></h5>
                    </div>
                </div>
                <div class="divider"></div>
                <div class="row">
                    <div class="col s12">
                        <h6><strong>SELECCIONE EL TIPO DE DESCUENTO</strong></h6>
                    </div>
                </div>
                <div>
                    <input class="des" name="groupD" type="radio" id="descuentoP" value="1" checked />
                    <label for="descuentoP" style="font-size: 15px;">Descuento Porcentual(%)</label>
                    <input class="des" name="groupD" type="radio" id="descuentoI" value="2" />
                    <label for="descuentoI" style="font-size: 15px;">Precio final de Venta($us)</label>
                </div>
                <div class="row" id="descuentoPor" style="display: block">
                    <div class="col s12">
                        <h6><strong>DESCUENTO:</strong></h6>
                    </div>
                    <div class="col s12 m6 l6">
                        <select id="descuentos"></select>
                    </div>
                    <div class="col s12 m6 l6">
                        <h5>Importe es: $us&nbsp;<span id="mostrardescuento">0</span></h5>
                    </div>
                </div>
                <div class="row" id="descuentoImpor" style="display: none;">

                    <div class="col s12">
                        <h6><strong>NUEVO PRECIO FINAL:</strong></h6>
                    </div>
                    <div class="col s12 m6 l6">
                        <input type='text' name='' value='0' id='totalcondescuento' min='0' onKeyUp="calcular_total_Modificado();" onkeypress="run(this)">
                    </div>
                    <div class="col s12 m6 l6">
                        <h5>Descuento es:  <span id="mostrarimporte"> 0 %</span></h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12">
                        <h5><strong>TOTAL A COBRAR:&nbsp;</strong><i>$us &nbsp; </i><span id='totalacobrarcondescuentoenlaventa'></span></h5>
                    </div>
                </div>
                <div>
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

            <div class="col s12 m6 l6">
                <div class="col s12" style="display: none;">
                    <h6><strong>VENDER CON O SIN FACTURA:</strong></h6>
                </div>
                <div class="col s12">

                    <div style="display: none;">
                        <input name="pago" type="radio" id="sifactura"/>
                        <label for="sifactura">FACTURA</label>
                        <input name="pago" type="radio" id="nofactura" checked/>
                        <label for="nofactura">RECIBO(Nota De Venta)</label>
                    </div>

                </div>
                <div class="col s12">
                    <h6><strong>FORMA DE PAGO:</strong></h6>
                </div>
                <div class="col s12">
                    <!--<div class="col s12 m6 l6">-->
                    <input  class="group1" name="group1" type="radio" id="test1" value="contado"  checked/>
                    <label for="test1">Efectivo</label>
                    <!--</div>-->
                    <div class="col s12 m6 l6" style="display: none;">
                        <input class="group1" name="group1" type="radio" id="test6" value="credito" />
                        <label for="test6">Credito</label>
                    </div>-

                    <input  class="group1" name="group1" type="radio" id="test2" value="tarjeta"/>
                    <label for="test2">Tarjeta Debito</label>
                    <input  class="group1" name="group1" type="radio" id="test3" value="tarjeta"/>
                    <label for="test3">Tarjeta Credito</label>
                    <input  class="group1" name="group1" type="radio" id="test4" value="deposito"/>
                    <label for="test4">Deposito a Banco</label>
                    <input  class="group1" name="group1" type="radio" id="test5" value="cheque"/>
                    <label for="test5">Cheque</label>

                </div>
                <div class="col s12">
                    <div class="row" id="pagoEfectivo" >
                        <div class="col s12">
                            <h6><strong>CONCESIONARIO PAGO CON:</strong></h6>
                        </div>
                        <div class="col s12">
                            <div style="display: none;">
                                <input  class="efec" name="efec" type="radio" id="bs" value="bs" checked />
                                <label for="bs">Bs</label>
                                <input  class="efec" name="efec" type="radio" id="sus" />
                                <label for="sus">$us</label>
                            </div>
                        </div>


                        <div class="col s12" style="display: none;">
                            <div class="input-field col s12 m6 l6">
                                <i class="mdi-action-payment prefix"></i>
                                <label for="pago">PAGO CON:</label>
                                <input type="text" class="importe_linea"  id="pago" onKeyUp="calcular_total();" onkeypress="run(this)" placeholder=""/> 
                            </div>
                            <div class="input-field col s12 m6 l6">
                                <i class="prefix"><h5>$us</h5></i>
                                <label for="cambio">CAMBIO:</label>
                                <input disabled type="text" id="cambio" value="0" placeholder=""/> 
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col s12">
                    <div class="row" id="pagoTarjeta" style="display: none;">
                        <div class="col s12">
                            <h6><strong>CONCESIONARIO PAGO CON:</strong></h6>
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
                            <h6><strong>CONCESIONARIO PAGO CON:</strong></h6>
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
                            <h6><strong>CONCESIONARIO PAGO CON:</strong></h6>
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
                <div class="col s12">
                    <div id="pagoCredito" class="row"  style="display: none;">
                        <div class="col s12">
                            <h6><strong>CONCESIONARIO PAGO CON:</strong></h6>
                        </div>
                        <div class="col s12">
                            <label>Porcentaje del Pago Inicial:</label>
                            <div id="porcentajepago"></div>
                        </div>
                        <br>
                        <br>
                        <div class="col s12">
                            <div class="input-field col s12 m6 l6">
                                <i class="mdi-communication-call-received prefix"></i>
                                {!!Form::label('A CUENTA(PAGO INICIAL)','A CUENTA(PAGO INICIAL): *')!!}
                                <input type="text" value="0" id='aCuenta' onkeypress="run(this)" onkeydown="calcular_totalCredito();" onKeyUp="calcularporcentaje();" onkeypress="run(this)" placeholder=""/>
                            </div>
                            <div class="input-field col s12 m6 l6">
                                <i class="prefix"><h5>$us</h5></i>
                                {!!Form::label('SALDO','SALDO:')!!}
                                <input disabled type="text" value="0" id='saldo' placeholder="" />
                            </div>
                            <div class="input-field col s12 m12 l12">
                                <i class="mdi-image-filter-1 prefix"></i>
                                {!!Form::label('NUMERO DE CUOTAS','NUMERO DE CUOTAS: *')!!}
                                <input type="text" value="0" id='nrCuotas' placeholder="" onkeypress="return isNumberKey(this);"/>
                            </div>
                            <div class="col s12">
                                <label>SELECCONE EL PLAN DE PAGOS</label>
                                <select class="browser-default" id="cobrarCada">
                                    <option value="" disabled selected>Elija un plan de Pagos</option>
                                    <option value="1">Diario</option>
                                    <option value="7">Semanal</option>
                                    <option value="15">Cada 15 dias</option>
                                    <option value="30">Mensual</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <!--                <div class="input-field col s12 m12 l12" style=" display: none">
                                                    <i class="mdi-action-today prefix"></i>
                                                    {!!Form::label('FECHA DE ENTREGA','FECHA DE ENTREGA: *')!!}
                                                    <input type="date" name="" id='cobrarCada' class="datepicker" placeholder=""/>
                                                </div>-->
                <div class="col s12">
                    <div class="input-field col s12">
                        <i class="mdi-action-payment prefix"></i>
                        <label for="pago">PAGO ANTICIPADO:</label>
                        <input type="text" class="importe_linea" id="cobroAnticipo" placeholder=""/> 
                    </div>
                </div>

                <div class="input-field col s12">
                    <i class="mdi-action-label prefix"></i>
                    {!!Form::label('FECHA DE ENTREGA','FECHA DE ENTREGA:')!!}
                    <input type="text" class="importe_linea"  id="fechaEntrega" placeholder=""/> 
                </div>
                <div class="input-field col s12">
                    <i class="mdi-communication-forum prefix"></i>
                    {!!Form::label('OBSERVACIONES','OBSERVACIONES:')!!}
                    <textarea class="materialize-textarea" length="700" id='observaciones' maxlength="700"></textarea>
                </div>
                <div class="col s12"    style="display: none;"              >
                    <h6><strong>¿REALIZAR VENTA CON ENVIO?</strong></h6>
                    <div>
                        <input  class="group2" name="groupenvio1" type="radio" id="test22" value="noenvio"  />
                        <label for="test22">SI</label>
                        <input  class="group2" name="groupenvio1" type="radio" id="test11" value="envio" checked />
                        <label for="test11">NO</label>
                    </div>
                    <div class="row" id="cuentabanco" style="display: none">
                        <div class="col s12 m8 l5">
                            <h5><strong>Numero de Cuenta:</strong></h5>
                            <select id="cuenta1">
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col s12">
                    <div class="row" id="pagoEnvio" class="row" style="display: none;">
                        <input type="hidden" id="id">
                        <div class="col s12">
                            <h6><strong>PROGRAMAR ENTREGA</strong></h6>
                        </div>
                        <div class="divider"></div>
                        <div class="col s12">
                            <div class="row">
                                <div class="input-field col s12 m12 l6">
                                    <span>Fecha Entrega:</span>
                                    <input id="fechaentrega" type="text" class="datepicker">
                                </div>
                                <div class="input-field col s12 m12 l6">
                                    <span>Hora de Salida:</span>
                                    <input id="horaentrega" type="time" name="time" >
                                </div>
                            </div>
                        </div>
                        <div id="informaciondelenvio" class="col s12">
                            <div class="row">
                                <div class="col s12">
                                    <h6><strong>DETALLE DE ENVIO A DOMICILIO</strong></h6>
                                </div>
                                <div class="col s12">
                                    <div class="row">
                                        <div class="input-field col s12 m6 l6">
                                            <span>Persona Que Recibe Envio:</span>
                                            <input id="personarecibeenvio" type="text"  >
                                        </div>
                                        <div class="input-field col s12 m6 l6">
                                            <span>Cedula de Identidad:</span>
                                            <input id="ci" type="number"  >
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col s12 m12 l4">
                                            <span>Celular o Telefono:</span>
                                            <input   id="celulars" type="number" value="0"  >
                                        </div>
                                        <div class="input-field col s12 m12 l4">
                                            <span>Ciudad:</span>
                                            {!! Form::select('Ciudad', $Ciudad ,null, ['id'=>'ciudadd'])!!}
                                        </div>
                                        <div class="input-field col s12 m12 l4">
                                            <span>Importe transporte ($us):</span>
                                            <input   id="importetransporte" type="number" value="0"  >
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="input-field col s12">
                                            <span>Direccion:</span>
                                            <textarea id="dirreccionenvio" class="materialize-textarea" length="230" maxlength="230" for="b"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
        {!!link_to('#', $title='Guardar', $attributes = ['id'=>'guardarfactura', 'class'=>'btn btn-primary' ], $secure = null)!!}
    </div>
</div>


<!-- MODAL -->
<div id="modal2" class="modal modal-fixed-footer">
    <div class="modal-content">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <input type="hidden" id="id">
        <h4><strong>Agregar Concesionario</strong></h4>
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

<!-- Modal Structure -->
<div id="modal3" class="modal modal-fixed-footer">
    <div class="modal-content">
        <input type="hidden" id="id">
        <h4 class="center-align">Programar Entrega</h4>
        <div class="divider"></div>
        <div class="row">
            <div class="input-field col s6">
                <span> Fecha Entrega:</span>
                <input id="fechaentrega" type="date" >
            </div>
            <div class="input-field col s3">
                <span  > Hora Entrega:</span>
                <input   id="horaentrega" type="time" name="time" >
            </div>
            <div class="input-field col s3">
                <span  > Minuto de Tolerancia:</span>
                <p class="range-field">
                    <input type="range" id="tolerancia" min="0" max="59" value="0" />
                </p>
            </div>
        </div>
        <span > Envío  a Domicilio:</span>
        <input class="with-gap" name="group80" type="radio" id="sienviodomiciolio" onclick="mostrardatosdeenvio();" />
        <label for="sienviodomiciolio">SI</label>
        <input class="with-gap" name="group80" type="radio" id="noenviodomiciolio" onclick="ocultardatosdeenvio();" />
        <label for="noenviodomiciolio">NO</label>
        <div id="informaciondelenvio" style='display:none;'>
            <h5>DETALLE DE ENVIO A DOMICILIO</h5>
            <div class="row">
                <div class="input-field col s6">
                    <span>   Persona  Recibe envió:</span>
                    <input id="personarecibeenvio" type="text"  >
                    <span>  Direccion :</span>
                    <textarea id="dirreccionenvio" class="materialize-textarea" length="230" maxlength="230" for="b"></textarea>
                    <span>    Cobro al entregar:</span>
                    <input class="with-gap" name="group81" type="radio" id="sicobro" />
                    <label for="sicobro">SI</label>
                    <input class="with-gap" name="group81" type="radio" id="nocobro"  />
                    <label for="nocobro">NO</label>
                </div>
                <span>Importe transporte:</span>
                <input   id="importetransporte" type="number" value="0"  >
                <div class="input-field col s6">
                    <div id="map"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a   class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
        {!!link_to('#', $title='Guardar', $attributes = ['id'=>'programarentrega', 'class'=>'btn' ], $secure = null)!!}
    </div>
</div>

<div id="mail-app" class="section">
    <div id="modal4" class="modal">
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
                            <li><a href="javascript:cambiarcanidad();"><i class="modal-action mdi-content-save"></i></a>
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
                    <input type="hidden" id="idproC"/>
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
<div id="modal5" class="modal modal-fixed-footer">
    <div class="modal-content">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <input type="hidden" id="id">
        <center><h4><strong>Agregar Chasis Disponibles</strong></h4></center>
        <div class="divider"></div>
        <div class="row">
            <div class="input-field col s12 m12 l12">
                <div class="col s12 m6 l12">
                    <table class="responsive-table">
                        <thead>
                            <tr>
                                <th>Numero de Compra</th>
                                <th>Numero de Contenedor</th>
                                <th>Numero de Chasis</th>
                                <th>Numero de Motor</th>
                                <th>Seleccionar</th>
                            </tr>
                        </thead>
                        <tbody id="resultadoChasis">
                        </tbody>
                    </table>
                </div> 
            </div>

        </div>


    </div>
    <div class="modal-footer">
        <a   class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
        {!!link_to('#', $title='Guardar', $attributes = ['id'=>'guardarChasis', 'class'=>'btn btn-primary' ], $secure = null)!!}
    </div>
</div>