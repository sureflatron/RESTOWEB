<!-- Modal Structure -->
<div id="modal1" class="modal">
    <div class="modal-content">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <input type="hidden" id="id">
        <h4><strong>Agregar Almacen</strong></h4>
        <div class="divider"></div>
        <div class="input-field col s12">
            <i class="mdi-action-account-balance prefix"></i>
            {!!Form::label('Nombre','Nombre: *')!!}
            {!!Form::text('nombre',null, ['id'=>'nombre'])!!}
        </div>
        <div class="row">
            <div class="row">
                <div class="input-field col s1">
                    <i class="mdi-communication-business prefix"></i>
                </div>
                <div class="col s11">
                    {!!Form::label('sucursal','sucursal: *')!!}
                    {!! Form::select('sucursal', $sucursal,null, ['id'=>'sucursal']) !!}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="row">
                <div class="input-field col s1">
                    <i class="mdi-communication-business prefix"></i>
                </div>
                <div class="col s11">
                    {!!Form::label('responsable','Responsable: *')!!}
                    {!! Form::select('responsable', $empleado,null, ['id'=>'responsable']) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a id="closemodal" class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
        {!!link_to('#', $title='Guardar', $attributes = ['id'=>'guardar', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
</div>

<!-- Modal Structure -->
<div id="modal2" class="modal">
    <div class="modal-content">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <input type="hidden" id="id">
        <h4><strong>Actualizar Almacen</strong></h4>
        <div class="divider"></div>
        <div class="input-field col s12"> 
            <i class="mdi-action-account-balance prefix"></i>
            {!!Form::label('Nombre','Nombre: *')!!}
            {!!Form::text('nombre',null, ['id'=>'nombres','placeholder'=>''])!!}
        </div>
        <div class="row">
            <div class="input-field col s1">
                <i class="mdi-communication-business prefix"></i>
            </div>
            <div class="col s11">
                {!!Form::label('sucursal','sucursal: *')!!}
                {!! Form::select('sucursal', $sucursal,null, ['id'=>'sucursals']) !!}
            </div>
        </div>
        <div class="row">
            <div class="row">
                <div class="input-field col s1">
                    <i class="mdi-communication-business prefix"></i>
                </div>
                <div class="col s11">
                    {!!Form::label('responsable','Responsable: *')!!}
                    {!! Form::select('responsable', $empleado,null, ['id'=>'responsables']) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
        {!!link_to('#', $title='Actualizar', $attributes = ['id'=>'actualizar', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
</div>
