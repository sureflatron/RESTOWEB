<!-- Modal Structure -->
<div id="modal1" class="modal">
    <div class="modal-content">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <input type="hidden" id="id"> 
        <input type="hidden" id="idproductoinvenatrio">
        <h4><strong>Actualizar Inventario</strong></h4>   
        <div class="divider"></div>
        <h6><strong>Producto:</strong></h6>
        <h5 id="nombre"></h5>
        <h6><strong>Unidad:*</strong></h6>
        {!! Form::select('unidad', $unidad,null, ['id'=>'unidadmedidass']) !!} 
        <h6><strong>Cantidad:*</strong></h6>
        <input type="number" id="cantidads">
        <h6><strong>Costo: *</strong></h6>
        <input type="number" id="costos">
    </div>
    <div class="modal-footer">
        <a class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
        <a class="btn" id="actualizar">Actualizar</a>
    </div>
</div>


