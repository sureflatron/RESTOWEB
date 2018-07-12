<!DOCTYPE html>
<html lang="es">
    <head>
        <link rel="icon" href="images/login-logo.png" type="image/x-icon" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="msapplication-tap-highlight" content="no">
        <meta name="description" content="Materialize is a Material Design Admin Template,It's modern, responsive and based on Material Design by Google. ">
        <meta name="keywords" content="materialize, admin template, dashboard template, flat admin template, responsive admin template,">
        <meta http-equiv="cache-control" content="max-age=0" />
        <meta http-equiv="cache-control" content="no-cache" />
        <meta http-equiv="expires" content="0" />
        <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
        <meta http-equiv="pragma" content="no-cache" />
        <title>OsB SMB</title>
        <meta charset="utf-8">
        <!-- Favicons-->
        <link rel="apple-touch-icon-precomposed" href="images/favicon/apple-touch-icon-152x152.png">
        <!-- For iPhone -->
        <meta name="msapplication-TileColor" content="#00bcd4">
        <meta name="msapplication-TileImage" content="images/favicon/mstile-144x144.png">
        <!-- For Windows Phone -->
        <style>
            div.container { max-width: 1200px }

            #map {
                width: 400px;
                height: 400px;
                float: left;
                background: green;
            }

            .picker__date-display {
                background-color:#00b0ff !important;
            }
            .picker__weekday-display {
                background-color: #0091ea !important;
            }
            .picker__day--selected, .picker__day--selected:hover, .picker--focused .picker__day--selected {
                background-color:#00b0ff !important;
            }
            .picker__close, .picker__today{
                color: #00b0ff !important;
            }
            .picker__day.picker__day--today{
                color: #0091ea !important;
            }
        </style>

        <!-- CORE CSS-->    

      <!--{!!Html::script('https://maps.googleapis.com/maps/api/js?key=AIzaSyBGg-H2iSh6ossPNp-XnHWSw0pN05us_gc&libraries=places')!!}-->  
        {!!Html::style('css/materialize.css')!!} 
        {!!Html::style('css/jquery.dataTables.min.css')!!}
        {!!Html::style('css/responsive.dataTables.min.css')!!}
        {!!Html::style('js/plugins/animate-css/animate.css')!!}
        {!!Html::style('css/style.css')!!} 
        <!-- CSS for full screen (Layout-2)-->    
        {!!Html::style('css/layouts/style-fullscreen.css')!!} 
        <!-- Custome CSS-->    
        {!!Html::style('css/custom/custom.css')!!} 
        <!-- INCLUDED PLUGIN CSS ON THIS PAGE -->
        {!!Html::style('js/plugins/perfect-scrollbar/perfect-scrollbar.css')!!}
        {!!Html::style('js/plugins/jvectormap/jquery-jvectormap.css')!!}
        {!!Html::style('js/plugins/chartist-js/chartist.min.css')!!}
        {!!Html::style('js/plugins/sweetalert/dist/sweetalert.css')!!}
        {!!Html::style('js/plugins/ionRangeSlider/css/ion.rangeSlider.css')!!}
        {!!Html::style('js/plugins/ionRangeSlider/css/ion.rangeSlider.skinFlat.css')!!}
    </head>
    <body>
        <input type="hidden" value="{!! Session::get('idPerfil') !!}" id="iddelperfil">
        <input type="hidden" value="{!! Session::get('idpuntoventa') !!}" id="iddelpuntoventa">
        <input type="hidden" value="{!! Session::get('idempleado') !!}" id="iddelempleado">
        <input type="hidden" value="{!! Session::get('idsucursal') !!}" id="idsucursal">
        <input type="hidden" value="" id="perfilpuedeGuardar">
        <input type="hidden" value="" id="perfilpuedeEliminar">
        <input type="hidden" value="" id="perfilpuedeModificar">
        <input type="hidden" value="" id="perfilpuedeListars">
        <input type="hidden" value="" id="perfilpuedeVerReporte">
        <input type="hidden" value="" id="perfilpuedeImprimir">
        <!-- Start Page Loading -->
        <div id="loader-wrapper">
            <div id="loader"></div>        
            <div class="loader-section section-left"></div>
            <div class="loader-section section-right"></div>
        </div>
        <!-- End Page Loading -->
        <!-- //////////////////////////////////////////////////////////////////////////// -->
        <!-- START HEADER -->
        <header id="header" class="page-topbar">
            <!-- start header nav-->
            <div class="navbar-fixed">
                <nav class="naranja">
                    <div class="nav-wrapper">
                        <ul class="left">                      
                            <li>
                                <h1 class="logo-wrapper">
                                    <a  class="brand-logo darken-1" href="/index">
                                        {!! HTML::image('images/materialize-logo.png')!!}
                                    </a>
                                </h1>
                            </li>
                        </ul>
                        <ul class="right hide-on-med-and-down">
                            <li>
                                <span class="logo-text white-text bold" style="font-weight: bold !important; font-size: 25px;">
                                    SUCURSAL: {!! Session::get('sucursal') !!}
                                </span>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="waves-effect waves-block waves-light toggle-fullscreen"><i class="mdi-action-settings-overscan"></i></a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="waves-effect waves-block waves-light notification-button" data-activates="notifications-dropdown">
                                    <i class="mdi-social-notifications"> 
                                        <!--<small class="notification-badge">5</small>-->  
                                    </i>
                                </a>
                            </li>
                            <li>
                                <a href="#" id="cache" class="waves-effect waves-light"><i class="mdi-action-cached"></i></a>
                            </li>
                        </ul>
                        <!-- translation-button -->

                        <!-- notifications-dropdown -->
                        <!-- <ul id="notifications-dropdown" class="dropdown-content">
                            <li>
                                <h5>NOTIFICATIONS <span class="new badge">5</span></h5>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="#!"><i class="mdi-action-add-shopping-cart"></i> A new order has been placed!</a>
                                <time class="media-meta" datetime="2015-06-12T20:50:48+08:00">2 hours ago</time>
                            </li>
                            <li>
                                <a href="#!"><i class="mdi-action-stars"></i> Completed the task</a>
                                <time class="media-meta" datetime="2015-06-12T20:50:48+08:00">3 days ago</time>
                            </li>
                            <li>
                                <a href="#!"><i class="mdi-action-settings"></i> Settings updated</a>
                                <time class="media-meta" datetime="2015-06-12T20:50:48+08:00">4 days ago</time>
                            </li>
                            <li>
                                <a href="#!"><i class="mdi-editor-insert-invitation"></i> Director meeting started</a>
                                <time class="media-meta" datetime="2015-06-12T20:50:48+08:00">6 days ago</time>
                            </li>
                            <li>
                                <a href="#!"><i class="mdi-action-trending-up"></i> Generate monthly report</a>
                                <time class="media-meta" datetime="2015-06-12T20:50:48+08:00">1 week ago</time>
                            </li>
                        </ul> -->
                    </div>
                </nav>
            </div>
            <!-- end header nav-->
        </header>
        <!-- END HEADER -->
        <!-- //////////////////////////////////////////////////////////////////////////// -->
        <!-- START MAIN -->
        <div id="main">
            <!-- START WRAPPER -->
            <div class="wrapper">
                <!-- START LEFT SIDEBAR NAV-->
                <aside id="left-sidebar-nav">
                    <ul id="slide-out" class="side-nav leftside-navigation">
                        <li class="user-details cyan darken-2">
                            <div class="row">
                                <div class="col col s4 m4 l4">
                                    <img src="images/login-logo.png" alt="img-perfil" id="imgperfil" class="circle responsive-img valign profile-image">
                                </div>
                                <div class="col col s8 m8 l8">
                                    <ul id="profile-dropdown" class="dropdown-content">
                                        <li><a href="/cambiarpasswordusuario/{!! Session::get('idempleado') !!}"><i class="mdi-action-face-unlock"></i> Perfil</a>
                                        </li>
                                        <!-- <li>
                                             <a href="/cambiarContraseniaDeUsuario/{!! Session::get('idempleado') !!}"><i class="mdi-action-lock prefix"></i>Pass</a>
                                             </li>
                                       <li><a href="#"><i class="mdi-communication-live-help"></i> Ayuda</a>
                                      </li>-->
                                        <li class="divider"></li>
                                        <li><a href="/logout/"><i class="mdi-hardware-keyboard-tab"></i> Salir</a>
                                        </li>
                                    </ul>
                                    <a class="btn-flat dropdown-button waves-effect waves-light white-text profile-btn" href="#" data-activates="profile-dropdown">
                                        {!! Session::get('nombre') !!}
                                        <i class="mdi-navigation-arrow-drop-down right"></i></a>
                                    <p class="user-roal">{!! Session::get('Cargo') !!}</p>
                                </div>
                                <!--<h5  class="sucursal" id="sucursalnombre">{!! Session::get('sucursal') !!}</h5>-->
                            </div>
                        </li>
                        <li class="no-padding">
                            <ul class="collapsible collapsible-accordion">
                                <li class="bold">
                                    <a class="collapsible-header  waves-effect waves-cyan"><i class="mdi-communication-business"></i>General</a>
                                    <div class="collapsible-body">
                                        <ul>
                                            <li id="Gestionarempresa"><a href="/Gestionarempresa">Gestionar Empresa </a></li>
                                            <li id="GestionarPais"><a href="/GestionarPais">Gestionar Pais </a></li>
                                            <li id="GestionarSucursal"><a href="/GestionarSucursal">Gestionar Sucursal </a></li>
                                            <li id="cargoempleado"><a href="/cargoempleado">Gestionar Cargo</a></li>
                                            <li class="li-hover"><div class="divider"></div></li>
                                            <li id="Gestionarturno"><a href="/Gestionarturno">Gestionar Turno</a></li>
                                            <li id="Empleados"><a href="/Empleados">Gestionar Empleado</a></li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="bold"><a class="collapsible-header  waves-effect waves-cyan"><i class="mdi-maps-store-mall-directory"></i>Compras</a>
                                    <div class="collapsible-body">
                                        <ul>
                                            <li id="GestionarProveedor"><a href="/GestionarProveedor">Proveedor</a></li>
                                            <li id="GestionarGastoCompra"><a href="/GestionarGastoCompra">Tipo Gasto de Compra</a></li>
                                            <li id="CompraCredito"><a href="/CompraCredito">Txn Compra Credito</a></li>
                                            <li id="Gestionarcompras"><a href="/Gestionarcompras">Txn Compra</a></li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="bold"><a class="collapsible-header  waves-effect waves-cyan"><i class="mdi-editor-insert-comment"></i>Venta</a>
                                    <div class="collapsible-body">
                                        <ul>
                                            <li id="Gestionarmoneda" ><a href="/Gestionarmoneda">Gestionar Moneda </a></li>
                                            <li id="TipoCliente1"><a href="/TipoCliente1">Tipo de Cliente </a></li>
                                            <li id="Gestionarcliente"><a href="/Gestionarcliente">Cliente </a></li>
                                            <li id="GestionarMesa"><a href="/GestionarMesa">Mesa</a></li>
                                            <li id="Gestionarlibroorden"><a href="/Gestionarlibroorden">Libro de orden</a></li>
                                            <li id="gestionarcontador"><a href="/gestionarcontador">Gestionar Contador</a></li>
                                            <li id="GestionarFactura"><a href="/GestionarFactura">Gestionar Factura</a></li>
                                            <li  id="Descuentos" ><a href="/Descuentos">Gestionar Descuentos </a></li>
                                            <li id="listadeventa"><a href="/listadeventa">TxN Venta POS</a></li>
                                            <!--li><a href="/listadeproforma">TxN Proforma</a></li-->
                                            <li id="Creditos" ><a href="/Creditos">TxN Credito</a></li>
                                            <li id='listaralquiler'><a href="/listaralquiler">TxN Alquiler</a></li>
                                            <!--                                            <div id='listarproformas'>
                                                                                            <li><a href="/listarproformas">Proformas</a></li>
                                                                                        </div>-->
                                            <li><div class="divider"></div></li>
                                            <li id="GestionarMapamesa"><a href="/GestionarMapamesa">Tablero de mesas</a></li>
                                            <li id="listadeventares" ><a href="/listadeventares">Txn Venta RESTO</a></li> 
                                            <li><div class="divider"></div></li>
                                            <li id="listadeventaopt"><a href="/listadeventaopt">Txn Venta OPT</a></li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="bold"><a class="collapsible-header  waves-effect waves-cyan"><i class="mdi-action-picture-in-picture"></i>Inventario</a>
                                    <div class="collapsible-body">
                                        <ul>
                                            <li id="abc"><a href="/abc">Analisis ABC</a></li>
                                            <li id="Categoria"><a href="/Categoria">Categoria</a></li>
                                            <li id="Productos"><a href="/Productos">Producto POS</a></li>
                                            <li id="ProductosResto"><a href="/ProductosResto">Producto RESTO</a></li>
                                            <li id="Productosucursal" ><a href="/Productosucursal">Precios Por Sucursal</a></li>
                                            <li id="Medida"><a href="/Medida">Unidad Medida</a></li>
                                            <li id="Origen"><a href="/Origen">Origen</a></li>
                                            <li id="Marca"><a href="/Marca">Marca</a></li>
                                            <li ><div class="divider"></div></li>
                                            <li id="gestionaralmacen"><a href="/gestionaralmacen">Almacen </a></li>
                                            <li id="gestionarmotivo"><a href="/gestionarmotivo">Motivo Movimiento</a></li>
                                            <li id="gestionarinventario"><a href="/gestionarinventario">Txn Inventario</a></li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="bold"><a class="collapsible-header  waves-effect waves-cyan"><i class="mdi-action-swap-vert"></i>Caja</a>
                                    <div class="collapsible-body">
                                        <ul>
                                            <li id="GestionarConcepto" ><a href="/GestionarConcepto">Forma de Pago</a></li>
                                            <li id="GestionarBanco"><a href="/GestionarBanco">Gestionar Banco</a></li>
                                            <li id="GestionarCuentaBancaria"><a href="/GestionarCuentaBancaria">Gesionar Cta. Bancaria</a></li>
                                            <li><div class="divider"></div></li>
                                            <li id="GestionarEgreso"  ><a href="/GestionarEgreso">Gestionar Egreso</a></li>
                                            <li id="GestionarIngreso"><a href="/GestionarIngreso">Gestionar Ingreso</a></li>
                                            <li class="li-hover"><div class="divider"></div></li>
                                            <li id="GestionarTipoEgreso" ><a href="/GestionarTipoEgreso">Gestionar Tipo Egreso</a></li>
                                            <li id="GestionarTipoIngreso"><a href="/GestionarTipoIngreso">Gestionar Tipo Ingreso</a></li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="bold"><a class="collapsible-header  waves-effect waves-cyan"><i class="mdi-action-lock-outline"></i>Seguridad</a>
                                    <div class="collapsible-body">
                                        <ul>
                                            <li id="GestionarObjeto"><a href="/GestionarObjeto">Gestionar Objeto </a></li>
                                            <li id="GestionarModulo"><a href="/GestionarModulo">Gestionar Modulo </a></li>
                                            <li id="GestionarPerfil"><a href="/GestionarPerfil">Gestionar Perfil </a></li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="bold">
                                    <a class="collapsible-header  waves-effect waves-cyan"><i class="mdi-action-assignment"></i>Reportes</a>
                                    <div class="collapsible-body">
                                        <ul>
                                            <li  id="ReportVentasporusuario"  ><a href="/ReportVentasporusuario">Ventas Usuario Actual</a></li>
                                            <li id="Reportporusuario" ><a href="/Reportporusuario">Venta por Usuario</a></li>
                                            <li id="Reporttodousurios"><a href="/Reporttodousurios">Det. Ventas Usr Actual</a></li>
                                            <li id="Reporttodousuriosporusuario"><a href="/Reporttodousuriosporusuario">Det. de Ventas por Usr</a></li>
                                            <li id="ReportVentaACredito"><a href="/ReportVentaACredito">Ventas a Creditos</a></li>
                                            <li id="ReportVentasanuladasyeliminadas" ><a href="/ReportVentasanuladasyeliminadas">Ventas Anuladas y Eli.</a></li>
                                            <li id="ReportVentaDetalleBelleMarie" ><a href="/ReportVentaDetalleBelleMarie">V. Fecha de Entrega</a></li>
                                            <li id="ReportVentasComisionActual" ><a href="/ReportVentasComisionActual">Comision Usr Actual</a></li>
                                            <li id="ReportVentasComisionPorUsuario"><a href="/ReportVentasComisionPorUsuario">Comision por Usuario</a></li>
                                            <li><div class="divider"></div></li>
                                            <li id="ReportGarantiaAlquilerActual"><a href="/ReportGarantiaAlquilerActual">Garantias Usr Actual</a></li>
                                            <li id="ReportGarantiaAlquilerPorUsuario"><a href="/ReportGarantiaAlquilerPorUsuario">Garantias por Usuario</a></li>
                                            <li><div class="divider"></div></li>
                                            <li id="Reportflujousuario"><a href="/Reportflujousuario">FlujoCaja Usuario Act.</a></li>
                                            <li id="Reportflujoporusuario"  ><a href="/Reportflujoporusuario">FlujoCaja por Usuario</a></li>
                                            <li id="reportflujoingresosegresos"><a href="/reportflujoingresosegresos">Flujo de Ingrs. y Egrs.</a></li>
                                            <li><div class="divider"></div></li>
                                            <li id="CompraCredito1"><a href="/CompraCredito1">Compras por Usr.</a></li>
                                            <li id="detallecomprasactual" ><a href="/detallecomprasactual">Det. Compra Usr Actual</a></li>
                                            <li id="detallecomprasporusuario"><a href="/detallecomprasporusuario">Det. de Compra por Usr</a></li>
                                            <li><div class="divider"></div></li>
                                            <li id="ReportEgreso"><a href="/ReportEgreso">Egreso Usr. Actual</a></li>
                                            <li id="ReportEgresoPorUsuario"><a href="/ReportEgresoPorUsuario">Egreso por Usuario</a></li>
                                            <li><div class="divider"></div></li>
                                            <li id="ReportIngreso"><a href="/ReportIngreso">Ingreso Usr. Actual</a></li>
                                            <li id="ReportIngresoPorUsuario"><a href="/ReportIngresoPorUsuario">Ingreso por Usuario</a></li>
                                            <li><div class="divider"></div></li>
                                            <li id="ReporteKardexInventario"><a href="/ReporteKardexInventario">Kardex de Inventario</a></li>
                                            <li id="ReportRaking" ><a href="/ReportRaking">Ranking de Productos</a></li>
                                            <li id="Ventacruzada"><a href="/Ventacruzada">Venta Cruzada</a></li>
                                            <li  id="EstadoInventario"><a href="/EstadoInventario">Estado de Inventario</a></li>                                                                                           
                                            <li  id="analisisABCreport"><a href="/analisisABCreport">analisisABCreporte</a></li>                                           
                                            <li  id="ReporteMovimientoInventario"><a href="/ReporteMovimientoInventario">Movimiento Inventario</a></li>
                                            <li><div class="divider"></div></li>
                                            <li id="Reportelibroventa"><a href="/Reportelibroventa">Libro Venta</a></li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="bold">
                                    <a class=" ">
                                        <div id="reloj" style="font-size:45px;"></div>
                                    </a>
                                </li>
                            </ul>
                        </li>

                    </ul>
                    <a href="#" data-activates="slide-out" class="sidebar-collapse btn-floating btn-medium waves-effect waves-light naranja">
                        <i class="mdi-navigation-menu"></i>
                    </a>
                </aside>
                <!-- END LEFT SIDEBAR NAV-->
                <!-- //////////////////////////////////////////////////////////////////////////// -->
                @yield('breadcumbs')
                <!-- START CONTENT -->
                <section id="content">
                    <!--start container-->
                    <div class="container">
                        @yield('Contenido')
                    </div>
                    <!--end container-->
                </section>
                <!-- END CONTENT -->
            </div>
            <!-- END WRAPPER -->
        </div>
        <!-- END MAIN -->

        <!-- START FOOTER -->
        <footer class="page-footer">
            <div class="footer-copyright blue-grey lighten-2 ">
                <div class="container">
                    <span>Copyright © 2017 <a class="grey-text text-lighten-4" href="http://osbolivia.com" target="_blank">OSBolivia</a></span>
                    <span class="right"> Desarrollado por <a class="grey-text text-lighten-4" href="http://osbolivia.com/" target="_blank">OSBolivia</a></span>
                </div>
            </div>
        </footer>
        <!-- END FOOTER -->

        <!-- ================================================
        Scripts
        ================================================ -->
        <!-- jQuery Library -->
        {!!Html::script('js/plugins/jquery-2.1.4.min.js')!!}
        {!!Html::script('js/plugins/angular.min.js')!!}
        <!--materialize js-->
        {!!Html::script('js/materialize.js')!!}
        <!--scrollbar-->
        {!!Html::script('js/jquery.dataTables.min.js')!!}
        {!!Html::script('js/dataTables.responsive.min.js')!!}
        {!!Html::script('js/addautorizacion.js')!!}
        {!!Html::script('js/plugins/perfect-scrollbar/perfect-scrollbar.min.js')!!}
        <!--plugins.js - Some Specific JS codes for Plugin Settings-->
        {!!Html::script('js/plugins.js')!!}
        <!--custom-script.js - Add your own theme custom JS-->
        {!!Html::script('js/custom-script.js')!!}
        {!!Html::script('js/plugins/sweetalert/dist/sweetalert.min.js')!!}
        {!!Html::script('js/plugins/ionRangeSlider/js/ion.rangeSlider.js')!!}
        {!!Html::script('js/plugins/jquery-validation/jquery.validate.min.js')!!}
        {!!Html::script('js/plugins/jquery-validation/additional-methods.min.js')!!}
        {!!Html::script('js/plugins/formatter/jquery.formatter.min.js')!!}
        {!!Html::script('js/plugins/floatThead/jquery.floatThead.min.js')!!}
        {!!Html::script('js/plugins/floatThead/jquery.floatThead-slim.min.js')!!}
        @section('scripts')
        @show
        <script type="text/javascript">
            function startTime() {
                today = new Date();
                h = today.getHours();
                m = today.getMinutes();
                s = today.getSeconds();
                m = checkTime(m);
                s = checkTime(s);
                var horacompleta = h + ":" + m + ":" + s;
                if (horacompleta == "0:21:00") {
//                    alert('Tu turno acaba de terminar : ' + horacompleta);
                }
                document.getElementById('reloj').innerHTML = horacompleta;
                t = setTimeout('startTime()', 500);
                return horacompleta;
            }
            function checkTime(i) {
                if (i < 10) {
                    i = "0" + i;
                }
                return i;
            }
            window.onload = function () {
                startTime();
            }
        </script>
    </body>
</html>

