<!-- Modal Structure -->
<div id="modal1" class="modal">
    <div class="modal-content">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <input type="hidden" id="id">
        <input type="hidden" id="idperfils">
        <input type="hidden" id="idobjetoss">
        <h4><strong>Actualizar Perfil-Objeto</strong></h4>
        <div class="divider"></div>
        <div class="row">
            <div>
                <input type="hidden" value="" id="idobjetos">
                <h5 id="nombreObjetos"></h5>
            </div>
            <div> 
                <input type="checkbox" id="Guardarss" />
                <label for="Guardarss">Guardar</label>
            </div>
            <div> 
                <input type="checkbox" id="Modificars" />
                <label for="Modificars">Modificar</label>
            </div>
            <div> 
                <input type="checkbox" id="Eliminars" />
                <label for="Eliminars">Eliminar</label>
            </div>
            <div  > 
                <input type="checkbox" id="Listars" />
                <label for="Listars">Listar</label>
            </div>
            <div> 
                <input type="checkbox" id="VerReportes" />
                <label for="VerReportes">VerReporte</label>
            </div>
            <div> 
                <input type="checkbox" id="Imprimirs" />
                <label for="Imprimirs">Imprimir</label>
            </div>

        </div>
    </div>
    <div class="modal-footer">
        <a   class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
        {!!link_to('#', $title='Actualizar', $attributes = ['id'=>'actualizar', 'class'=>'btn btn-primary'], $secure = null)!!}

    </div>
</div>
































