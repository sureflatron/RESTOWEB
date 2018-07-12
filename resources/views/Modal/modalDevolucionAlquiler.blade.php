<!--Modal Structure -->
<div id="modal1" class="modal modal-fixed-footer">
    <div class="modal-content">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <h4><strong>DEVOLUCION DE PRODUCTOS</strong></h4>
        <div class="divider"></div>
        <div>
            <div class="col s12">
                <h6><strong><i class="mdi-action-turned-in-not"></i>CODIGO DE ALQUILER:</strong>&nbsp; &nbsp; &nbsp; &nbsp;<span id="idvent"></span></h6>
            </div>
            <div class="col s12">
                <div class="row">
                    <div class="col s12 m6 l6">
                        <h6><strong><i class="mdi-action-perm-identity"></i>CLIENTE:</strong>&nbsp; &nbsp; &nbsp; &nbsp;<span id="cliente"></span></h6>
                    </div>
                    <div class="col s12 m6 l6">
                        <h6><strong><i class="mdi-action-picture-in-picture"></i>NIT:</strong>&nbsp; &nbsp; &nbsp; &nbsp;<span id="nit"></span></h6>
                    </div>
                </div>
            </div>
            <div class="col s12">
                <h6><strong>GARANTIA:</strong></h6>
            </div>
            <div class="col s12">
                <h5><i class="mdi-editor-attach-money prefix"></i><span id="mostrardescuento"></span></h5>
            </div>
        </div>
        <div class="col s12">
            <div><h6><strong>PRODUCTO(S) ALQUILADOS</strong></h6></div>
        </div>
        <div class="col s12" style="padding-top: 20px;">
            <div id="productos" class="row">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a   class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
        {!!link_to('#', $title='DEVOLVER', $attributes = ['id'=>'devolver', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
</div>