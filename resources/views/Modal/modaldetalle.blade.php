<div id="modal10" class="modal">
    <div class="modal-content">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <input type="hidden" id="id">
        <h4>Detalle del producto</h4>
        <div class="divider"></div>
        {!!Form::label('Nombre','Ingrediente : ')!!}
        <h5 id="ingredientelabel"></h5>
        {!!Form::label('Abreviatura','Unidad: ')!!}
        {!! Form::select('unidad', $unidad,null, ['id'=>'unidad']) !!}
        <div>
            {!!Form::label('Cantidad','Cantidad : ')!!}
            {!!Form::text('Cantidad',null, ['id'=>'cantidad'])!!}
        </div>
        {!!Form::label('Costo','Costo : ')!!}
        {!!Form::text('Cantidad',null, ['id'=>'Costoactuaa'])!!}
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
        {!!link_to('#', $title='Actualizar', $attributes = ['id'=>'actualizar', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
</div>