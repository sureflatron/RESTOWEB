@extends('Layouts.PanelVentas')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title"> 
                    <strong>Nueva Venta OPTICA</strong> - 
                    <strong>Sucursal:</strong>{!! Session::get('sucursal') !!} &nbsp; &nbsp; || &nbsp; &nbsp; 
                    <strong>Codigo Venta:</strong> {!!$idventaultimo !!}  &nbsp; &nbsp; || &nbsp; &nbsp;  
                    <strong>Estado:</strong>{!!$actual !!}  &nbsp; &nbsp; || &nbsp; &nbsp; 
                    <strong>Fecha:</strong>{!!$fecha !!} &nbsp; &nbsp;
                    <strong>Almacen:</strong><span id="nombrealmacenventa">{!! $a !!}</span>
                </h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li><a href="/listadeventaopt">Listado de Ventas OPTICA</a></li>
                    <li class="active">Nueva Venta OPTICA</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
@include('Modal.modalFacturaResto')
<input type="hidden"  id="idproducto"> 
<input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
<input type="hidden" id="idCliente"> 
<input type="hidden" id="idoptica"> 
<input type="hidden" value="{{$idventaultimo}}" id="venta"> 
<div class="row" style="padding-top: 20px;">
    <div class="col s12 m2 l1">
        <h5><strong>Cliente:</strong></h5>
    </div>
    <div class="col s12 m6 l3">
        <input type="text" list="clienteslist" name="buscar_cliente" autocomplete="off" class="input-field" id="cliNombre" required>
        <datalist id="clienteslist">
            @foreach($clientes as $datos)
            <option data-id='{{ $datos->id }}' value="{{ $datos->nombre }}"> NIT: {{ $datos->nit }}
                @endforeach
        </datalist>
    </div>
    <div class="col s12 m3 l1">
        <a class="waves-effect waves-light btn modal-trigger" href="#modal2" id="agregarcliente">
            <i class="material-icons">assignment_ind</i>
        </a>
    </div>
    @if ($p == "0")
    <div class="col s12 m2 l2">
        <h5><strong>Almacen Para Venta:</strong></h5>
    </div>
    <div class="col s12 m3 l3">
        <select id="almacencombo">
            <option>Cambiar Almacen a</option>
        </select>
    </div>
    @endif
    <div class="col s12 m6 l3" id="generadordeventa" style='display:none;'>
        <p><a class="btn animated infinite jello" href="#" id="generarventa" >NUEVA VENTA</a></p>
    </div>
</div>

<div class="row">
    <div class="grid-example col s12">
        <span class="flow-text"><strong>Nueva Venta</strong></span>
    </div>
    <div class="col s12" style="text-align: center;">
        <div class="row">
            <div class="col s6">
                <p class="right-align">
                    <input name="group185" type="radio" id="test86" checked="true">
                    <label for="test86">Armar Lentes</label>
                </p>
            </div>
            <div class="col s6">
                <p class="left-align">
                    <input name="group185" type="radio" id="test85" />
                    <label for="test85">Accesorios</label>
                </p>
            </div>
        </div>
    </div>
    <div class="col s12 m6 l6"  style="display: none"  id="antiguaventa">
        <ul class="collapsible collapsible-accordion" data-collapsible="accordion">
            <li>
                <div class="collapsible-header active" id="titulotipoprodcuto" >
                    <i class="mdi-action-dns"></i>TIPO DE PRODUCTO
                </div>
                <div class="collapsible-body" id="tipoproducto">
                    <div class="row" id="divrow2" style="text-align: center;"></div>
                </div>
            </li>
            <li>
                <div class="collapsible-header"  id="tituloproductos">
                    <i class="mdi-action-dashboard"></i>PRODUCTOS
                </div>
                <div class="collapsible-body" id="productos">
                    <div class="row" id="sdivrow"  style="text-align: center;">
                    </div>
                </div>
            </li>
        </ul>
    </div>
    <div class="col s12 m6 l6" id="nuevoventas">
        <form class="col s12">
            <div class="row">
                <div class="input-field col s12">
                    <i class="material-icons prefix">account_circle</i>
                    <input type="text"   id="nombrelente"  placeholder="Nombre de quien recibe el lente">
                    <label for="name">NOMBRE DEL PACIENTE: </label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <i class="material-icons prefix">invert_colors</i>
                    <input id="LABORATORIO" class="materialize-textarea" type="text" placeholder="NOMBRE LABORATORIO"> 
                    <label for="address">NOMBRE LABORATORIO: </label>
                </div>
            </div>  
            <div class="input-field col s2">    
                <h4>Lejos</h4>
            </div> 
            <div class="input-field col s10">
                <table class="highlight">
                    <thead>
                        <tr>
                            <th data-field="id">ESF.</th>
                            <th data-field="name">CIL.</th>
                            <th data-field="price">EJE</th>
                            <th data-field="price">PRISMA</th>
                            <th data-field="price">D.I.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input   id="ESFLejos" type="number"  ></td>
                            <td><input   id="CILLejos" type="number" ></td>
                            <td><input   id="EJELejos" type="number" ></td>
                            <td><input   id="PRISMALejos" type="number" ></td>
                            <td><input   id="DILejos" type="number" ></td>
                        </tr>
                        <tr> 
                            <td><input   id="ESFLejosizquierdo" type="number"  ></td>
                            <td><input   id="CILLejosizquierdo" type="number" ></td>
                            <td><input   id="EJELejosizquierdo" type="number" ></td>
                            <td><input   id="PRISMALejosizquierdo" type="number" ></td>
                            <td><input   id="DILejosizquierdo" type="number" ></td>
                        </tr>
                    </tbody>
                </table>
            </div> 
            <div class="input-field col s2">    
                <h4>Cerca</h4>
            </div> 
            <div class="input-field col s10">
                <table class="highlight">
                    <thead>
                        <tr>
                            <th data-field="id">ESF.</th>
                            <th data-field="name">CIL.</th>
                            <th data-field="price">EJE</th>
                            <th data-field="price">PRISMA</th>
                            <th data-field="price">D.I.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>  <input   id="ESFCerca" type="number"  ></td>
                            <td><input   id="CILCerca" type="number" ></td>
                            <td><input   id="EJECerca" type="number" ></td>
                            <td><input   id="PRISMACerca" type="number" ></td>
                            <td><input   id="DICerca" type="number" ></td>
                        </tr>
                        <tr>
                            <td>  <input   id="ESFCercaizquierdo" type="number"  ></td>
                            <td><input   id="CILCercaizquierdo" type="number" ></td>
                            <td><input   id="EJECercaizquierdo" type="number" ></td>
                            <td><input   id="PRISMACercaizquierdo" type="number" ></td>
                            <td><input   id="DICercaizquierdo" type="number" ></td>
                        </tr>
                    </tbody>
                </table>
            </div> 
            <h5>MATERIAL :</h5>
            <style type="text/css">
                /* DivTable.com */
                .divTable{
                    display: table;
                    width: 100%;
                }
                .divTableRow {
                    display: table-row;
                }
                .divTableHeading {
                    background-color: #EEE;
                    display: table-header-group;
                }
                .divTableCell, .divTableHead {
                    border: 1px solid #999999;
                    display: table-cell;
                    padding: 3px 10px;
                }
                .divTableHeading {
                    background-color: #EEE;
                    display: table-header-group;
                    font-weight: bold;
                }
                .divTableFoot {
                    background-color: #EEE;
                    display: table-footer-group;
                    font-weight: bold;
                }
                .divTableBody {
                    display: table-row-group;
                }
            </style>
            <div class="row">
                <div class="col s6  m6 l12">
                    <div class="divTable" style="width: 100%;" >
                        <div class="divTableBody">
                            <div class="divTableRow">
                                <div class="divTableCell"> <input type="checkbox" id="ORGANICO" />
                                    <label for="ORGANICO">ORGANICO</label> </div>
                                <div class="divTableCell"><input type="checkbox" id="POLICARBONATO" />
                                    <label for="POLICARBONATO">POLICARBONATO</label></div>
                                <div class="divTableCell"><input type="checkbox" id="HIGHLITE" />
                                    <label for="HIGHLITE">HIGH LITE</label></div>
                                <div class="divTableCell"><input type="checkbox" id="HIGHINDEX" />
                                    <label for="HIGHINDEX">HIGH INDEX</label></div>
                                <div class="divTableCell"><input type="checkbox" id="VIDRIO" />
                                    <label for="VIDRIO">VIDRIO</label></div>
                            </div>
                            <div class="divTableRow">
                                <div class="divTableCell">  <input type="checkbox" id="BLANCO" />
                                    <label for="BLANCO">BLANCO</label> </div>
                                <div class="divTableCell"><input type="checkbox" id="ANTIRREFLEX" />
                                    <label for="ANTIRREFLEX">ANTIRREFLEX</label> </div>
                                <div class="divTableCell"><input type="checkbox" id="FOTOCROMATICO" />
                                    <label for="FOTOCROMATICO">FOTOCROMATICO</label> </div>
                                <div class="divTableCell"><input type="checkbox" id="CONTINTE" />
                                    <label for="CONTINTE">CON TINTE</label> </div>
                                <div class="divTableCell">&nbsp;</div>
                            </div>
                            <div class="divTableRow">
                                <div class="divTableCell"><input type="checkbox" id="SENCILLA" />
                                    <label for="SENCILLA">V. SENCILLA</label>  </div>
                                <div class="divTableCell"><input type="checkbox" id="BIFOCAL" />
                                    <label for="BIFOCAL">BIFOCAL</label>  </div>
                                <div class="divTableCell"><input type="checkbox" id="PROGRESIVO" />
                                    <label for="PROGRESIVO">PROGRESIVO</label> </div>
                                <div class="divTableCell"><input type="checkbox" id="FUTUREX" />
                                    <label for="FUTUREX">FUTUREX</label></div>
                                <div class="divTableCell">&nbsp;</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>    
        <h5>TOTALES :</h5>
        <div class="row" style="padding-left: 340px;">
            <div class="input-field col s6" >
                <i class="material-icons prefix">mode_edit</i>
                <input id="totaloptica" type="number" placeholder="0" >
                <label for="totaloptica">TOTAL CLIENTE *:</label>
            </div>
            <div class="input-field col s6" >
                <i class="material-icons prefix">mode_edit</i>
                <input id="totalinterno" type="number" placeholder="0" >
                <label for="totalinterno">TOTAL INTERNO *:</label>
            </div>
        </div>
        <h5>Fecha Entrega :</h5>
        <input id="fechaentregas" type="date" class="datepicker" >
        <div id="detalleoptica"> 
            <a class="waves-effect waves-light btn-large" id="ultimo"><i class="material-icons left">trending_flat</i>Agregar Detalle</a>
        </div>
        <div id="editardetalleoptica" > 
            <a class="waves-effect waves-light btn-large" id="limpiar"  ><i class="material-icons left">trending_flat</i>limpiar</a>
            <a class="waves-effect waves-light btn-large" id="editardetalleopticas"><i class="material-icons left">trending_flat</i>Editar Detalle</a>
        </div>
    </div>
    <div class="col s12 m6 l6">
        <div class="row" id="datallleproducto"  style="display: none">
            <div class="col s12 m4 l4">
                <span id="nombreproducto">Producto Seleccionado</span>
            </div>
            <div class="col s12 m4 l4">
                <input type="text" name="" value ="1" id="cantidadproducto">
            </div>
            <div class="col s12 m4 l4">
                <a class="btn" id="agregardetalle"><i class="mdi-image-control-point"></i></a>
            </div>
        </div>
        <table>
            <thead>
                <tr>
                    <th data-field="id">Producto</th>
                    <th data-field="name">Cantidad</th>
                    <th data-field="price">Precio</th>
                    <th data-field="price">Subtotal</th>
                    <th data-field="price">Operacion</th>
                </tr>
            </thead>
            <tbody id="datos">
                <tr>
                </tr>
            </tbody>
        </table>
        <div class="col s12">
            <h5><strong>Total:</strong></h5><span id="total"></span>
        </div> 
        <div class="col s12">
            <div class="row">
                <div class="col s12 m6 s3" style="padding: 10px; text-align: center;"> 
                    <a class="waves-effect waves-light btn modal-trigger" href="#modal1"  OnClick='cargarfactura();'>Cobrar</a>
                </div>               
                <div class="col s12 m6 s3" style="padding: 10px; text-align: center;">
                    <a class="btn"  id="Imprimir"><span>Imprimir.</span></a>
                </div>
                <div class="col s12 m6 s3" style="padding: 10px; text-align: center;">
                    <a class="btn" href="#" id="anularventa">Anular</a>
                </div>
                <div class="col s12 m6 s3" style="padding: 10px; text-align: center;">
                    <a  class="btn" href="/listadeventaopt/" tooltips >volver</a>
                </div>
            </div>
        </div>
    </div>
</div>

@stop

@section('scripts')
{!! Html::script('js/addventaopt.js') !!}


@endsection