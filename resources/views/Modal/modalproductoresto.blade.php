
<!-- Modal Structure -->
<div id="modal1" class="modal modal-fixed-footer">
    <div class="modal-content">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <input type="hidden" id="id">
        <h4><strong>Actualizar Producto</strong></h4>
        <div class="divider"></div>
        <div class="row">
            <div class="col s12 m6 l6">
                <label for="categoria" class="active">Categoria: *</label>
                <select id="categoria"></select>
            </div>
            <div class="col s12 m6 l6">
                <h6>Tipo de Producto</h6>                        
                <input name="group1" type="radio" id="test3" checked/>
                <label for="test3">Comida</label>
                <input name="group1" type="radio" id="test4" />
                <label for="test4">Ingrediente</label>
                <input name="group1" type="radio" id="test1" />
                <label for="test1">Item</label>
                <input name="group1" type="radio" id="test2" />
                <label for="test2">Combo</label>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <i class="mdi-content-text-format prefix"></i>
                {!!Form::label('Nombre','Nombre: *')!!}
                {!!Form::text('nombre',null, ['id'=>'nombre','placeholder'=>''])!!}
            </div>
            <div class="col s12 m6 l6">
                <h6>Venta Directa</h6>                        
                <input name="group2" type="radio" id="test8" checked/>
                <label for="test8">SI</label>
                <input name="group2" type="radio" id="test9" />
                <label for="test9">NO</label>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <i class="mdi-editor-border-color prefix"></i>
                {!!Form::label('descripcion','Descripcion: *')!!}
                {!!Form::text('descripcion',null, ['id'=>'descripcion','placeholder'=>''])!!}
            </div>
            <div class="col s12 m6 l6">
                <h6>¿Vender producto con Stock?</h6>
                <input name="group3" type="radio" id="test5" checked/>
                <label for="test5">SI</label>
                <input name="group3" type="radio" id="test6" />
                <label for="test6">NO</label>
            </div>
        </div>
        <div class="row">            
            <div class="input-field col s12 m6 l6">
                <i class="prefix">Bs.</i>
                {!!Form::label('precioVenta','Precio Venta: *')!!}
                {!!Form::text('precioVenta',null, ['id'=>'precioVenta','placeholder'=>'','onKeypress'=>'return NumCheck(event, this)'])!!}
            </div>
            <div class="input-field col s12 m6 l6">
                <i class="prefix">Bs.</i>
                {!!Form::label('precioVentaCredito','Precio Venta a Credito: *')!!}
                {!!Form::text('precioVenta',null, ['id'=>'precioVentaCredito','onKeypress'=>'return NumCheck(event, this)','placeholder'=>''])!!}
            </div>
        </div>
        <div class="row">
            <div class="col s12 m6 l6">
                <label for="marca" class="active">Marca: *</label>
                <select id="marca"></select>
            </div>
            <div class="col s12 m6 l6">
                <label for="origen" class="active">Origen: *</label>
                <select id="origen"></select>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12 m4 l4">
                <i class="mdi-hardware-keyboard-arrow-down prefix"></i>
                {!!Form::label('Stock Minimo','Stock Minimo: ')!!}
                {!!Form::text('stockMin',null,['id'=>'stockMin','placeholder'=>'','onKeypress'=>'return isNumberKey(this)'])!!}
            </div>
            <div class="input-field col s12 m4 l4">
                <i class="mdi-hardware-keyboard-arrow-up prefix"></i>
                {!!Form::label('Stock Maximo','Stock Maximo: ')!!}
                {!!Form::text('stockMax',null,['id'=>'stockMax','placeholder'=>'','onKeypress'=>'return isNumberKey(this)'])!!}
            </div>
            <div class="input-field col s12 m4 l4">
                <i class="mdi-toggle-check-box-outline-blank prefix"></i>
                {!!Form::label('Unidades Por Caja','Unidades Por Caja: ')!!}
                {!!Form::text('unidadesCaja',null,['id'=>'unidadesCaja','placeholder'=>'','onKeypress'=>'return isNumberKey(this)'])!!}
            </div>
        </div>
        <br>
        <div class="file-field input-field">
            <div class="btn">
                <label for="seleccionarImagen" class="active">Seleccionar imagen :</label>
                <span><i class="material-icons">open_in_browser</i></span>
                <input type="file" id="seleccionarImagen">
            </div>
            <div class="file-path-wrapper">
                <input class="file-path validate" type="text" id="nombreimg">
            </div>
        </div>
        <img id="vistaPrevia" src="/images/productoavatar.png" width="200px"/>
    </div>
    <div class="modal-footer">
        <a  class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
        {!!link_to('#', $title='Actualizar', $attributes = ['id'=>'actualizar', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
</div>

<div id="modal2" class="modal modal-fixed-footer modal">
    <div class="modal-content">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
        <input type="hidden" id="idcompo">
        <h4>Actualizar Composicion</h4>
        <div class="divider"></div>
        <div class="input-field col s12" >
            <h5>Nombre :</h5>
            {!!Form::text('nombre',null, ['id'=>'productonombre','disabled'])!!}
            <h5>Cantidad :</h5>
            {!!Form::text('Cantidad',null, ['id'=>'cantidad'])!!}
        </div>
    </div>
    <div class="modal-footer">
        <a  class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
        {!!link_to('#', $title='Actualizar Composicion', $attributes = ['id'=>'actualizarComposicion', 'class'=>'btn btn-primary'], $secure = null)!!}
    </div>
</div>

<div id="modal3" class="modal modal-fixed-footer">
    <form action="{{ URL::to('importExcelProductos') }}" class="form-horizontal" method="post" enctype="multipart/form-data">
        <div class="modal-content">
            <h4><strong>IMPORTAR ARCHIVO EXCEL(CSV)</strong></h4>
            <div class="divider"></div>
            <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
            <h6><strong>DESCARGAR ARCHIVO CSV PARA LLENAR LOS DATOS:</strong><a href="/download" class="waves-effect btn btn-primary" title="DESCARGAR"><i class="mdi-file-cloud-download"></i></a></h6>
            <div class="divider"></div>
            <div>
                <h6><strong>INSTRUCCIONES</strong></h6>
                <p>Llenar todas las columnas con los datos de los productos.<br>
                    En el caso de que algun atributo no aplique a las caracteristicas de sus productos colocar el texto <strong>N/A</strong><br>
                    Respecto a las siguientes columnas:</p>
                <ul>
                    <li><strong>idTipoProducto: </strong> colocar el id de alguna categoria registrada en el sistema</li>
                    <li><strong>idOrigen: </strong> colocar el id de algun origen registrado en el sistema</li>
                    <li><strong>idMarca: </strong> colocar el id de alguna marca registrada en el sistema</li>
                </ul>
                <p>Para consultar los <strong>IDs</strong> de cada columna los puede ver en el formulario de Categorias, Origen y Marca respectivamente.<br>
                    Es <strong>importante</strong> que en estas columnas solo se <strong>coloque el ID</strong> correspondiente</p>
            </div>
            <div class="divider"></div>
            <div class="file-field input-field">
                <div class="btn">
                    <span>SELECCIONAR ARCHIVO</span>
                    <input type="file" name="import_file" id='import_file'>
                </div>
                <div class="file-path-wrapper">
                    <input class="file-path validate" type="text">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <a class="modal-action modal-close waves-effect waves-green btn-flat" id="closemodal">Cerrar</a>
            <button class="waves-effect btn btn-primary">Importar Archivo</button>
        </div>
    </form>
</div>
