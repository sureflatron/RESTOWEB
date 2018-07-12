<!-- Modal Structure -->
<div id="modal1" class="modal modal-fixed-footer">
    <div class="modal-content">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <h4><strong>Agregar Descuento</strong></h4>
        <div class="divider"></div>
        <div class="input-field col s12">
            <i class="mdi-action-trending-down prefix"></i>
            {!!Form::label('Nombre','Nombre: *')!!}
            {!!Form::text('nombre',null, ['id'=>'nombre'])!!}
        </div>
        <div class="input-field col s12">
            <i class="mdi-content-create prefix"></i>
            {!!Form::label('Descripcion','Descripcion: ')!!}
            {!!Form::text('abreviatura',null, ['id'=>'descripcion'])!!}
        </div>
        <div class="input-field col s12">
            <i class="mdi-action-today prefix"></i>
            {!!Form::label('fechaInicio','Fecha Inicio: *')!!}
            {!!Form::date('fechaInicio',null, ['id'=>'fechaInicio','class'=>'datepicker'])!!}
        </div>
        <div class="input-field col s12">
            <i class="mdi-action-today prefix"></i>
            {!!Form::label('fechaFin','Fecha Fin: *')!!}
            {!!Form::date('fechaFin',null, ['id'=>'fechaFin','class'=>'datepicker'])!!}
        </div>
        <div class="row">
            <div class="input-field col s1">
                <i class="mdi-editor-format-list-numbered prefix"></i>
            </div>
            <div class="col s11">
                {!!Form::label('Descuento','Descuento: *')!!}
                <div id="descuento"></div>
                <!--<input type="range" id="descuento"/>-->
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a id="closemodal"  class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
        {!!link_to('#', $title='Guardar', $attributes = ['id'=>'guardar', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
</div>
<!-- Modal Structure -->
<div id="modal2" class="modal modal-fixed-footer">
    <div class="modal-content">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <input type="hidden" id="id">
        <h4><strong>Actualizar Descuento</strong></h4>
        <div class="input-field col s12">
            <i class="mdi-action-trending-down prefix"></i>
            {!!Form::label('Nombre','Nombre: *')!!}
            {!!Form::text('nombre',null, ['id'=>'nombres','placeholder'=>''])!!}
        </div>
        <div class="input-field col s12">
            <i class="mdi-content-create prefix"></i>
            {!!Form::label('Descripcion','Descripcion:')!!}
            {!!Form::text('abreviatura',null, ['id'=>'descripcions','placeholder'=>''])!!}
        </div>
        <div class="input-field col s12">
            <i class="mdi-action-today prefix"></i>
            {!!Form::label('fechaInicios','Fecha Inicio: *')!!}
            {!!Form::date('fechaInicios',null, ['id'=>'fechaInicios','class'=>'datepicker','placeholder'=>''])!!}
        </div>
        <div class="input-field col s12">
            <i class="mdi-action-today prefix"></i>
            {!!Form::label('fechaFins','Fecha Fin: *')!!}
            {!!Form::date('fechaFin',null, ['id'=>'fechaFins','class'=>'datepicker','placeholder'=>''])!!}
        </div>
        <div class="row">
            <div class="input-field col s1">
                <i class="mdi-editor-format-list-numbered prefix"></i>
            </div>
            <div class="col s11">
                {!!Form::label('Descuento','Descuento: *')!!}
                <div id="descuentos"></div>
                <!--<input type="range" id="descuentos"/>-->
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a   class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
        {!!link_to('#', $title='Actualizar', $attributes = ['id'=>'actualizar', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
</div>
