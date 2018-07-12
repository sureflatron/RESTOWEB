<div id="modal5" class="modal modal-fixed-footer">
    <div class="modal-content">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <h3><strong>LISTA DE PAGOS</strong></h3>
        <table id="cuotasCredito" class="responsive-table striped centered">
            <thead>
            <th>Codigo</th>
            <th>Fecha de Pago</th>
            <th>Importe</th>
            </thead>
            <tbody id="cuotasdatos">
            </tbody>
        </table>
    </div>
    <div class="modal-footer">
        <a  href="#" class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
    </div>
</div>
