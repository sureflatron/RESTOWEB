<?php

/* * ******************Login************************************************ */

Route::resource('Login', 'LoginController');
Route::get('/', 'LoginController@login');
Route::get('index', 'FrontController@index');
Route::get('abc', 'FrontController@abc');
Route::get('maxmin', 'FrontController@maxmin');
Route::get('eqq', 'FrontController@eqq');

Route::get('logout', 'LoginController@logout');

Route::resource('CodigoControl', 'CodigocontrolController');
//Route::get('porvafor','CodigocontrolController@generar');
/* * ***************************Fin*****************listaorigen***************************** */

/* * *****************1ro caso de uso, controladores y modelo******** vistaingrediente********* */
Route::resource('Producto', 'ProductoController');
Route::resource('TipoProducto', 'TipoProductoController');
Route::resource('SubTipoProducto', 'SubtipoProductoController');
Route::resource('ComposicionProducto', 'ComposicionProductoController');

Route::get('listarsubcategorias/{id}', 'SubtipoProductoController@listarsubcategorias');//RAFA DADCO
Route::get('listacategorias', 'TipoProductoController@listarCategorias');
Route::get('listarsubcategoria/{id}', 'SubtipoProductoController@listarsubcategoria');
/* * ******************Las vistas************************************************ */
Route::get('Categoria', 'FrontController@categoria');



Route::get('listarCategoria', 'TipoProductoController@listarCategoria');
Route::get('Productos', 'FrontController@producto');

Route::get('AddProducto', 'FrontController@nuevoproducto');


//Route::get('listacomproducto/{id}', 'ProductoController@listacomposicion'); listaconunidadyingrediente listacomposicionyproducto listaingredientes listarcomprastotal
Route::get('listaingredientesprodu/{id}', 'ProductoController@listaringrediente');
Route::get('listaproductoportipo/{tipo}/{id}/{almacen}/{sucursal}', 'ProductoController@listaproductoportipo');
Route::get('listacomposicionyproducto/{id}', 'ComposicionProductoController@composicionyproducto');

Route::get('editarcomposicion/{id}', 'ComposicionProductoController@editarcomposicionproducto'); ///<---
Route::get('vistacomposicion/{id}', 'FrontController@composicionprodcuto');
Route::get('vistacombo/{id}', 'FrontController@comboproducto');
/* * ******************* ********Fin********************************************** */

/* * *****************2do caso de uso, controladores y modelo***************** */
Route::resource('Unidadmedida', 'UnidadmedidaController');
Route::resource('Ingredienteproducto', 'IngredienteproductoController');
Route::resource('Ingrediente', 'IngredienteController');


Route::resource('Origen', 'OrigenController');


/* * ******************Las vistas************************************************ */
Route::get('Medida', 'FrontController@Unidadmedida');
Route::get('listaunidad', 'UnidadmedidaController@listaunidadmedida');

Route::resource('Marca', 'MarcaController');
Route::get('Marca', 'FrontController@Marca');
Route::get('listamarca', 'MarcaController@listamarca');


Route::get('Origen', 'FrontController@Origen');
Route::get('listaorigen', 'OrigenController@listaorigen');


Route::get('Ingredientes', 'FrontController@liingredientes');
Route::get('listaingredientes', 'IngredienteController@listaingrediente');
Route::get('listaconunidadyingrediente/{id}', 'IngredienteController@prueba');
Route::get('listaconunidadyingredientes/{id}', 'IngredienteController@listaconunidadyingredientes');

Route::get('editardetalleingrediente/{id}', 'IngredienteproductoController@listarparaeditar');


Route::get('vistaingrediente/{id}', 'FrontController@vistaingrediente');


/* * ******************listarporductos****detalleventas*****Fin***********prubacodigocontrol****************listaconunidadyingredientes******************* */

/* * *****************3er caso de uso, controladores y modelo***************** */
Route::resource('Venta', 'VentaController');
Route::resource('Detalleventa', 'DetalleventaController');
Route::resource('Factura', 'FacturaController');

Route::get('imprirfactura/{id}', 'FacturaController@imprirfactura');
//Route::get('otro','FacturaController@imprirfactura');//imprirfactura
/* * ******************Las vistas venta************************************************ */
Route::get('Ventas/{id}', 'FrontController@venta');
Route::get('GenerarVenta/{id}', 'VentaController@generarventas');
Route::get('AnularVenta/{id}', 'VentaController@AnularVentas');

Route::get('detalleventas/{id}', 'DetalleventaController@productocondetalledeventa');
//Ventas
Route::get('listadeventa', 'FrontController@vistadeventa');

Route::get('listarventas', 'VentaController@listarventa');


Route::get('editarventa/{id}', 'FrontController@editarventas');
/* * ***************************Fin********************************************** */
Route::resource('Empleado', 'EmpleadoController');
Route::resource('Cargo', 'CargoController');


Route::get('Empleados', 'FrontController@vistaempleado');
Route::get('listarempleados', 'EmpleadoController@listarempleados');
Route::get('listarempleadosreporte', 'EmpleadoController@listarempleadosresporte');
Route::get('AddEmpleado', 'FrontController@nuevoempleado');


Route::get('cargoempleado', 'FrontController@vistacargo');
Route::get('listarcargo', 'CargoController@listadecargoempleado');


Route::get('Gestionarturno', 'FrontController@Gestionarturno');

/* * ***************************Fin********************************************** */
Route::resource('Turno', 'TurnoController');
Route::resource('Horario', 'HorarioController');

Route::get('listaturno', 'TurnoController@listaturno');
Route::get('Gestionarhorario/{id}', 'TurnoController@gestionarhorario');
Route::get('listadehorario/{id}', 'HorarioController@listarhorario');

/* * ***************************buscarcliente********************************************** */

Route::resource('Modulo', 'ModuloController');
Route::resource('Objeto', 'ObjetoController');
Route::resource('Perfil', 'PerfilController');
Route::resource('Perfilobjeto', 'PerfilobjetoController');
Route::resource('Usuario', 'UsuarioController');

Route::get('GestionarModulo', 'FrontController@Gestionarmodulo');
Route::get('GestionarObjeto', 'FrontController@Gestionarobjeto');
Route::get('GestionarPerfil', 'FrontController@Gestionarperfil');
Route::get('GestionarPerfilObjeto/{id}', 'FrontController@Gestionarperfilobjeto');


Route::get('listarmodulo', 'ModuloController@listarmodulo');
Route::get('listarobjeto', 'ObjetoController@listarobjeto');
Route::get('listaperfil', 'PerfilController@listaperfil');
Route::get('listarperfilobjeto/{id}', 'PerfilobjetoController@listarperfilobjeto');
/* * **********************listarcomprastotal*****Fin********************************************** */

Route::resource('Ciudad', 'CiudadController');
Route::resource('Pais', 'PaisController');
Route::resource('Puntoventa', 'PuntoventaController');
Route::resource('Sucursal', 'SucursalController');


Route::get('Gestionarpuntoventa', 'FrontController@Gestionarpuntoventa');
Route::get('GestionarPais', 'FrontController@GestionarPais');
Route::get('GestionarSucursal', 'FrontController@GestionarSucursal');

Route::get('listarpais', 'PaisController@listarpais');
Route::get('GestionarCiudad/{id}', 'PaisController@GestionarCiudad');
Route::get('listarciudad/{id}', 'CiudadController@listarciudad');
//Route::get('listarsucrusal', 'SucursalController@listarsucrusal');


Route::get('Gestionarusuario/{id}', 'FrontController@Gestionarusuario');
Route::get('listarusuario/{id}', 'UsuarioController@listarusuario');



Route::get('pdf', 'FrontController@reporte');
Route::get('ruser', 'FrontController@reporteusuario');

Route::get('GestionarReporte', 'FrontController@GestionarReporte');
Route::get('Autorizaciones/{id}', 'FrontController@Autorizaciones');
/* * ***************************Fin*********************************generarcompras************* */
Route::resource('Compras', 'ComprasController');
Route::resource('Proveedor', 'ProveedorController');
Route::resource('LibroOrden', 'LibroOrdenController');
Route::resource('Detallecompra', 'DetallecompraController');

Route::get('GestionarProveedor', 'FrontController@GestionarProveedor');
Route::get('Gestionarcompras', 'FrontController@Gestionarcompras');

Route::get('listarproveedor', 'ProveedorController@listarproveedor');
Route::get('Addproveedor', 'FrontController@Addproveedor');

Route::get('Gestionarlibroorden', 'FrontController@Gestionarlibroorden');
Route::get('listarlibroorden', 'LibroOrdenController@listarlibroorden');
Route::get('Nuevolibroorden', 'FrontController@Nuevolibroorden');

Route::POST('listarcompras', 'ComprasController@listarcompras');
Route::get('GenerarCompra/{id}', 'ComprasController@generarcompras');
Route::get('obtenerdatoscompra/{id}', 'ComprasController@obtenerdatoscompra');

Route::get('addCompras/{id}', 'FrontController@nuevacompra');
Route::get('editarcompra/{id}', 'FrontController@editarcompra');

Route::get('listarcomprastotal/{id}', 'DetallecompraController@listarcontotal');
Route::get('pruebas/{id}', 'DetallecompraController@pruebas');

/* * ***************************Fin******************REporte**********obtenerdatoscompra****************** *//*
  //****************************MEtodo prueba********************************************** */
Route::get('obtenernumerodefactura/{id}', 'FacturaController@obtenernumerodefactura');
//****************************Fin***********************************************/
//****************************Fin***********************************************/
Route::resource('Tipoegreso', 'TipoegresoController');
Route::resource('Tipoingreso', 'TipoingresoController');
Route::resource('Egreso', 'EgresoController');
Route::resource('Ingreso', 'IngresoController');


Route::get('GestionarTipoEgreso', 'FrontController@GestionarTipoEgreso');
Route::get('GestionarTipoIngreso', 'FrontController@GestionarTipoIngreso');
Route::get('GestionarEgreso', 'FrontController@GestionarEgreso');
Route::get('GestionarIngreso', 'FrontController@GestionarIngreso');
Route::get('addingreso', 'FrontController@addingreso');
Route::get('addegreso', 'FrontController@addegreso');

Route::get('listatipoingreso', 'TipoingresoController@listatipoingreso');
Route::get('listacatipoegreso', 'TipoegresoController@listacatipoegreso');







Route::POST('listarIngreso', 'IngresoController@listarIngreso');
Route::POST('listarEgreso', 'EgresoController@listarEgreso');



Route::get("certificacion", "FacturaController@prubacodigocontrol");

Route::get('reportecompra/{id}', 'ComprasController@reportecompra');
    


//************listarcompras****************Fin*******GenerarVenta************Almacen**********index******** listaproductoportipo**********/ listadeproducto
Route::resource('Inventario', 'InventarioController');
Route::resource('Detalleinventario', 'DetalleinventarioController');
Route::resource('Motivomovimiento', 'MotivomovimientoController');
Route::resource('Almacen', 'AlmacenController');



Route::get('gestionarinventario', 'FrontController@gestionarinventario');
Route::get('gestionaralmacen', 'FrontController@gestionaralmacen');
Route::get('gestionarmotivo', 'FrontController@gestionarmotivo');


///listaralmacen listarmotivo
Route::get('listaralmacen', 'AlmacenController@listaralmacen');
Route::get('listarmotivo', 'MotivomovimientoController@listarmotivo');


Route::POST('listarinventario', 'InventarioController@listarinventario');
Route::get('addInventario/{id}', 'FrontController@nuevoinventario');
Route::get('Generarinventario/{id}', 'InventarioController@Generarinventario');
Route::get('editarinventario/{id}', 'FrontController@editarinventario');

Route::get('reporteinventario/{id}', 'InventarioController@reporteinventario');
//Route::get('reporteinventario/{id}/{idempleado}', 'InventarioController@reporteinventario1');

Route::get('reporteinventario/{id}/{idempleado}/{extencion}', 'InventarioController@reporteinventario1');

Route::get('listarinventariototal/{id}', 'DetalleinventarioController@listarinventariototal');
Route::get('obtenerdatosinventario/{id}', 'InventarioController@obtenerdatosinventario');

//listacategorias

Route::POST('elimanractualizartotal', 'DetallecompraController@elimnars');
Route::POST('eliminarordencompra', 'DetallecompraController@eliminarordencompra');
Route::get('reporteegreso/{id}', 'EgresoController@reporteegreso');
Route::get('reporteingreso/{id}', 'IngresoController@reporteingreso');

Route::get('reporteingreso/{id}', 'IngresoController@reporteingreso');

Route::get('Gestionarempresa', 'FrontController@Gestionarempresa');

//listarinventario listarsucrusal 

Route::resource('Empresa', 'EmpresaController');
Route::get('listarEmpresa', 'EmpresaController@listarEmpresa');

Route::get('test', 'TipoProductoController@proceduretest');
//************ **************** ******* validarestado ************ ****

Route::resource('Mesa', 'MesaController');
Route::resource('Cliente', 'ClienteController');
//GestionarMesa  
Route::get('GestionarMesa', 'FrontController@GestionarMesa');


Route::get('listarmesa', 'MesaController@listarmesa');
Route::get('listarcliente', 'ClienteController@listarcliente');
Route::get('listarclientecodigo', 'ClienteController@listarclientecodigo');


//GestionarMapamesa
Route::get('GestionarMapamesa', 'FrontController@GestionarMapamesa'); //
//Route::get('Gestionarcliente', 'FrontController@Gestionarcliente'); //Ventas
Route::get('buscarmesa/{id}', 'MesaController@buscarmesa');
Route::get('Buscarventaconmesa/{id}', 'MesaController@Buscarventaconmesa');
Route::get('Bucarmesaporventa/{id}', 'MesaController@Bucarmesaporventa');
Route::get('Cambiarestadomesa/{id}', 'MesaController@Cambiarestadomesa');

Route::POST('generarventadesdemesa', 'VentaController@generarventadesdemesa');



Route::get('Validarventaantigua/{id}', 'VentaController@Validarventaantigua');
Route::get('validarstockminimo/{producto}/{almacen}/{cantidad}', 'DetalleventaController@validarstockminimo');
Route::get('validarstockminimoactualiza/{id}/{almacen}/{cantidad}', 'DetalleventaController@validarstockminimoactualiza');
Route::get('validarstockmaximocompra/{idingrediente}/{idcompra}/{cantidad}', 'DetallecompraController@validarstockmaximocompra');

//listarCategoria ReporteController



Route::get('ejemplo', 'FrontController@ejemplo1');
Route::get('buscarcliente/{id}', 'ClienteController@buscarcliente');
Route::get('agregarcliente/{nit}/{nombre}/{idVenta}', 'ClienteController@agregarcliente');

//****************************listarcompras********************productodetalleventa******listaralmacen*********************/
Route::resource('Reporte', 'ReporteController');
Route::POST('ventasgenerar', 'ReporteController@ventas');
Route::get('GenerarVentas/{idinicio}/{idfin}/{idempleado}', 'ReporteController@generarcierrecaja');
Route::get('GenerarVentastotal/{idinicio}/{idfin}', 'ReporteController@generarcierrecajatotal');
Route::get('GenerarVentastotal/{idinicio}/{idfin}', 'ReporteController@generarcierrecajatotal');

Route::get('flujodecajaporusuario/{idinicio}/{idfin}/{idempleado}', 'ReporteController@flujodecajaporusuario');
Route::get('flujodecajatotal/{idinicio}/{idfin}', 'ReporteController@flujodecajatotal');


///{turno}/{pago}/{listarmesa}/{mesa}
Route::get('generarpdf/{id}', 'ReporteController@generarpdf');

Route::get('precioventaproducto/{id}', 'DetalleventaController@precioventaproducto');
Route::get('productodetalleventa/{id}', 'VentaController@listaproductodetalleventa');

Route::get('validarestado/{id}', 'VentaController@validarestado');


Route::get('GestionarFactura', 'FrontController@Gestionarfactura');
Route::get('listarfacturas', 'VentaController@listarfacturas');

Route::POST('anularfacturaventa', 'VentaController@anularfacturaventa');


Route::get('obteneralmacen/{id}', 'VentaController@obteneralmacen');
Route::get('facturaempresa/{id}', 'VentaController@facturaempresa');
Route::get('cambiarpasswordusuario/{id}', 'FrontController@cambiarpasswordusuario');
Route::POST('actualizarpassword', 'UsuarioController@actualizarpassword');



route::get('buscarProducto/{id}/{idalmacen}', 'VentaController@buscarProducto');





//Route::get('reporteEgresoCompra/{idinicio}/{idfin}','ReporteController@reporteEgresoCompra'); listarfacturas productodetalleventa
Route::get('prueba', 'ReporteController@prueba');


Route::get('cargardatos/{idinicio}/{idfin}/{idempleado}', 'ReporteController@cargardatos');
Route::get('cargardatostotalesventas/{idinicio}/{idfin}', 'ReporteController@cargardatostotalesventas');
//*****************************************************************************
Route::get('cargaringreso/{idinicio}/{idfin}/{idempleado}', 'ReporteController@cargaringreso');
Route::get('totalingreso/{idinicio}/{idfin}/{idempleado}', 'ReporteController@totalingreso');
Route::get('cargaregresos/{idinicio}/{idfin}/{idempleado}', 'ReporteController@cargaregresos');
Route::get('totalegreso/{idinicio}/{idfin}/{idempleado}', 'ReporteController@totalegreso');
//*****************************************************************************
Route::get('reportusuarioactual/{idinicio}/{idfin}/{idempleado}', 'ReporteController@reportusuarioactual'); //uno y dos
Route::get('reportusuario/{idinicio}/{idfin}', 'ReporteController@reportusuario'); //tres
Route::get('Flujocajasuarioactual/{idinicio}/{idfin}/{idempleado}', 'ReporteController@Flujocajasuarioactual'); //uno y dos
Route::get('Flujocajausuario/{idinicio}/{idfin}', 'ReporteController@Flujocajausuario'); //tres
//*****************************************************************************
Route::get('cargaringresototal/{idinicio}/{idfin}', 'ReporteController@cargaringresototal');
Route::get('totalingresototal/{idinicio}/{idfin} ', 'ReporteController@totalingresototal');
Route::get('cargaregresostotal/{idinicio}/{idfin} ', 'ReporteController@cargaregresostotal');
Route::get('totalegresototal/{idinicio}/{idfin}', 'ReporteController@totalegresototal');
Route::get('totaltarjeta/{idinicio}/{idfin}', 'ReporteController@totaltarjeta');
//*****************************************************************************

Route::get('reporteEgresoCompra/{idinicio}/{idfin}', 'ReporteController@reporteEgresoCompra'); //uno y dos
Route::get('egresocompras/{idinicio}/{idfin}', 'ReporteController@egresocompras');
// flujodecajaporusuario


Route::get('descargarreporteVentassinusuario/{idinicio}/{idfin}', 'ReporteController@ReporteVentassinusuario');
Route::get('descargarreporteVentasusuario/{idinicio}/{idfin}/{idempleado}', 'ReporteController@ReporteVentasusuario');

Route::get('descargarreporteflujocajausuario/{idinicio}/{idfin}/{idempleado}', 'ReporteController@Reporteflujocajaempleado');
Route::get('descargarreporteflujocajasinusuario/{idinicio}/{idfin}', 'ReporteController@Reporteflujocajasinempleado');

//Reporteflujocajasinempleado

Route::get('descargarreporteegresocompras/{idinicio}/{idfin}', 'ReporteController@descargarreporteegresocompras');

//**********************************************************************Reporteflujocajasinempleado********ReporteVentasusuario**************************************************

Route::get('ReportVentasporusuario', 'FrontController@ReportVentasporusuario');
Route::get('Reporttodousurios', 'FrontController@Reporttodousurios');
Route::get('Reportporusuario', 'FrontController@Reportporusuario');
Route::get('Reportflujousuario', 'FrontController@Reportflujousuario');
Route::get('Reportflujocompleto', 'FrontController@Reportflujocompleto');
Route::get('Reportflujoporusuario', 'FrontController@Reportflujoporusuario');
Route::get('ReportEgreso', 'FrontController@ReportEgreso');
Route::get('ReporteKardexInventario', 'FrontController@ReporteKardexInventario');

Route::get('reportKardexInventario/{fechaInicio}/{fechaFin}/{idProducto}/{idAlmacen}', 'ReporteController@ReporteKardexInventario');
Route::get('reportKardexDatos/{fechaInicio}/{fechaFin}/{idProducto}/{idAlmacen}', 'ReporteController@ReporteDatosKardex');
Route::get('descargarreportekardex/{fechaInicio}/{fechaFin}/{idProducto}/{idAlmacen}', 'ReporteController@descargarreportekardex');

Route::get('Descuentos', 'FrontController@Descuentos');
Route::resource('Descuento', 'DescuentoController');
Route::get('listardescuento', 'DescuentoController@listardescuento');



//**************************************Reporteflujocajasinempleado********imprirfactura**************************************************cargardatos

Route::get('ReportRaking', 'FrontController@ReportRaking');
Route::get('ReportRakingimporte/{idinicio}/{idfin}', 'ReporteController@ReportRakingimporte');
Route::get('ReportRakingtotal/{idinicio}/{idfin}', 'ReporteController@ReportRakingtotal');
Route::get('Reportkingproducto/{idinicio}/{idfin}', 'ReporteController@Reportkingproducto');
Route::get('Datorakingproducto/{idinicio}/{idfin}', 'ReporteController@Datorakingproducto');
//
Route::get('DatoRakingtotal/{idinicio}/{idfin}', 'ReporteController@DatoRakingtotal');
Route::get('DatoRakingimporte/{idinicio}/{idfin}', 'ReporteController@DatoRakingimporte');
Route::get('descargarRakingimporte/{idinicio}/{idfin}', 'ReporteController@descargarRakingimporte');
Route::get('descargarRakingtotal/{idinicio}/{idfin}', 'ReporteController@descargarRakingimporte'); //**************************************Reporteflujocajasinempleado********ReporteVentasusuario**************************************************cargardatos
Route::get('Ventacruzada', 'FrontController@Ventacruzada');
Route::get('ReportVentacruzdas/{id}', 'ReporteController@ReportVentacruzdas');
Route::get('CargarReportVentacruzdas/{id}', 'ReporteController@CargarReportVentacruzdas');
Route::get('descargarVentacruzada/{id}', 'ReporteController@descargarVentacruzada'); //

Route::get('imprircomanda/{id}', 'VentaController@comanda');
Route::get('imprircomandaventa/{id}', 'VentaController@imprircomandaventa');
Route::get('buscarpornumerodefactura/{id}', 'VentaController@buscarpornumerodefactura');
Route::get('editarfactura/{id}', 'VentaController@editarfactura');
Route::POST('editarfacturaventa', 'VentaController@editarfacturaventa');

Route::get('GenerarVentares/{id}', 'VentaController@generarventasres');

Route::get('Ventares/{id}', 'FrontController@ventares');

Route::get('editarventasres/{id}', 'FrontController@editarventasres');

Route::get('listadeventares', 'FrontController@vistadeventares');
Route::POST('conenvioventa', 'VentaController@conenvioventa');

Route::get('Ventaprogramda/{id}', 'VentaController@Ventaprogramda');



Route::get('Reportelibroventa', 'FrontController@Reportelibroventa');
Route::get('reporlibroventaexcel/{idinicio}/{idfin}/{idsucursal}', 'ReporteController@reporlibroventaexcel');
//

Route::POST('importExcelProductos', 'ProductoController@importExcelProductos');




Route::get('proforma/{id}/{idcliente}/{total}/{descuento}/{importe}/{tipo}', 'VentaController@proforma');
Route::get('imprimirproforma/{id}', 'VentaController@imprimirproforma');
Route::POST('listarventascredito', 'VentaController@listarcreditos');
Route::POST('cuotascredito/{id}', 'VentaController@listarCuotas');
Route::get('cuotascreditopagadas/{id}', 'VentaController@listarCuotasPagadas');
Route::resource('Creditos', 'CobroCuotaController');
Route::get('Creditos', 'FrontController@Credito');

Route::get('imprimirnotaventa/{id}', 'VentaController@imprimirnotaventa');
Route::get('printrecibo/{id}', 'VentaController@imprimirrecibo');


//---------------------------------------------------------------------
//REPORTES
//---------------------------------------------------------------------
Route::get('reportecompra/{id}/{idempleado}', 'ComprasController@reportecompra1');
Route::get('reporteordencompra/{id}/{idempleado}', 'ComprasController@reporteordencompra');
Route::get('reporteinventario/{id}/{idempleado}', 'InventarioController@reporteinventario1');
Route::get('reporteegreso/{id}/{idempleado}', 'EgresoController@reporteegreso');
Route::get('reporteingreso/{id}/{idempleado}', 'IngresoController@reporteingreso');
//Route::get('reportDetalleVenta/{fechainicio}/{fechafin}/{idempleado}/{exten}/{almacen}', 'ReporteController@reporteDetalleVenta');
//Route::get('reportDetalleVentaNO/{fechainicio}/{fechafin}/{idempleado}/{exten}', 'ReporteController@reporteDetalleVentaNoAlmacen');
Route::get('reportusuarioactual/{fechainicio}/{fechafin}/{idempleado}/{id}/{exten}/{almacen}', 'ReporteController@reporteUsuarioActualPorUsuario');
Route::get('reportusuarioactualPorUsuarioNA/{fechainicio}/{fechafin}/{idempleado}/{id}/{exten}', 'ReporteController@reporteUsuarioActualPorUsuarioNoAlmacen');
Route::get('reportusuarioactual/{idinicio}/{idfin}/{idempleado}/{exten}', 'ReporteController@reporteUsuarioActual');
Route::get('reportusuarioactual/{idinicio}/{idfin}/{idempleado}/{exten}/{almacen}', 'ReporteController@reporteUsuario');
Route::get('FlujocajaCualquierEmpleado/{idinicio}/{idfin}/{idempleado}/{exten}', 'ReporteController@FlujocajaCualquierEmpleado');
Route::get('Flujocajasuarioactual/{idinicio}/{idfin}/{usuario}/{idempleado}/{exten}', 'ReporteController@Flujocajasuarioactual');
Route::get('Flujocajasuarioactual/{idinicio}/{idfin}/{usuario}/{idempleado}/{exten}', 'ReporteController@Flujocajasuarioactual');
Route::get('reporteEgresoCompra/{idinicio}/{idfin}/{idempleado}/{exten}', 'ReporteController@reporteEgreso'); //uno y dos
Route::get('ReportIngreso', 'FrontController@ReportIngreso');
Route::get('reporteIngreso/{idinicio}/{idfin}/{idempleado}/{exten}', 'ReporteController@reporteIngreso');
Route::get('reportKardexInventario/{fechaInicio}/{fechaFin}/{idProducto}/{idempleado}/{exten}', 'ReporteController@ReporteKardexInventario');
Route::get('Reportkingproducto/{idinicio}/{idfin}/{idempleado}/{exten}', 'ReporteController@reporteRankingProductos');
Route::get('Reportkingproductocantidad/{idinicio}/{idfin}/{idempleado}/{exten}', 'ReporteController@reporteRankingProductosPorCantidad');
Route::get('Reportkingproductoimporte/{idinicio}/{idfin}/{idempleado}/{exten}', 'ReporteController@reporteRankingProductosPorImporte');
Route::get('ReportVentacruzdas/{idproducto}/{idempleado}/{exten}', 'ReporteController@reporteReportVentacruzdas');
//---------------------------------------------------------------------
//REPORTES
//--------------------------------------------------------------------
//---------------------------------------------------------------------
//DASHBOARDS
//---------------------------------------------------------------------
Route::POST('ventasusers', 'VentaController@ventasXempleados');
Route::POST('productstipo', 'VentaController@productosXtipo');
Route::POST('ventasdiassemana', 'Ventacontroller@ventasXdiaXsemana');
Route::POST('abc', 'Ventacontroller@abc');
Route::GET('analisisabc', 'Ventacontroller@analisisabc');
Route::GET('analisisabc_anioalmacen/{anio}/{almacen}', 'Ventacontroller@analisisabc_anioalmacen');
Route::GET('analisisabc_aniomesalmacen/{mes}/{anio}/{almacen}', 'Ventacontroller@analisisabc_aniomesalmacen');

Route::POST('maxminxdefecto', 'Ventacontroller@maxminxdefecto');
Route::GET('ejecutarmaxminanual/{anio}/{almacen}', 'Ventacontroller@ejecutarmaxminanual');
Route::GET('ejecutareqq/{almacen}/{producto}', 'Ventacontroller@ejecutareqq');
Route::GET('graficarcalculo/{anio}/{almacen}', 'Ventacontroller@graficarcalculo');
//
Route::GET('analisismetodo/{mes}/{anio}/{almacen}', 'Ventacontroller@analisismetodo');
Route::POST('ventasmes', 'Ventacontroller@ventasXmes');
Route::POST('ventastipopago', 'Ventacontroller@ventasformapago');
Route::POST('topproductos', 'Ventacontroller@topproductos');
Route::POST('topclientesfactura', 'Ventacontroller@topclientesfacturados');
Route::POST('topclientesventas', 'Ventacontroller@topclientesventas');
//---------------------------------------------------------------------
//DASHBOARDS
//---------------------------------------------------------------------
//--------------------------------------------------------------------
Route::get('obtenercontador', 'SucursalController@gestionarcontador');
Route::get('gestionarcontador', 'FrontController@gestionarcontador');
Route::get('mostrarcontador/{idfin}', 'SucursalController@mostrarcontador');
Route::POST('colocarcero/{id}', 'SucursalController@colocarcero');
Route::POST('actualizarcontador/{id}', 'SucursalController@actualizarcontador');
//--------------------------------------------------------------------
//CUENTAS POR PAGAR
Route::resource('CobroaCuota', 'CobroaCuotaController');
Route::resource('CuantaaCobrar', 'CuantaaCobrarController');

Route::POST('cuotascreditopagadas/{id}', 'ComprasController@listarCuotasPagadas');
Route::POST('listarCuotas/{id}', 'ComprasController@listarCuotas');


///////////////////////////////////////////en ROUTE////
Route::get('CompraCredito', 'FrontController@CompraCredito');
//////////////////////////////////////////// en frontcontroller

Route::get('listarcreditocompra', 'FrontController@listarcreditocompra');

//===========================
//DESCARGAR ARCHIVO BASE PARA IMPORTAR EXCEL
//===================================
Route::get('/download', 'ProductoController@getDownload');
//===================================================
//=================================================
//=================================
//OBBTENER RAZON Y NIT AL SELECCIONAR CLIENTE
//=============================================
Route::get('nitrazoncliente/{id}', 'VentaController@nitrazoncliente');
//=============================================
//==================================================
//=================================
//Listar Proformas
//=============================================
Route::resource('listarproformas', 'VentaController');
Route::get('listarproformas', 'FrontController@listarproformas');
Route::POST('proformas', 'VentaController@proformas');
//=============================================
//==================================================
//=================================
//Listar Alquiler
//=============================================
Route::resource('listaralquiler', 'VentaController');
Route::get('listaralquiler', 'FrontController@listaralquiler');
Route::POST('alquiler', 'VentaController@listaralquiler');
Route::get('obteneralquiler/{id}', 'VentaController@obteneralquiler');
Route::get('devolverproducto/{id}', 'VentaController@devolverproducto');
//=============================================
//==================================================


Route::get('EstadoInventario', 'FrontController@EstadoInventario');
Route::get('analisisABCreport', 'FrontController@analisisABCreport');
Route::get('eqqreport', 'FrontController@eqqreport');
Route::get('reporteeqq/{idempleado}/{exten}', 'ReporteController@reporteeqq');
Route::get('reporteeqqConSucursalConAlmacen/{idempleado}/{exten}/{idsucursal}/{idalmacen}', 'ReporteController@reporteeqqConSucursalConAlmacen');
Route::get('reporteAnalisisAbc/{idempleado}/{exten}', 'ReporteController@reporteAnalisisAbc');
Route::get('reporteAnalisisAbcConSucursal/{idempleado}/{exten}/{idsucursal}', 'ReporteController@reporteAnalisisAbcConSucursal');
Route::get('reporteAnalisisAbcConSucursalConAlmacen/{idempleado}/{exten}/{idsucursal}/{idalmacen}', 'ReporteController@reporteAnalisisAbcConSucursalConAlmacen');

Route::get('reporteEstadoInventario/{idempleado}/{exten}', 'ReporteController@reporteEstadoInventario');


//'''''''''''''''''''''''''''''''''''''

Route::get('GestionarBanco', 'FrontController@Banco');
Route::get('listarbanco', 'BancoController@listabanco');
Route::resource('Banco', 'BancoController');

Route::get('GestionarCuentaBancaria', 'FrontController@CuentaBancaria');
Route::get('listarCuentaBancaria', 'CuentaBancariaController@listaCuentaBancaria');
Route::resource('CuentaBancaria', 'CuentaBancariaController');
Route::get('listarCuenta/{id}', 'CuentaBancariaController@listaCuenta');

Route::get('GestionarConcepto', 'FrontController@Concepto');
Route::get('listarConcepto', 'ConceptoController@listaConcepto');
Route::resource('Concepto', 'ConceptoController');

//=========================================


Route::get('TipoCliente1', 'FrontController@TipoCliente');
Route::resource('TipoCliente', 'TipoClienteController');
Route::get('listaTipoCliente', 'TipoClienteController@listaTipoCliente');

Route::resource('Moneda', 'MonedaController');
Route::get('listarmoneda', 'MonedaController@listademoneda');
Route::get('listarmoneda/{id}', 'MonedaController@listarmoneda');
Route::get('Gestionarmoneda', 'FrontController@GestionarMoneda');
Route::get('Gestionarcliente', 'FrontController@GestionarTipoCliente');

Route::get('CompraEfectivo', 'FrontController@CompraEfectivo');
Route::get('CompraCredito1', 'FrontController@CompraCredito1');



//=============================================
//     ConsultarStockdeProducto
//==============================================

Route::get('obtenerstock/{id}/{almacen}', 'DetalleinventarioController@obtenerstock');
Route::get('actualizartipo/{id}/{tipo}', 'DetalleinventarioController@actualizartipo');

//Reporte Movimineto de Inventario
Route::get('ReporteMovimientoInventario', 'FrontController@ReporteMovimientoInventario');

//Listar Descuentos Activos
Route::get('listardescuentos', 'DescuentoController@listardescuentos');


Route::get('listarAlmacenCombo', 'AlmacenController@listarAlmacenCombo');
Route::get('Reporttodousuriosporusuario', 'FrontController@Reporttodousuriosporusuario');
Route::get('reportDetalleVenta/{fechainicio}/{fechafin}/{idempleado}/{idusuario}/{exten}/{almacen}', 'ReporteController@reporteDetalleVenta');
Route::get('reportDetalleVentaNO/{fechainicio}/{fechafin}/{idempleado}/{idusuario}/{exten}', 'ReporteController@reporteDetalleVentaNoAlmacen');
Route::get('reporteDetalleVentaSinSucursalSinEmpleado/{fechainicio}/{fechafin}/{idempleado}/{exten}', 'ReporteController@reporteDetalleVentaSinSucursalSinEmpleado');
Route::get('reporteDetalleVentaConSucursalSinEmpleado/{fechainicio}/{fechafin}/{idempleado}/{exten}/{sucursal}', 'ReporteController@reporteDetalleVentaConSucursalSinEmpleado');
Route::get('reportKardexInventarioConSucursal/{fechaInicio}/{fechaFin}/{idProducto}/{idempleado}/{exten}/{idsucursal}', 'ReporteController@reportKardexInventarioConSucursal');
Route::get('reportKardexInventarioConSucursalConAlmacen/{fechaInicio}/{fechaFin}/{idProducto}/{idempleado}/{exten}/{idAlmacen}/{idsucursal}', 'ReporteController@reportKardexInventarioConSucursalConAlmacen');
Route::get('reporteEstadoInventarioConSucursal/{idempleado}/{exten}/{sucursal}', 'ReporteController@reporteEstadoInventarioConSucursal');
Route::get('reporteEstadoInventarioConSucursalConAlmacen/{idempleado}/{exten}/{sucursal}/{almacen}', 'ReporteController@reporteEstadoInventarioConSucursalConAlmacen');

Route::get('reporteDetalleVentaSinSucursalSinEmpleadoSinSucursal/{fechaini}/{fechafin}/{idempleado}/{exte}', 'ReporteController@reporteDetalleVentaSinSucursalSinEmpleadoSinSucursal');

Route::get('AnularVent/{id}', 'VentaController@AnularVenta');
Route::get('actualizaralmaceninv/{idinventario}/{idalmacen}', 'InventarioController@actualizaralmaceninv');
Route::get('actualizaralmaceninvdestino/{idinventario}/{idalmacen}', 'InventarioController@actualizaralmaceninvdestino');

Route::get("listaralmacensucursal/{idpunto}", 'AlmacenController@listaralmacensucursal');
Route::get("listarproductosucursal/{idpunto}", 'ProductoController@listarproductosucursal');
Route::get("listaralmacenesmaxmin", 'AlmacenController@listaralmacenesmaxmin');
route::get("listaralmacendelasucursal/{idsucursal}", 'AlmacenController@listaralmaccendelasucursal');

Route::get('listarAlmacenS/{ids}', 'AlmacenController@listarAlmacenS');
Route::get('listarsucrusal', 'SucursalController@listarsucrusal');
Route::get('listarSucursalCombo/{id}', 'SucursalController@listarSucursal');




Route::get('actualizaralmacenventa/{idventa}/{idalmacen}', 'VentaController@actualizaralmacenventa');


Route::get('imprimiringreso/{id}', 'IngresoController@imprimirreciboingreso');
Route::get('imprimiregreso/{id}', 'EgresoController@imprimirreciboegreso');


Route::get('reporteDetalleVentaSinSucursalSinEmpleadoSinSucursal/{fechainicio}/{fechafin}/{idempleado}/{exten}', 'ReporteController@reporteDetalleVentaSinSucursalSinEmpleadoSinSucursal');
Route::get('ventaDetalleVentaConSucursalSinAlmacenSinEmpleado/{fechainicio}/{fechafin}/{idempleado}/{exten}/{sucursal}', 'ReporteController@ventaDetalleVentaConSucursalSinAlmacenSinEmpleado');
Route::get('reportDetalleVenta/{fechainicio}/{fechafin}/{idempleado}/{idusuario}/{exten}/{sucursal}', 'ReporteController@reporteDetalleVentas');
Route::get('reportDetalleVentaNO/{fechainicio}/{fechafin}/{idempleado}/{idusuario}/{exten}', 'ReporteController@reporteDetalleVentaNoAlmacen');
Route::get('reportDetalleVentaConSucursalConEmpleadoConAlmacen/{fechainicio}/{fechafin}/{usuario}/{idempleado}/{exten}/{sucursal}/{almacen}', 'ReporteController@reportDetalleVentaConSucursalConEmpleadoConAlmacen');
Route::get('reportusuarioactual/{idinicio}/{idfin}/{idempleado}', 'ReporteController@reportusuarioactual'); //uno y dos
Route::get('reportusuarioactual/{idinicio}/{idfin}/{idempleado}/{exten}', 'ReporteController@reporteUsuarioActual');

Route::get('reportusuarioactual/{fechainicio}/{fechafin}/{idempleado}/{id}/{exten}/{almacen}', 'ReporteController@reporteUsuarioActualPorUsuario');
Route::get('reportusuarioactual/{idinicio}/{idfin}/{idempleado}/{exten}/{almacen}', 'ReporteController@reporteUsuario');
Route::get('reportusuarioactualPorUsuarioNA/{fechainicio}/{fechafin}/{idempleado}/{id}/{exten}', 'ReporteController@reporteUsuarioActualPorUsuarioNoAlmacen');
Route::get('reporteVentaSinSucursalSinEmpleadoSinAlmacen/{idinicio}/{idfin}/{idempleado}/{exten}', 'ReporteController@reporteVentaSinSucursalSinEmpleadoSinAlmacen');
Route::get('reporteVentaConSucursalSinAlmacenSinEmpleado/{idinicio}/{idfin}/{idempleado}/{exten}/{sucursal}', 'ReporteController@reporteVentaConSucursalSinAlmacenSinEmpleado');
Route::get('reporteVentaConSucursalSinAlmacenConEmpleado/{idinicio}/{idfin}/{usuariologueado}/{idempleado}/{exten}/{sucursal}', 'ReporteController@reporteVentaConSucursalSinAlmacenConEmpleado');
Route::get('reporteVentaSinSucursalSinAlmacenConEmpleado/{idinicio}/{idfin}/{usuariologueado}/{idempleado}/{exten}', 'ReporteController@reporteVentaSinSucursalSinAlmacenConEmpleado');
Route::get('reporteVentaConSucursalConAlmacenConEmpleado/{idinicio}/{idfin}/{usuariologueado}/{idempleado}/{exten}/{sucursal}/{almacen}', 'ReporteController@reporteVentaConSucursalConAlmacenConEmpleado');
Route::get('reporteEgresoCompra/{idinicio}/{idfin}', 'ReporteController@reporteEgresoCompra'); //uno y dos
Route::get('reporteEgresoCompra/{idinicio}/{idfin}/{idusuariologeado}/{idempleado}/{exten}', 'ReporteController@reporteEgreso'); //uno y dos


Route::get('ReportEgresoPorUsuario', 'FrontController@ReportEgresoPorusuario');
Route::get('reporteEgresoCompraSinEmpleado/{idinicio}/{idfin}/{idusuariologeado}/{exten}', 'ReporteController@reporteEgresoCompraSinEmpleado');
Route::get('reporteEgresoCompraConEmpleado/{idinicio}/{idfin}/{idusuariologeado}/{mepleado}/{exten}', 'ReporteController@reporteEgresoCompraConEmpleado');

Route::get('reporteIngreso/{idinicio}/{idfin}/{idusuariologeado}/{idempleado}/{exten}', 'ReporteController@reporteIngreso');
Route::get('ReportIngresoPorUsuario', 'FrontController@ReportIngresoPorUsuario');

Route::get('reporteIngresoSinEmpleado/{idinicio}/{idfin}/{idusuariologeado}/{exten}', 'ReporteController@reporteIngresoSinEmpleado');

Route::get('reporteRankingSinOrdenarSinSucursalSinEmpleadoSinAlmacen/{idinicio}/{idfin}/{idempleado}/{exten}', 'ReporteController@reporteRankingProductos');
Route::get('reporteRankingSinOrdenarConSucursalSinEmpleadoSinAlmacen/{idinicio}/{idfin}/{idempleado}/{exten}/{sucursal}', 'ReporteController@reporteRankingSinOrdenarConSucursalSinEmpleadoSinAlmacen');
Route::get('reporteRankingSinOrdenarConSucursalConEmpleadoSinAlmacen/{idinicio}/{idfin}/{idempleado}/{idusuario}/{exten}/{sucursal}', 'ReporteController@reporteRankingSinOrdenarConSucursalConEmpleadoSinAlmacen');
Route::get('reporteRankingSinOrdenarConSucursalConEmpleadoConAlmacen/{idinicio}/{idfin}/{idempleado}/{idusuario}/{exten}/{sucursal}/{almacen}', 'ReporteController@reporteRankingSinOrdenarConSucursalConEmpleadoConAlmacen');

Route::get('reporteRankingOrdenarImporteSinSucursalSinEmpleadoSinAlmacen/{idinicio}/{idfin}/{idempleado}/{exten}', 'ReporteController@reporteRankingOrdenarImporteSinSucursalSinEmpleadoSinAlmacen');
Route::get('reporteRankingOrdenarImporteConSucursalSinEmpleadoSinAlmacen/{idinicio}/{idfin}/{idempleado}/{exten}/{sucursal}', 'ReporteController@reporteRankingOrdenarImporteConSucursalSinEmpleadoSinAlmacen');
Route::get('reporteRankingOrdenarImporteConSucursalConEmpleadoSinAlmacen/{idinicio}/{idfin}/{idempleado}/{idusuario}/{exten}/{sucursal}', 'ReporteController@reporteRankingOrdenarImporteConSucursalConEmpleadoSinAlmacen');
Route::get('reporteRankingOrdenarImporteConSucursalConEmpleadoConAlmacen/{idinicio}/{idfin}/{idempleado}/{idusuario}/{exten}/{sucursal}/{almacen}', 'ReporteController@reporteRankingOrdenarImporteConSucursalConEmpleadoConAlmacen');

Route::get('reporteRankingOrdenarCantidadSinSucursalSinEmpleadoSinAlmacen/{idinicio}/{idfin}/{idempleado}/{exten}', 'ReporteController@reporteRankingOrdenarCantidadSinSucursalSinEmpleadoSinAlmacen');
Route::get('reporteRankingOrdenarCantidadConSucursalSinEmpleadoSinAlmacen/{idinicio}/{idfin}/{idempleado}/{exten}/{sucursal}', 'ReporteController@reporteRankingOrdenarCantidadConSucursalSinEmpleadoSinAlmacen');
Route::get('reporteRankingOrdenarCantidadConSucursalConEmpleadoSinAlmacen/{idinicio}/{idfin}/{idempleado}/{idusuario}/{exten}/{sucursal}', 'ReporteController@reporteRankingOrdenarCantidadConSucursalConEmpleadoSinAlmacen');
Route::get('reporteRankingOrdenarCantidadConSucursalConEmpleadoConAlmacen/{idinicio}/{idfin}/{idempleado}/{idusuario}/{exten}/{sucursal}/{almacen}', 'ReporteController@reporteRankingOrdenarCantidadConSucursalConEmpleadoConAlmacen');


Route::get('FlujocajaporusurioConEmpleadoConSucursal/{idinicio}/{idfin}/{idempleado}/{usuariologeado}/{extension}/{sucursal}', 'ReporteController@FlujocajaporusurioConEmpleadoConSucursal'); //uno y dos
Route::get('FlujocajaporusurioConEmpleadoSinSucursal/{idinicio}/{idfin}/{idempleado}/{usuariologeado}/{extension}', 'ReporteController@FlujocajaporusurioConEmpleadoSinSucursal'); //uno y dos
//Route::get('FlujocajaCualquierEmpleado/{idinicio}/{idfin}/{idempleado}/{exten}', 'ReporteController@FlujocajaCualquierEmpleado');
Route::get('FlujocajaporusurioSinEmpleadoConSucursal/{idinicio}/{idfin}/{usuariologeado}/{extension}/{sucursal}', 'ReporteController@FlujocajaporusurioSinEmpleadoConSucursal'); //uno y dos
//VENTAS OPTICA
Route::get('listadeventaopt', 'FrontController@vistadeventaopt');
Route::get('Ventaopt/{id}', 'FrontController@ventaopt');
Route::get('editarventasopt/{id}', 'FrontController@editarventasopt');
Route::get('reporteoptica/{id}', 'ReporteController@reporteoptica');
Route::get('recibooptica/{id}', 'ReporteController@recibooptica');
Route::POST('agregarrecibocliente', 'ClienteController@agregarrecibocliente');
///
/////////////NUEVOS REPORTES
Route::get('ReportVentacruzdasConSucursal/{idproducto}/{idempleado}/{exten}/{sucursal}', 'ReporteController@reporteReportVentacruzdasConSucursal');
Route::get('ReportVentacruzdasConSucursalConAlmacen/{idproducto}/{idempleado}/{exten}/{sucursal}/{almacen}', 'ReporteController@reporteReportVentacruzdasConSucursalConAlmacen');



Route::get('listarProveedor', 'ProveedorController@listarproveedorCombo');

Route::get('reporteCompraCredito/{fechaini}/{fechafin}/{idempleado}/{ext}', 'ReporteController@reportecompraCredito');
Route::get('reporteCompraCreditoConEmpleadoSinProveedor/{fechaini}/{fechafin}/{usuariologeado}/{idempleado}/{ext}', 'ReporteController@reportecompraCreditoConempleadoSinProveedor');
Route::get('reporteCompraCreditoSinEmpleadoConProveedor/{fechaini}/{fechafin}/{usuariologeado}/{proveedor}/{ext}', 'ReporteController@reporteCompraCreditoSinEmpleadoConProveedor');
Route::get('reporteCompraCreditoConEmpleadoConProveedor/{fechaini}/{fechafin}/{usuariologeado}/{empleado}/{proveedor}/{ext}', 'ReporteController@reporteCompraCreditoConEmpleadoConProveedor');

Route::get('reporteCompraEfectivo/{fechaini}/{fechafin}/{idempleado}/{ext}', 'ReporteController@reportecompraEfectivo');
Route::get('reporteCompraEfectivoConEmpleadoSinProveedor/{fechaini}/{fechafin}/{usuariologeado}/{idempleado}/{ext}', 'ReporteController@reporteCompraEfectivoConEmpleadoSinProveedor');
Route::get('reporteCompraEfectivoSinEmpleadoConProveedor/{fechaini}/{fechafin}/{usuariologeado}/{proveedor}/{ext}', 'ReporteController@reporteCompraEfectivoSinEmpleadoConProveedor');
Route::get('reporteCompraEfectivoConEmpleadoConProveedor/{fechaini}/{fechafin}/{usuariologeado}/{empleado}/{proveedor}/{ext}', 'ReporteController@reporteCompraEfectivoConEmpleadoConProveedor');

Route::get('reporteEstadoInventarioConProducto/{idempleado}/{exten}/{producto}', 'ReporteController@reporteEstadoInventarioConProducto');
Route::get('reporteEstadoInventarioConSucursalConProducto/{idempleado}/{exten}/{sucursal}/{producto}', 'ReporteController@reporteEstadoInventarioConSucursalConProducto');
Route::get('reporteEstadoInventarioConSucursalConAlmacenConProducto/{idempleado}/{exten}/{sucursal}/{almacen}/{producto}', 'ReporteController@reporteEstadoInventarioConSucursalConAlmacenConProducto1');
//////////////////////////






Route::get('ReportMovimientoInventario/{fechainicio}/{fechafin}/{id}/{exten}', 'ReporteController@reportemotivomovimiento');
Route::get('ReportMovimientoInventarioConSucursal/{fechainicio}/{fechafin}/{id}/{exten}/{sucursal}', 'ReporteController@reportemotivomovimientoConSucursal');
Route::get('ReportMovimientoInventarioConSucursalConAlmacen/{fechainicio}/{fechafin}/{id}/{exten}/{sucursal}/{almacen}', 'ReporteController@reportemotivomovimientoConSucursalConAlmacen');




Route::get('ReportMovimientoInventarioProducto/{fechainicio}/{fechafin}/{id}/{exten}/{producto}', 'ReporteController@ReportMovimientoInventarioProducto');
Route::get('ReportMovimientoInventarioConSucursalProducto/{fechainicio}/{fechafin}/{id}/{exten}/{sucursal}/{producto}', 'ReporteController@ReportMovimientoInventarioConSucursalProducto');
Route::get('ReportMovimientoInventarioConSucursalConAlmacenProducto/{fechainicio}/{fechafin}/{id}/{exten}/{sucursal}/{almacen}/{producto}', 'ReporteController@ReportMovimientoInventarioConSucursalConAlmacenProducto');

//===============================================================================
Route::get('ReportVentasComisionActual', 'FrontController@ReportVentasComisionActual');
Route::get('ReportVentasComisionPorUsuario', 'FrontController@ReportVentasComisionPorUsuario');


Route::get('reportusuarioactualPorUsuarioNAComision/{fechainicio}/{fechafin}/{idempleado}/{id}/{exten}', 'ReporteController@reportusuarioactualPorUsuarioNAComision');
Route::get('reportusuarioactualComision/{fechainicio}/{fechafin}/{idempleado}/{id}/{exten}/{almacen}', 'ReporteController@reporteUsuarioActualPorUsuarioComision');


Route::get('reportusuarioactualComision/{idinicio}/{idfin}/{idempleado}/{exten}', 'ReporteController@reporteUsuarioActualComision');
Route::get('reporteVentaConSucursalSinAlmacenSinEmpleadoComision/{idinicio}/{idfin}/{idempleado}/{exten}/{sucursal}', 'ReporteController@reporteVentaConSucursalSinAlmacenSinEmpleadoComision');
Route::get('reporteVentaConSucursalSinAlmacenConEmpleadoComision/{idinicio}/{idfin}/{usuariologueado}/{idempleado}/{exten}/{sucursal}', 'ReporteController@reporteVentaConSucursalSinAlmacenConEmpleadoComision');
//Route::get('reporteVentaSinSucursalSinAlmacenConEmpleado/{idinicio}/{idfin}/{usuariologueado}/{idempleado}/{exten}', 'ReporteController@reporteVentaSinSucursalSinAlmacenConEmpleado');
Route::get('reporteVentaConSucursalConAlmacenConEmpleadoComision/{idinicio}/{idfin}/{usuariologueado}/{idempleado}/{exten}/{sucursal}/{almacen}', 'ReporteController@reporteVentaConSucursalConAlmacenConEmpleadoComision');


Route::get('ReportGarantiaAlquilerActual', 'FrontController@ReportGarantiaAlquilerActual');
Route::get('ReportGarantiaAlquilerPorUsuario', 'FrontController@ReportGarantiaAlquilerPorUsuario');

Route::get('reportusuarioactualPorUsuarioAlquiler/{fechainicio}/{fechafin}/{idempleado}/{exten}/{filt}', 'ReporteController@reportusuarioactualPorUsuarioAlquiler');
Route::get('reportusuarioactualAlquiler/{fechainicio}/{fechafin}/{idempleado}/{exten}/{almacen}/{filt}', 'ReporteController@reportusuarioactualAlquiler');

Route::get('reportAlquilerSinSucursalSinAlmacenSinEmpleado/{idinicio}/{idfin}/{idempleado}/{exten}/{filt}', 'ReporteController@reportAlquilerSinSucursalSinAlmacenSinEmpleado');
Route::get('reporteVentaConSucursalSinAlmacenSinEmpleadoAlquiler/{idinicio}/{idfin}/{idempleado}/{exten}/{sucursal}/{filt}', 'ReporteController@reporteVentaConSucursalSinAlmacenSinEmpleadoAlquiler');



Route::get('reporteVentaConSucursalSinAlmacenConEmpleadoAlquiler/{idinicio}/{idfin}/{usuariologueado}/{idempleado}/{exten}/{sucursal}/{filt}', 'ReporteController@reporteVentaConSucursalSinAlmacenConEmpleadoAlquiler');
Route::get('reporteVentaConSucursalConAlmacenConEmpleadoAlquiler/{idinicio}/{idfin}/{usuariologueado}/{idempleado}/{exten}/{sucursal}/{almacen}/{filt}', 'ReporteController@reporteVentaConSucursalConAlmacenConEmpleadoAlquiler');


Route::get('detallecomprasactual', 'FrontController@detallecomprasactual');
Route::get('detallecomprasporusuario', 'FrontController@detallecomprasporusuario');

Route::get('reportDetalleCompraNO/{fechainicio}/{fechafin}/{idempleado}/{idusuario}/{exten}', 'ReporteController@reporteDetalleCompraNoAlmacen');
Route::get('reportDetalleCompra/{fechainicio}/{fechafin}/{idempleado}/{idusuario}/{exten}/{almacen}', 'ReporteController@reportDetalleCompra');

Route::get('reporteDetalleCompraSinSucursalSinEmpleadoSinSucursal/{fechainicio}/{fechafin}/{idempleado}/{exten}', 'ReporteController@reporteDetalleCompraSinSucursalSinEmpleadoSinSucursal');
Route::get('compraDetalleCompraConSucursalSinAlmacenConEmpleado/{fechainicio}/{fechafin}/{idempleado}/{usuariologeado}/{exten}/{sucursal}', 'ReporteController@compraDetalleCompraConSucursalSinAlmacenConEmpleado');

Route::get('compraDetalleCompraConSucursal/{fechainicio}/{fechafin}/{idempleado}/{exten}/{sucursal}', 'ReporteController@compraDetalleCompraConSucursal');

Route::get('reportDetalleCompraConSucursalConEmpleadoConAlmacen/{fechainicio}/{fechafin}/{usuario}/{idempleado}/{exten}/{sucursal}/{almacen}', 'ReporteController@reportDetalleCompraConSucursalConEmpleadoConAlmacen');

Route::get('ProductosResto', 'FrontController@productoresto');
Route::get('AddProductoResto', 'FrontController@nuevoproductoresto');
Route::put('ProductoResto', 'ProductoController@productoresto');
Route::get('imprirfacturaresto/{id}', 'FacturaController@imprirfacturaresto');

Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
});


Route::get('imprimirnotaventaresto/{id}', 'VentaController@imprimirnotaventa2');


Route::get('obtenerporductocreditos/{id}', 'CobroCuotaController@obtenerporductocredito');
Route::get('obtenerporductoscombo/{id}', 'DetalleVentaController@obtenerporductoscombo');

//obtenerporductoscombo

Route::get('enviarmodista/{id}', 'CobroCuotaController@productomodista');
Route::get('entregarcliente/{id}', 'CobroCuotaController@entregarcliente');
Route::get('imprimirenviomodista/{id}', 'CobroCuotaController@imprimirenviomodista');
Route::get('imprimirentrega/{id}', 'CobroCuotaController@imprimirentrega');
Route::get('GenerarAlquiler/{id}', 'VentaController@generaralquiler');
Route::get('alquilernuevo/{id}', 'FrontController@alquiler');
Route::get('listalquiler', 'AlquilerController@listalquilar');
Route::get('editaralquiler/{id}', 'FrontController@editaralquiler');
Route::get('obtenerservicios', 'AlquilerController@obtenerservicios');
Route::get('buscarbarcode/{id}/{idalmacen}/{tipoventa}', 'VentaController@buscarcodigodebarra');
Route::get('buscarcodintero/{id}/{idalmacen}/{tipoventa}/{sucursal}', 'VentaController@buscarcodigointerno');

Route::get('buscarnombreproducto/{id}/{idalmacen}/{tipoventa}/{sucursal}', 'VentaController@buscarnombreproducto');
Route::get('buscarnombreproductocompra/{id}/{idalmacen}', 'ComprasController@buscarnombreproductocompra');

Route::resource('Alquiler', 'AlquilerController');
Route::put('actualizarcantidad', 'DetalleventaController@actualizarcantidad');



////Modificaciones Danny
Route::get('ReportVentaDetalleBelleMarie', 'FrontController@ReportDetalleVentaBelleMarie');

Route::get('reportDetalleVentaConEmpleadoConSucursalSinAlmacen/{fechainicio}/{fechafin}/{idempleado}/{idusuario}/{exten}/{sucursal}', 'ReporteController@reporteDetalleVentasConEmpleadoConSucursalSinAlmacen');
Route::get('ReporteTxNInventario', 'FrontController@ReportTxNInventario');
Route::get('listarEmpleadoInv/{empleado}', 'EmpleadoController@listarEmpleado');
Route::get('listarinventarioConEmpleadoSinMotivo/{empleado}/{punto}/{fechaini}/{fechafin}', 'InventarioController@listarinventarioConEmpleadoSinMotivo');
Route::get('listarinventarioSoloFecha/{fechaini}/{fechafin}', 'InventarioController@listarinventarioSoloFecha');
Route::get('listarinventarioSoloMotivo/{motivo}/{fechaini}/{fechafin}', 'InventarioController@listarinventarioSoloMotivo');
Route::get('listarinventarioConEmpleadoConPuntoConMotivo/{empleado}/{punto}/{fechaini}/{fechafin}/{motivo}', 'InventarioController@listarinventarioConEmpleadoConPuntoConMotivo');

Route::get('cambiarContraseniaDeUsuario/{id}', 'FrontController@cambiarContraseniaDeUsuario');
Route::POST('actualizarcontrasenia', 'UsuarioController@actualizarcontrasenia');
Route::get('ReportVentasanuladasyeliminadas', 'FrontController@ReportVentasanuladasyeliminadas');
Route::get('listadoVentasAnuladas/{idinicio}/{idfin}/{idempleado}/{exten}', 'ReporteController@listadoVentasAnuladas');
Route::get('listadoVentasSinEmpleadoConSucursalSinAlmacenAnulada/{idinicio}/{idfin}/{idempleado}/{exten}/{sucursal}', 'ReporteController@listadoVentasSinEmpleadoConSucursalSinAlmacenAnulada');
Route::get('listadoVentasConEmpleadoConSucursalSinAlmacenAnulada/{idinicio}/{idfin}/{usuariologueado}/{idempleado}/{exten}/{sucursal}', 'ReporteController@listadoVentasConEmpleadoConSucursalSinAlmacenAnulada');
Route::get('listadoVentasConEmpleadoSinSucursalSinAlmacenAnulada/{idinicio}/{idfin}/{usuariologueado}/{idempleado}/{exten}', 'ReporteController@listadoVentasConEmpleadoSinSucursalSinAlmacenAnulada');
Route::get('listadoVentasConEmpleadoConSucursalConAlmacenAnulada/{idinicio}/{idfin}/{usuariologueado}/{idempleado}/{exten}/{sucursal}/{almacen}', 'ReporteController@listadoVentasConEmpleadoConSucursalConAlmacenAnulada');
Route::get('listadoVentasEliminadas/{idinicio}/{idfin}/{idempleado}/{exten}', 'ReporteController@listadoVentasEliminadas');
Route::get('listadoVentasSinEmpleadoConSucursalSinAlmacenEliminadas/{idinicio}/{idfin}/{idempleado}/{exten}/{sucursal}', 'ReporteController@listadoVentasSinEmpleadoConSucursalSinAlmacenEliminadas');
Route::get('listadoVentasConEmpleadoConSucursalSinAlmacenEliminar/{idinicio}/{idfin}/{usuariologueado}/{idempleado}/{exten}/{sucursal}', 'ReporteController@listadoVentasConEmpleadoConSucursalSinAlmacenEliminar');
Route::get('listadoVentasConEmpleadoSinSucursalSinAlmacenEliminadas/{idinicio}/{idfin}/{usuariologueado}/{idempleado}/{exten}', 'ReporteController@listadoVentasConEmpleadoSinSucursalSinAlmacenEliminadas');
Route::get('listadoVentasConEmpleadoConSucursalConAlmacenEliminada/{idinicio}/{idfin}/{usuariologueado}/{idempleado}/{exten}/{sucursal}/{almacen}', 'ReporteController@listadoVentasConEmpleadoConSucursalConAlmacenEliminada');
Route::get('ReportVentaACredito', 'FrontController@ReportVentaACredito');
Route::get('reporteVentaCreditoSinEmpleadoSinSucursalSinCliente/{idinicio}/{idfin}/{usuariologeado}/{exten}', 'ReporteController@reporteVentaCreditoSinEmpleadoSinSucursalSinCliente');
Route::get('reporteVentaCreditoSinEmpleadoConSucursalSinCliente/{idinicio}/{idfin}/{usuariologeado}/{exten}/{sucursal}', 'ReporteController@reporteVentaCreditoSinEmpleadoConSucursalSinCliente');
Route::get('reporteVentaCreditoConEmpleadoConSucursalSinCliente/{idinicio}/{idfin}/{idempleado}/{usuariologeado}/{exten}/{sucursal}', 'ReporteController@reporteVentaCreditoConEmpleadoConSucursalSinCliente');
Route::get('reporteVentaCreditoConEmpleadoConSucursalConCliente/{idinicio}/{idfin}/{idempleado}/{usuariologeado}/{exten}/{sucursal}/{cliente}', 'ReporteController@reporteVentaCreditoConEmpleadoConSucursalConCliente');
Route::get('reporteVentaCreditoSinEmpleadoSinSucursalConCliente/{idinicio}/{idfin}/{usuariologeado}/{exten}/{cliente}', 'ReporteController@reporteVentaCreditoSinEmpleadoSinSucursalConCliente');
Route::get('ReportVentaDetalleBelleMarie', 'FrontController@ReportDetalleVentaBelleMarie');
Route::get('ventaDetalleVentaSinSucursaSinAlmacenSinEmpleadoBelleMarie/{fechainicio}/{fechafin}/{usuariologeado}/{exten}', 'ReporteController@ventaDetalleVentaSinSucursaSinAlmacenSinEmpleadoBelleMarie');
Route::get('ventaDetalleVentaConSucursaSinAlmacenSinEmpleadoBelleMarie/{fechainicio}/{fechafin}/{usuariologeado}/{exten}/{sucursal}', 'ReporteController@ventaDetalleVentaConSucursaSinAlmacenSinEmpleadoBelleMarie');
Route::get('ventaDetalleVentaConSucursaSinAlmacenConEmpleadoBelleMarie/{fechainicio}/{fechafin}/{empleado}/{usuariologeado}/{exten}/{sucursal}', 'ReporteController@ventaDetalleVentaConSucursaSinAlmacenConEmpleadoBelleMarie');
Route::get('ventaDetalleVentaConSucursaConAlmacenConEmpleadoBelleMarie/{fechainicio}/{fechafin}/{empleado}/{usuariologeado}/{exten}/{sucursal}/{almacen}', 'ReporteController@ventaDetalleVentaConSucursaConAlmacenConEmpleadoBelleMarie');
Route::get('gastoCompra/{idcompra}', 'FrontController@VistaGastoCompra');
Route::get('listarGastoCompra', 'TipoGastoCompraController@listarGastoCompra');
Route::resource('planillaGastoCompra', 'planillaGastoCompraController');
Route::get('listarPlanillagasto/{id}', 'planillaGastoCompraController@listarPlanillagasto');
Route::get('listarPlanillagastoEditar/{id}', 'planillaGastoCompraController@listarPlanillagastoEditar');
Route::GET('elimanraGasto', 'planillaGastoCompraController@elimnars');
Route::get('GestionarGastoCompra', 'FrontController@GestionarGastoCompra');
Route::resource('TipoGastoCompra', 'TipoGastoCompraController');
Route::get('reportPlanillaGasto/{UsuarioLogeado}/{idCompra}/{exten}', 'planillaGastoCompraController@reportPlanillaGasto');
Route::GET('reportPlanillaGastoHayDatos', 'planillaGastoCompraController@ComprobanteDatos');

Route::get('ReportVentasanuladasyeliminadas', 'FrontController@ReportVentasanuladasyeliminadas');
Route::get('listadoVentasAnuladas/{idinicio}/{idfin}/{idempleado}/{exten}', 'ReporteController@listadoVentasAnuladas');
Route::get('listadoVentasSinEmpleadoConSucursalSinAlmacenAnulada/{idinicio}/{idfin}/{idempleado}/{exten}/{sucursal}', 'ReporteController@listadoVentasSinEmpleadoConSucursalSinAlmacenAnulada');
Route::get('listadoVentasConEmpleadoConSucursalSinAlmacenAnulada/{idinicio}/{idfin}/{usuariologueado}/{idempleado}/{exten}/{sucursal}', 'ReporteController@listadoVentasConEmpleadoConSucursalSinAlmacenAnulada');
Route::get('listadoVentasConEmpleadoSinSucursalSinAlmacenAnulada/{idinicio}/{idfin}/{usuariologueado}/{idempleado}/{exten}', 'ReporteController@listadoVentasConEmpleadoSinSucursalSinAlmacenAnulada');
Route::get('listadoVentasConEmpleadoConSucursalConAlmacenAnulada/{idinicio}/{idfin}/{usuariologueado}/{idempleado}/{exten}/{sucursal}/{almacen}', 'ReporteController@listadoVentasConEmpleadoConSucursalConAlmacenAnulada');


Route::get('listadoVentasEliminadas/{idinicio}/{idfin}/{idempleado}/{exten}', 'ReporteController@listadoVentasEliminadas');
Route::get('listadoVentasSinEmpleadoConSucursalSinAlmacenEliminadas/{idinicio}/{idfin}/{idempleado}/{exten}/{sucursal}', 'ReporteController@listadoVentasSinEmpleadoConSucursalSinAlmacenEliminadas');
Route::get('listadoVentasConEmpleadoConSucursalSinAlmacenEliminar/{idinicio}/{idfin}/{usuariologueado}/{idempleado}/{exten}/{sucursal}', 'ReporteController@listadoVentasConEmpleadoConSucursalSinAlmacenEliminar');
Route::get('listadoVentasConEmpleadoSinSucursalSinAlmacenEliminadas/{idinicio}/{idfin}/{usuariologueado}/{idempleado}/{exten}', 'ReporteController@listadoVentasConEmpleadoSinSucursalSinAlmacenEliminadas');
Route::get('listadoVentasConEmpleadoConSucursalConAlmacenEliminada/{idinicio}/{idfin}/{usuariologueado}/{idempleado}/{exten}/{sucursal}/{almacen}', 'ReporteController@listadoVentasConEmpleadoConSucursalConAlmacenEliminada');

Route::get('ReportVentaACredito', 'FrontController@ReportVentaACredito');
Route::get('reporteVentaCreditoSinEmpleadoSinSucursalSinCliente/{idinicio}/{idfin}/{usuariologeado}/{exten}', 'ReporteController@reporteVentaCreditoSinEmpleadoSinSucursalSinCliente');
Route::get('reporteVentaCreditoSinEmpleadoConSucursalSinCliente/{idinicio}/{idfin}/{usuariologeado}/{exten}/{sucursal}', 'ReporteController@reporteVentaCreditoSinEmpleadoConSucursalSinCliente');
Route::get('reporteVentaCreditoConEmpleadoConSucursalSinCliente/{idinicio}/{idfin}/{idempleado}/{usuariologeado}/{exten}/{sucursal}', 'ReporteController@reporteVentaCreditoConEmpleadoConSucursalSinCliente');
Route::get('reporteVentaCreditoConEmpleadoConSucursalConCliente/{idinicio}/{idfin}/{idempleado}/{usuariologeado}/{exten}/{sucursal}/{cliente}', 'ReporteController@reporteVentaCreditoConEmpleadoConSucursalConCliente');
Route::get('reporteVentaCreditoSinEmpleadoSinSucursalConCliente/{idinicio}/{idfin}/{usuariologeado}/{exten}/{cliente}', 'ReporteController@reporteVentaCreditoSinEmpleadoSinSucursalConCliente');

Route::get('reporteVentaCreditoAtrasadasSinEmpleadoSinSucursalSinCliente/{usuariologeado}/{exten}/{idinicio}/{idfin}', 'ReporteController@reporteVentaCreditoAtrasadasSinEmpleadoSinSucursalSinCliente');
Route::get('reporteVentaCreditoAtrasadasSinEmpleadoSinSucursalConCliente/{idcliente}/{usuariologeado}/{exten}/{idinicio}/{idfin}', 'ReporteController@reporteVentaCreditoAtrasadasSinEmpleadoSinSucursalConCliente');
Route::get('reporteCreditoVentaAtrasadasSinEmpleadoConSucursalConCliente/{idcliente}/{usuariologeado}/{exten}/{sucursal}/{idinicio}/{idfin}', 'ReporteController@reporteCreditoVentaAtrasadasSinEmpleadoConSucursalConCliente');
Route::get('reporteCreditoVentaAtrasadasConEmpleadoConSucursalConCliente/{idemple}/{idcliente}/{usuariologeado}/{exten}/{sucursal}/{idinicio}/{idfin}', 'ReporteController@reporteCreditoVentaAtrasadasConEmpleadoConSucursalConCliente');
Route::get('reporteCreditoVentaAtrasadasConEmpleadoConSucursalSinCliente/{idemple}/{usuariologeado}/{exten}/{sucursal}/{idinicio}/{idfin}', 'ReporteController@reporteCreditoVentaAtrasadasConEmpleadoConSucursalSinCliente');
Route::get('reporteVentaCreditoAtrasadasSinEmpleadoConSucursalSinCliente/{usuariologeado}/{exten}/{sucursal}/{idinicio}/{idfin}', 'ReporteController@reporteVentaCreditoAtrasadasSinEmpleadoConSucursalSinCliente');

Route::get('reportflujoingresosegresos', 'FrontController@reportflujoingresosegresos');
Route::get('reporteFlujoIngresosEgresos/{fechaini}/{fechafin}/{usrlogeado}/{exte}', 'ReporteController@reporteFlujoIngresosEgresos');
Route::get('reporteFlujoIngresosEgresosConSucursal/{fechaini}/{fechafin}/{usrlogeado}/{exte}/{sucursal}', 'ReporteController@reporteFlujoIngresosEgresosConSucursal');


Route::POST('cambiarimagen', 'UsuarioController@cambiarimagen');

Route::get('listaregresotipo', 'EgresoController@listaregresos');
Route::get('reporteEgresoConEmpleadoConTipo/{idinicio}/{idfin}/{idempleado}/{idusuariologeado}/{exten}/{tipo}', 'ReporteController@reporteEgresoConEmpleadoConTipo');
Route::get('reporteEgresoConTipo/{idinicio}/{idfin}/{idusuariologeado}/{exten}/{tipo}', 'ReporteController@reporteEgresoConTipo');

Route::get('imprimirnotaalquiler/{id}', 'VentaController@imprimirnotalquiler');

Route::get('obtenerdatosvent/{id}', 'VentaController@obtenerdatosvent');
Route::get('updatedate', 'VentaController@updatedate');
Route::get('updatecancel', 'VentaController@updatecancel');
Route::get('404', 'FrontController@page404');

//Reporte Creditos Consolidado
Route::get('reporteVentaCreditoSinEmpleadoSinSucursalSinClienteConsolidado/{idinicio}/{idfin}/{usuariologeado}/{exten}', 'ReporteController@reporteVentaCreditoSinEmpleadoSinSucursalSinClienteConsolidado');
Route::get('reporteVentaCreditoSinEmpleadoConSucursalSinClienteConsolidado/{idinicio}/{idfin}/{usuariologeado}/{exten}/{sucursal}', 'ReporteController@reporteVentaCreditoSinEmpleadoConSucursalSinClienteConsolidado');
Route::get('reporteVentaCreditoConEmpleadoConSucursalSinClienteConsolidado/{idinicio}/{idfin}/{idempleado}/{usuariologeado}/{exten}/{sucursal}', 'ReporteController@reporteVentaCreditoConEmpleadoConSucursalSinClienteConsolidado');
Route::get('reporteVentaCreditoConEmpleadoConSucursalConClienteConsolidado/{idinicio}/{idfin}/{idempleado}/{usuariologeado}/{exten}/{sucursal}/{cliente}', 'ReporteController@reporteVentaCreditoConEmpleadoConSucursalConClienteConsolidado');
Route::get('reporteVentaCreditoSinEmpleadoSinSucursalConClienteConsolidado/{idinicio}/{idfin}/{usuariologeado}/{exten}/{cliente}', 'ReporteController@reporteVentaCreditoSinEmpleadoSinSucursalConClienteConsolidado');



//RONNY ACTUALIZACION
Route::resource('Productosucursal', 'ProductosucursalController@productosucursal2');
Route::resource('Productosucursal3', 'ProductosucursalController@productosucursal3');
Route::resource('sucursales', 'SucursalController@sucursales');
Route::resource('almacenes', 'AlmacenController@almacenes');
Route::get('cargarproductosucursal/{valor}', 'ProductosucursalController@consultaproductosucursal');
Route::resource('guardarproductosucursal', 'ProductosucursalController');


Route::get('reporteRankingOrdenarTallaSinSucursalSinEmpleadoSinAlmacen/{idinicio}/{idfin}/{idempleado}/{exten}', 'ReporteController@reporteRankingOrdenarTallaSinSucursalSinEmpleadoSinAlmacen');
Route::get('reporteRankingOrdenarTallaConSucursalSinEmpleadoSinAlmacen/{idinicio}/{idfin}/{idempleado}/{exten}/{sucursal}', 'ReporteController@reporteRankingOrdenarTallaConSucursalSinEmpleadoSinAlmacen');
Route::get('reporteRankingOrdenarTallaConSucursalConEmpleadoSinAlmacen/{idinicio}/{idfin}/{idempleado}/{idusuario}/{exten}/{sucursal}', 'ReporteController@reporteRankingOrdenarTallaConSucursalConEmpleadoSinAlmacen');
Route::get('reporteRankingOrdenarTallaConSucursalConEmpleadoConAlmacen/{idinicio}/{idfin}/{idempleado}/{idusuario}/{exten}/{sucursal}/{almacen}', 'ReporteController@reporteRankingOrdenarTallaConSucursalConEmpleadoConAlmacen');
Route::get('reporteRankingOrdenarTallaConSucursalSinEmpleadoConAlmacen/{idinicio}/{idfin}/{idusuario}/{exten}/{sucursal}/{almacen}', 'ReporteController@reporteRankingOrdenarTallaConSucursalSinEmpleadoConAlmacen');
Route::get('reporteRankingOrdenarCantidadConSucursalSinEmpleadoConAlmacen/{idinicio}/{idfin}/{idusuario}/{exten}/{sucursal}/{almacen}', 'ReporteController@reporteRankingOrdenarCantidadConSucursalSinEmpleadoConAlmacen');
Route::get('reporteRankingOrdenarImporteConSucursalSinEmpleadoConAlmacen/{idinicio}/{idfin}/{idusuario}/{exten}/{sucursal}/{almacen}', 'ReporteController@reporteRankingOrdenarImporteConSucursalSinEmpleadoConAlmacen');
Route::get('reporteRankingSinOrdenarConSucursalSinEmpleadoConAlmacen/{idinicio}/{idfin}/{idusuario}/{exten}/{sucursal}/{almacen}', 'ReporteController@reporteRankingSinOrdenarConSucursalSinEmpleadoConAlmacen');


Route::get('VentaCategoria', 'FrontController@VentaCategoria');
Route::get('VentaEnvio', 'FrontController@VentaEnvio');

Route::get('reporteVentaCateogria/{idinicio}/{idfin}/{idempleado}/{exten}', 'ReporteController@reporteVentaPorCategoria');
Route::get('reporteVentaPorEnvios/{idinicio}/{idfin}/{idempleado}/{exten}', 'ReporteController@reporteVentaPorEnvios');


Route::get('reporteVentaCateogriaSinSucursaSinAlmacenSinEmpleado/{fechainicio}/{fechafin}/{idusuariologeado}/{exten}', 'ReporteController@reporteVentaCateogriaSinSucursaSinAlmacenSinEmpleado');
Route::get('reporteVentaCateogriaConSucursaSinAlmacenSinEmpleado/{fechainicio}/{fechafin}/{idusuariologeado}/{exten}/{sucursal}', 'ReporteController@reporteVentaCateogriaConSucursaSinAlmacenSinEmpleado');
Route::get('reporteVentaCateogriaConSucursaSinAlmacenConEmpleado/{fechainicio}/{fechafin}/{idempleado}/{idusuariologeado}/{exten}/{sucursal}', 'ReporteController@reporteVentaCateogriaConSucursaSinAlmacenConEmpleado');
Route::get('reporteVentaCateogriaConSucursaConAlmacenConEmpleado/{fechainicio}/{fechafin}/{idempleado}/{idusuariologeado}/{exten}/{sucursal}/{alamcen}', 'ReporteController@reporteVentaCateogriaConSucursaConAlmacenConEmpleado');


Route::get('reporteVentaPorEnviosSinSucursaSinAlmacenSinEmpleado/{fechainicio}/{fechafin}/{idusuariologeado}/{exten}', 'ReporteController@reporteVentaPorEnviosSinSucursaSinAlmacenSinEmpleado');
Route::get('reporteVentaPorEnviosConSucursaSinAlmacenSinEmpleado/{fechainicio}/{fechafin}/{idusuariologeado}/{exten}/{sucursal}', 'ReporteController@reporteVentaPorEnviosConSucursaSinAlmacenSinEmpleado');
Route::get('reporteVentaPorEnviosConSucursaSinAlmacenConEmpleado/{fechainicio}/{fechafin}/{idempleado}/{idusuariologeado}/{exten}/{sucursal}', 'ReporteController@reporteVentaPorEnviosConSucursaSinAlmacenConEmpleado');
Route::get('reporteVentaPorEnviosConSucursaConAlmacenConEmpleado/{fechainicio}/{fechafin}/{idempleado}/{idusuariologeado}/{exten}/{sucursal}/{alamcen}', 'ReporteController@reporteVentaPorEnviosConSucursaConAlmacenConEmpleado');

/*VENTAS MES */
Route::POST('faltanterafa', 'VentaController@faltanterafa');
Route::GET('eqq/{producto}/{almacen}', 'VentaController@eqq');

Route::GET('faltanterafa2/{mes}/{almacen}', 'VentaController@faltanterafa2');

///ROUTES DADCO
Route::get('GestionarSubcategoriarafa/{id}', 'TipoProductoController@GestionarSubcategoriarafa');
Route::get('listarsubtipoproducto/{categoria}','SubtipoProductoController@listarSubTipoProducto');
Route::get('listadeproducto', 'ProductoController@listarporductos');

//PROFORMA
/*Route::get('listadeproforma', 'FrontController@vistadeproforma');
Route::get('GenerarProforma/{id}', 'ProformaController@generarproformas');
Route::get('Proformas/{id}', 'FrontController@proforma');
Route::get('detalleproformas/{id}', 'DetalleproformaController@productocondetalledeproforma');
Route::get('Validarproformantigua/{id}', 'ProformaController@Validarventaantigua');
Route::resource('Detalleproforma', 'DetalleproformaController');
Route::get('listarproformas', 'ProformaController@listarproforma');
Route::get('editarproforma/{id}', 'FrontController@editarproforma');
Route::resource('Proforma', 'ProformaController');
Route::get('imprimirProforma/{id}', 'ProformaController@imprimirnotaventa');
Route::get('AnularProforma/{id}', 'ProformaController@AnularProforma');
Route::get('convertirProformaVenta/{idpunto}/{idproforma}', 'ProformaController@convertirProformaVenta');*/