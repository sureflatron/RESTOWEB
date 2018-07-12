<!-- Modal Structure -->
<div id="modal1" class="modal">
    <div class="modal-content">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <input type="hidden" id="id"> 
        <h4><strong>Actualizar Compra</strong></h4>   
        <div class="divider"></div>
        <div class="col s12">
            <label>Producto:</label><h5 id="nombre"> </h5>
        </div>
        <div class="input-field col s12">
            <i class="mdi-action-description prefix"></i>
            <label for="cantidads">Cantidad :</label>
            <input type="text" id="cantidads" placeholder="" onkeypress="return isNumberKey(this);">
        </div>
        <div class="input-field col s12">
            <i class="mdi-action-label-outline prefix"></i>
            <label for="costos">Costo:</label>
            <input type="text" id="costos" placeholder="" onkeypress="return NumCheck(event, this);">
        </div>
    </div>
    <div class="modal-footer">
        <a   class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
        <a   class="btn" id="actualizar">Actualizar</a>
    </div>
</div>


