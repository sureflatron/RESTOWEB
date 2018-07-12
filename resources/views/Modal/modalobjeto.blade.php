<!--                              Modal Nuevo              -->
<div id="modal1" class="modal modal-fixed-footer">
    <div class="modal-content">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <h4><strong>Nuevo Objeto</strong></h4>
        <div class="divider"></div>
        <form class="col s12">
            <div class="row">
                <div class="input-field col s6">
                    <span for="first_name">Nombre</span>
                    <input   id="nombre" type="text"  >
                </div>
                <div class="input-field col s6">
                    <span  >Tipo de objeto</span>

                    <select id="tipoObjeto">
                        <option value="" disabled selected>Selecione Tipo objeto</option>
                        <option value="Formulario"    >Formulario</option>
                        <option value="Reporte"    >Reporte</option>
                    </select>

                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <input    id="urlObjeto" type="text"  >
                    <label for="disabled">Url Objeto</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <h6  >Habilitado</h6>
                    <input name="group1" type="radio" id="test1" />
                    <label for="test1">Si</label>
                    <input name="group1" type="radio" id="test2" />
                    <label for="test2">No</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <h6  >Visible en menu</h6>  
                    <input name="group2" type="radio" id="test3" />
                    <label for="test3">Si</label>
                    <input name="group2" type="radio" id="test4" />
                    <label for="test4">No</label>
                </div>
                <br>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <h6  >Modulo</h6>
                    <select id="modulo">
                        <option value="" disabled selected>Selecione Modulo</option>
                    </select>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <a  id="closemodal" class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
        {!!link_to('#', $title='Guardar', $attributes = ['id'=>'guardar', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
</div>
<!--                             Modal Actualizacion            -->
<div id="modal2" class="modal modal-fixed-footer">
    <div class="modal-content">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <input type="hidden" id="idactualizar">
        <h4>Actualizar Objeto</h4>
        <div class="divider"></div>
        <form class="col s12">
            <div class="row">
                <div class="input-field col s6">
                    <span for="first_name">Nombre</span>
                    <input   id="nombres" type="text"  >
                </div>
                <div class="input-field col s6">
                    <span for="last_name">Tipo de objeto</span>
                    <input id="tipoObjetos" type="text"  >
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <span for="disabled">Url Objeto</span>
                    <input    id="urlObjetos" type="text"  >
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <h6  >Habilitado</h6>
                    <input name="grp1" type="radio" id="test1s">
                    <label for="test1s">Si</label>
                    <input name="grp1" type="radio" id="test2s">
                    <label for="test2s">No</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <h6  >Visible en menu</h6>  
                    <input name="grp2" type="radio" id="test3s">
                    <label for="test3s">SI</label>
                    <input name="grp2" type="radio" id="test4s">
                    <label for="test4s">No</label>
                </div>
                <br>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <h6  >Modulo</h6>
                    <select id="modulos">
                    </select>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <a   class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
        {!!link_to('#', $title='Actualizar', $attributes = ['id'=>'actualizar', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
</div>

