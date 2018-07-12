 
<!-- Modal Structure -->
<div id="modal1" class="modal">
    <div class="modal-content">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <input type="hidden" id="id">
        <h4><strong>Actualizar Horario</strong></h4>
        <div class="divider"></div>
        <div class="col s12">
            <label for="diasactua">Dia</label>
            <select id="diasactua">
                <option value="Lunes">Lunes</option>
                <option value="Martes">Martes</option>
                <option value="Miercoles">Miércoles</option>
                <option value="Jueves">Jueves</option>
                <option value="Viernes">Viernes</option>
                <option value="Sabado">Sábado</option>
                <option value="Domingo">Domingo</option>
            </select>
        </div>
        <div class="input-field col s12">
            <i class="mdi-action-history prefix"></i>
            <label for="achoraentrada" class="active">Hora de entrada</label>
            <input type="time"  id="achoraentrada"    step="1800" placeholder="">
        </div>
        <div class="input-field col s12">
            <i class="mdi-action-history prefix"></i>
            <label for="achorasalida" class="active">Hora de Salida</label>
            <input type="time"  id="achorasalida"   step="1800" placeholder="">
        </div>
    </div>
    <div class="modal-footer">
        <div class="divider"></div>
        <a   class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
        {!!link_to('#', $title='Actualizar', $attributes = ['id'=>'actualizar', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
</div>
