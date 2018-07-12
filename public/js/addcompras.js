var iddelpuntoventa;
var idempleado;
var nroCuenta;
var idselecionado;
var idselecionadoModal;
$(document).ready(function () {
    iddelpuntoventa = $('#iddelpuntoventa').val();
    idempleado = $('#iddelempleado').val();
    $('#tablaGasto').DataTable().destroy();
    cargartabla();
    Cargar();
    cargarCuentaModal();
    listarConcepto();
    listarProveedor();
    cargarCuenta1();
    listarGastoCompra();
    $("select#concepto").change(function () {
        idselecionado = $(this).val();
        nroCuenta = 0;
        if (idselecionado === "1") {  //efectivo
            $("#cuentabanco").attr("style", "display :none;");
            $("#cuentacheque").attr("style", "display :none;");
        }

        if (idselecionado === "2") {  //Banco
            $("#cuentabanco").attr("style", "display :block;");
            $("#cuentacheque").attr("style", "display :none;");

        }

        if (idselecionado === "3") {  //Cheque
            $("#cuentabanco").attr("style", "display :none;");
            $("#cuentacheque").attr("style", "display :block;");
        }
    });

    $("select#conceptoModal").change(function () {
        idselecionadoModal = $(this).val();
        if (idselecionadoModal === "1") {  //efectivo
            $("#cuentabancoModal").attr("style", "display :none;");
            $("#cuentachequeModal").attr("style", "display :none;");
        }
        if (idselecionadoModal === "2") {  //Banco
            $("#cuentabancoModal").attr("style", "display :block;");
            $("#cuentachequeModal").attr("style", "display :none;");

        }
        if (idselecionadoModal === "3") {  //Cheque
            $("#cuentabancoModal").attr("style", "display :none;");
            $("#cuentachequeModal").attr("style", "display :block;");
        }
    });
    $('#importe').formatter({
        'pattern': '{{999999999999999}}',
        'persistent': true
    });

});
/*
 * Metodo para mostrar las compras realizadas
 */
function Cargar() {
    $('#datos').empty();
    var route = "/listarcompras";
    var token = $("#token").val();
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'POST',
        dataType: 'json',
        data: {
            idempleado: idempleado,
            iddelpuntoventa: iddelpuntoventa
        },
        success: function ($route) {
            var tabladatos = $('#datos');
            $($route).each(function (key, value) {
                var estados = "";
                var ordenestado = "";
                var totales;
                if (value.estado == 1) {
                    estados = "Comprado";
                }
                if (value.estado == 2) {
                    estados = "Credito";
                }
                if (value.estado == 0) {
                    estados = "Guardado";
                }
                if (value.total == null) {
                    totales = 0;
                } else {
                    totales = value.total;
                }
                var s = parseFloat(value.total).toFixed(1) - parseFloat(value.cobro).toFixed(1);
                if (s <= 0) {
                    s = 0;
                }
                if(value.ordenestado==0) 
                {ordenestado="Enviado"}
                else 
                 if(value.ordenestado==1)
                   {ordenestado="Llegado"}
                   debugger
                   ////CALCULO DE TIEMPO DE ESPERA_ORDEN PARA VER EL ESTADO//////
                   var hoy=new Date();
                   var dd=hoy.getDate()
                   var mm=hoy.getMonth()+1
                   var yyyy=hoy.getFullYear()
                   if(dd<10) dd='0'+dd
                   if(mm<10) mm='0'+mm
                   hoy=yyyy+'-'+mm+'-'+dd
                   //////////////////////
                   
                   //////////////////////
                   var fechainicio=new Date(value.fecha).getTime();
                   var fechaactual=new Date(hoy).getTime();
                   var diff=fechaactual-fechainicio;
                   var diferencia=diff/(1000*60*60*24);//diferencia en dias                   
                   var tiempodeorden=value.tiempodeorden;
                   var diascumple=tiempodeorden-diferencia;
                   var bandera;
                   var cero=0                  
                   if(diascumple==0 )
                       {
                        bandera="llego"    
                       }else
                       {
                       if (diascumple!=0)
                           bandera="no_llego"
                       } 
                                            
                   /////////////////////////////////////////
                if (s == 0 && estados == "Credito") {
                    estados = "Credito Finalizado";
                }
                if (s > 0 && estados == "Credito") {
                    estados = "Credito Vigente";
                }
                if (estados == "Guardado" && bandera=="no_llego") {
                    if (diascumple<0 && estados=="Guardado" && bandera=="no_llego")
                        {// llego la orden pero nuna la registro por eso es orange 
                           bandera="llego"
                    tabladatos.append("<tr>" +
                            "<td>" + value.id + "</td>" +
                            "<td>" + value.fecha + "</td>" +
                            "<td>" + value.hora + "</td>" +
                            "<td>" + value.almacen + "</td>" +
                            "<td>" + value.proveedores + "</td>" +
                            "<td>" + estados + "</td>" +
                            "<td bgcolor='orange'>" + bandera + "</td>" +
                            //"<td>" + value.nombre + "</td>" +
                            "<td>" + cero + "</td>" +                            
                            "<td>" + totales + "</td>" +
                            "<td>" + value.formaPago + "</td>" +
                            "<td>" + value.saldo + "</td>" +
                            "<td></td>" +
                            "<td><button value=" + value.id + " OnClick='Mostrar(" + value.id + ");' class='btn btn-floating waves-effect'  href='#' title='Editar' title='Editar'>" +
                            "<i class='material-icons'>mode_edit</i>" +
                            "</button>" +
                            "</td><td></td><td>" +
                            "<button class='btn btn-danger btn-floating waves-effect' value=" + value.id + " OnClick='Eliminar(this);' title='Eliminar' title='Eliminar'>" +
                            "<i class='material-icons'>delete</i>" +
                            "</button>" +
                            "</td></tr>");        
                        }else {
                            tabladatos.append("<tr>" +
                            "<td>" + value.id + "</td>" +
                            "<td>" + value.fecha + "</td>" +
                            "<td>" + value.hora + "</td>" +
                            "<td>" + value.almacen + "</td>" +
                            "<td>" + value.proveedores + "</td>" +
                            "<td>" + estados + "</td>" +
                            "<td bgcolor='red'>" + bandera + "</td>" +
                            //"<td>" + value.nombre + "</td>" +
                            "<td>" + diascumple + "</td>" +                            
                            "<td>" + totales + "</td>" +
                            "<td>" + value.formaPago + "</td>" +
                            "<td>" + value.saldo + "</td>" +
                            "<td></td>" +
                            "<td><button value=" + value.id + " OnClick='Mostrarsillego(" + value.id +","+ diascumple + ");' class='btn btn-floating waves-effect'  href='#' title='Editar' title='Editar'>" +
                            "<i class='material-icons'>mode_edit</i>" +
                            "</button>" +
                            "</td><td></td><td>" +
                            "<button class='btn btn-danger btn-floating waves-effect' value=" + value.id + " OnClick='Eliminar(this);' title='Eliminar' title='Eliminar'>" +
                            "<i class='material-icons'>delete</i>" +
                            "</button>" +
                            "</td></tr>");   
                        }
                    
                } else {
                    bandera='llego'                  
                    if  (bandera=='llego' || estados != "Guardado")
                        {
                            if (estados=="Guardado")
                                {
                                     tabladatos.append("<tr>" +
                            "<td>" + value.id + "</td>" +
                            "<td>" + value.fecha + "</td>" +
                            "<td>" + value.hora + "</td>" +
                            "<td>" + value.almacen + "</td>" +
                            "<td>" + value.proveedores + "</td>" +
                            "<td>" + estados + "</td>" +
                            "<td bgcolor='orange'>" + bandera + "</td>" +
                            //"<td>" + value.nombre + "</td>" +
                            "<td>" + cero + "</td>" +                            
                            "<td>" + totales + "</td>" +
                            "<td>" + value.formaPago + "</td>" +
                            "<td>" + value.saldo + "</td>" +
                            "<td></td>" +
                            "<td><button value=" + value.id + " OnClick='Mostrar(" + value.id + ");' class='btn btn-floating waves-effect'  href='#' title='Editar' title='Editar'>" +
                            "<i class='material-icons'>mode_edit</i>" +
                            "</button>" +
                            "</td><td></td><td>" +
                            "<button class='btn btn-danger btn-floating waves-effect' value=" + value.id + " OnClick='Eliminar(this);' title='Eliminar' title='Eliminar'>" +
                            "<i class='material-icons'>delete</i>" +
                            "</button>" +
                            "</td></tr>"); 
                                }else {
                    tabladatos.append("<tr>" +
                            "<td>" + value.id + "</td>" +
                            "<td>" + value.fecha + "</td>" +
                            "<td>" + value.hora + "</td>" +
                            "<td>" + value.almacen + "</td>" +
                            "<td>" + value.proveedores + "</td>" +
                            "<td>" + estados + "</td>" +
                            "<td bgcolor='green'>" + bandera + "</td>" +
                            //"<td>" + value.nombre + "</td>" +
                            "<td>" + cero + "</td>" +
                            "<td>" + totales + "</td>" +
                            "<td>" + value.formaPago + "</td>" +
                            "<td>" + value.saldoacobrar + "</td>" +
                            "<td>" +
                            "<button class='btn btn-danger btn-floating waves-effect' value=" + value.id + " OnClick='vistagasto(this);' title='Agregar Gasto'>" +
                            "<i class='mdi-av-playlist-add'></i>" +
                            "</button>" +
                            "</td>" +
                            "<td>" +
                            "</td><td>" +
                            "<button class='btn btn-danger btn-floating waves-effect' value=" + value.id + " OnClick='imprimir(this);' title='imprimir'>" +
                            "<i class='material-icons'>print</i>" +
                            "</button>" +
                            "</td><td>" +
                            "<button class='btn btn-danger btn-floating waves-effect' value=" + value.id + " OnClick='Eliminar(this);' title='Eliminar' title='Eliminar'>" +
                            "<i class='material-icons'>delete</i>" +
                            "</button>" +
                            "</td></tr>");   
                                }
                        }                    
                    }
              });
            paginador();
        },
        error: function () {
            swal({title: "ERROR",
                type: "error",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        }
    });
}

/*
 * Metodo para imprimir el comprobante de compra
 */
function vistagasto(btn) {
    window.location.href = ("/gastoCompra/" + btn.value);
}


$("#generarPlanillaGasto").click(function () {
    var idcompra = $('#idcompra').val();
    var route1 = "/reportPlanillaGastoHayDatos/";
    var token1 = $("#token").val();
    $.ajax({
        url: route1,
        headers: {'X-CSRF-TOKEN': token1},
        type: 'GET',
        dataType: 'json',
        data: {
            idcompra: idcompra
        },
        success: function () {

            var usuariologueado = $('#iddelempleado').val();
            var idcompra = $('#idcompra').val();
            swal({title: "Desea exportar a:",
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Pdf",
                cancelButtonText: "Excel",
                closeOnConfirm: true,
                closeOnCancel: true},
                    function (isConfirm) {

                        if (isConfirm) {
//                     swal("Deleted!", "Your imaginary file has been deleted.", "success");  
                            var exten = 0;
//                            var route = "/reportPlanillaGasto/"+usuariologueado+"/"+idcompra+"/"+exten;
                            window.open("/reportPlanillaGasto/" + usuariologueado + "/" + idcompra + "/" + exten);
//                            var token = $("#token").val();
//                            $.ajax({
//                                url: route,
//                                headers: {'X-CSRF-TOKEN': token},
//                                type: 'GET',
//                                dataType: 'json',
//                                data: {
//                                    usuariologueado: usuariologueado,
//                                    idcompra: idcompra,
//                                    exten: exten
//                                },
//                                success: function () {
//
//                                    swal({title: "Genero Exitosamente.",
//                                        type: "success",
//                                        showConfirmButton: false,
//                                        closeOnConfirm: false,
//                                        timer: 1000});
//                                    return;
//                                },
//                                error: function () {
//                                    swal({title: "NO EXISTE GASTOS EN LA COMPRA!!",
//                                        type: "error",
//                                        showConfirmButton: false,
//                                        closeOnConfirm: false,
//                                        timer: 1000});
//                                }
//                            });
                   } else {
                            var exten = 1;
                            window.open("/reportPlanillaGasto/" + usuariologueado + "/" + idcompra + "/" + exten);
                        }
                    });





        },
        error: function () {
            swal({title: "NO EXISTE GASTOS EN LA COMPRA!!",
                type: "error",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        }
    });





//    var usuariologueado = $('#iddelempleado').val();
//    var idcompra = $('#idcompra').val();
//    swal({title: "Desea exportar a:",
//        text: "",
//        type: "warning",
//        showCancelButton: true,
//        confirmButtonColor: "#DD6B55",
//        confirmButtonText: "Pdf",
//        cancelButtonText: "Excel",
//        closeOnConfirm: true,
//        closeOnCancel: true},
//            function (isConfirm) {
//
//                if (isConfirm) {
////                     swal("Deleted!", "Your imaginary file has been deleted.", "success");  
//                    var exten = 0;
//                    var route = "/reportPlanillaGastoAjax/";
//                    var token = $("#token").val();
//                    $.ajax({
//                        url: route,
//                        headers: {'X-CSRF-TOKEN': token},
//                        type: 'GET',
//                        dataType: 'json',
//                        data: {
//                            usuariologueado: usuariologueado,
//                            idcompra: idcompra,
//                            exten: exten
//                        },
//                        success: function () {
//
//                            swal({title: "Genero Exitosamente.",
//                                type: "success",
//                                showConfirmButton: false,
//                                closeOnConfirm: false,
//                                timer: 1000});
//                            return;
//                        },
//                        error: function () {
//                            swal({title: "NO EXISTE GASTOS EN LA COMPRA!!",
//                                type: "error",
//                                showConfirmButton: false,
//                                closeOnConfirm: false,
//                                timer: 1000});
//                        }
//                    });
//
//
//
//
//
//
//                } else {
//                    var exten = 1;
////                    
//                    var route = "/reportPlanillaGastoAjax/";
//                    var token = $("#token").val();
//                    $.ajax({
//                        url: route,
//                        headers: {'X-CSRF-TOKEN': token},
//                        type: 'GET',
//                        dataType: 'json',
//                        data: {
//                            usuariologueado: usuariologueado,
//                            idcompra: idcompra,
//                            exten: exten
//                        },
//                        success: function () {
//
//                            swal({title: "Genero Exitosamente.",
//                                type: "success",
//                                showConfirmButton: false,
//                                closeOnConfirm: false,
//                                timer: 1000});
//                            return;
//                        },
//                        error: function () {
//                            swal({title: "NO EXISTE GASTOS EN LA COMPRA!!",
//                                type: "error",
//                                showConfirmButton: false,
//                                closeOnConfirm: false,
//                                timer: 1000});
//                        }
//                    });
//
//                }
//            });
});


function cargarCuenta() {
    $('#concepto')
            .find('option')
            .remove()
            .end();
    $('#concepto').material_select();
    var route = "/listarCuentaBancaria/";
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $('#concepto').append('<option  value=' + value.nroCuenta + ' >' + value.banco + "  " + value.nroCuenta + '</option>');
            $('#concepto').material_select();
        });
    });
}

$("#agregarGasto").click(function () {
    var idcompra = $('#idcompra').val();
    var idTipoGastoCompra = $('#gastoCompra').val();
    var idProveedor = $('#proveedor').val();
    var idFormaPago = $('#concepto').val();
    if (idselecionado === "3") {
        nroCuenta = $("#cheque").val();
        if (nroCuenta === "") {
            Materialize.toast('Ingrese el Numero de Cuenta', 2500, 'rounded');
            return;
        }
    }
    if (idselecionado === "2") {
        nroCuenta = $("#cuentaBancaria").val();
        if (nroCuenta === "") {
            Materialize.toast('Ingrese el Numero de Cuenta', 2500, 'rounded');
            return;
        }
    }

    var importe = parseInt($("#importe").val()) + "";
    var afectaCostoProductoSI = document.getElementById('test1').checked;
    var afectaCostoProductoNO = document.getElementById('test2').checked;
    var afectaCostoProducto;
    if (afectaCostoProductoSI === true) {
        afectaCostoProducto = 1;
    }

    if (afectaCostoProductoNO === true) {
        afectaCostoProducto = 0;
    }


//  VALIDACIONES!!

    if (idProveedor === "") {
        Materialize.toast('Ingrese un Proveedor', 2500, 'rounded');
        return;
    }

    if (idTipoGastoCompra === "") {
        Materialize.toast('Ingrese el Tipo de Gasto', 2500, 'rounded');
        return;
    }


    if (!importe || !importe.trim().length || importe === "NaN") {
        Materialize.toast('Ingrese un Importe', 2500, 'rounded');
        return;
    }
    if (importe == 0) {
        Materialize.toast('Ingrese un Importe mayor a 0', 2500, 'rounded');
        return;
    }

    if (idFormaPago === "") {
        Materialize.toast('Ingrese una Forma de Pago', 2500, 'rounded');
        return;
    }


    var route = "/planillaGastoCompra";
    var token = $("#token").val();
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'POST',
        dataType: 'json',
        data: {
            idcompra: idcompra,
            idTipoGastoCompra: idTipoGastoCompra,
            idProveedor: idProveedor,
            idFormaPago: idFormaPago,
            nroCuenta: nroCuenta,
            importe: importe,
            afectaCostoProducto: afectaCostoProducto
        },
        success: function (json) {
            cargartabla();
            swal({title: json.message,
                type: "success",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});

            listarConcepto();
            listarProveedor();
            cargarCuenta1();
            listarGastoCompra();
            $("#importe").val("");

        },
        error: function () {
            swal({title: "Error al agregar el Gasto!",
                type: "error",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        }
    });
});

$("#actualizarGasto").click(function () {
    var idgastoG = $("#idGasto").val();
    var gastoCompraModalG = $("#gastoCompraModal").val();
    var proveedorModalG = $("#proveedorModal").val();
    var conceptoModalG = $("#conceptoModal").val();
    if (conceptoModalG === "1") {  //efectivo
        nroCuenta = 0;
    }
    if (conceptoModalG === "2") {  //Banco
        nroCuenta = $("#cuentaBancariaModal").val();
        if (nroCuenta === "") {
            Materialize.toast('Ingrese el Numero de Cuenta', 2500, 'rounded');
            return;
        }
    }

    if (conceptoModalG === "3") {  //Cheque
        nroCuenta = $("#chequeModal").val();
        if (nroCuenta === "") {
            Materialize.toast('Ingrese el Numero de Cuenta', 2500, 'rounded');
            return;
        }
    }


    var afectaCostoProductoSIModal = document.getElementById('test1Modal').checked;
    var afectaCostoProductoNOModal = document.getElementById('test2Modal').checked;
    var afectaCostoProductoModal;

    if (afectaCostoProductoSIModal === true) {
        afectaCostoProductoModal = 1;
    }

    if (afectaCostoProductoNOModal === true) {
        afectaCostoProductoModal = 0;
    }
    var importeModal = $('#importeModal').val();

    //VALIDACIONES!!
    if (proveedorModalG === "") {
        Materialize.toast('Ingrese un Proveedor', 2500, 'rounded');
        return;
    }

    if (gastoCompraModalG === "") {
        Materialize.toast('Ingrese el Tipo de Gasto', 2500, 'rounded');
        return;
    }

    if (importeModal === "") {
        Materialize.toast('Ingrese un Importe', 2500, 'rounded');
        return;
    }

    var route = "/planillaGastoCompra/" + idgastoG + "";
    var token = $("#token").val();
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'PUT',
        dataType: 'json',
        data: {
            gastoCompraModalG: gastoCompraModalG,
            proveedorModalG: proveedorModalG,
            nroCuenta: nroCuenta,
            conceptoModalG: conceptoModalG,
            afectaCostoProductoModal: afectaCostoProductoModal,
            importeModal: importeModal
        },
        success: function ( ) {
            swal({title: "ACTUALIZACION CORRECTAMENTE!!",
                type: "success",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
            $('#tabla').DataTable().destroy();
            cargartabla();
            $('#modalGasto').closeModal();

            $('.lean-overlay').remove();

        }, error: function () {
            Materialize.toast('Error al Actualizar!! ', 2000, 'rounded');
        }
    });
});

function cargartabla() {
    var tabladatos = $('#datosGasto');
    var idcompra = $('#idcompra').val();
    var route = "/listarPlanillagasto/" + idcompra;
    $('#datosGasto').empty();
    $('#datos').empty();

    $.get(route, function (res) {
        $(res).each(function (key, value) {
            var afecta;
            var bandera = value.afectaCostoProducto;
            if (bandera == "1") {
                afecta = "SI";
            }
            if (bandera == "0") {
                afecta = "NO";
            }
//            $('#tabla').DataTable().destroy();
            tabladatos.append("<tr>" +
                    "<td>" + value.fecha + "</td>" +
                    "<td>" + value.hora + "</td>" +
                    "<td>" + value.tipoGasto + "</td>" +
                    "<td>" + value.proveedor + "</td>" +
                    "<td>" + value.formaPago + "</td>" +
                    "<td>" + value.importe + "</td>" +
                    "<td>" + afecta + "</td>" +
                    "<td>" +
                    "<button value=" + value.id + " OnClick='openmodalGasto(this);' class='waves-effect waves-light btn modal-trigger'  href='#modalGasto' title='Editar'>" +
                    "<i class='material-icons'>mode_edit</i>" +
                    "</button>" +
                    "</td><td>" +
                    "<button class='btn btn-danger' value=" + value.id + " OnClick='EliminarGasto(this);' title='Eliminar'>" +
                    "<i class='material-icons'>delete</i>" +
                    "</button>" +
                    "</td>" +
                    "</tr>");
        });
        $('.modal-trigger').leanModal({
            dismissible: false,
            complete: function () {
                $('.lean-overlay').remove();
            }
        });
        paginadorGasto();
    });
}


function openmodalGasto(btn) {
    $("#modalGasto").openModal();
    MostrarGasto(btn);
}


function MostrarGasto(btn) {
    var route = "/listarPlanillagastoEditar/" + btn.value;
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $("#idGasto").val(value.id);
            $("#gastoCompraModal").val(value.idTipoGastoCompra);
            $('#gastoCompraModal option:selected').val(value.idTipoGastoCompra);
            $("#gastoCompraModal").material_select();

            $("#proveedorModal").val(value.idProveedor);
            $('#proveedorModal option:selected').val(value.idProveedor);
            $("#proveedorModal").material_select();



            if (value.idFormaPago === "1") {  //efectivo
                $("#cuentabancoModal").attr("style", "display :none;");
                $("#cuentachequeModal").attr("style", "display :none;");
            }

            if (value.idFormaPago === "2") {  //Banco
                $("#cuentabancoModal").attr("style", "display :block;");
                $("#cuentachequeModal").attr("style", "display :none;");
                $("#cuentaBancariaModal").val(value.nroCuenta);

            }

            if (value.idFormaPago === "3") {  //Cheque
                $("#cuentabancoModal").attr("style", "display :none;");
                $("#cuentachequeModal").attr("style", "display :block;");
                $("#chequeModal").val(value.nroCuenta);
            }


            $("#conceptoModal").val(value.idFormaPago);
            $('#conceptoModal option:selected').val(value.idFormaPago);
            $("#conceptoModal").material_select();


            if (value.afectaCostoProducto === "1") {        //SI
                document.getElementById('test2Modal').checked = false;
                document.getElementById('test1Modal').checked = true;
            }

            if (value.afectaCostoProducto === "0") {        //SI
                document.getElementById('test1Modal').checked = false;
                document.getElementById('test2Modal').checked = true;
            }
            $('#importeModal').val(parseFloat(value.importe));

        });
    });
}

function EliminarGasto(btn) {
    var idcompra = $('#idcompra').val();
    var idetallecompraGasto = btn.value;
    var route = "/elimanraGasto/";
    var token = $("#token").val();
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'GET',
        dataType: 'json',
        data: {
            idetallecompraGasto: idetallecompraGasto
        },
        success: function () {
            swal({title: "ELIMINACION COMPLETA",
                type: "success",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
            $('#tablaGasto').DataTable().destroy();
            cargartabla();
        },
        error: function () {
            Materialize.toast('Error al eliminar', 2500, 'rounded');
        }
    });
}

function cargarCuentaModal() {
    $('#cuentaBancariaModal')
            .find('option')
            .remove()
            .end();
    $('#cuentaBancariaModal').material_select();
    var route = "/listarCuentaBancaria/";
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $('#cuentaBancariaModal').append('<option  value=' + value.nroCuenta + ' >' + value.banco + "  " + value.nroCuenta + '</option>');
            $('#cuentaBancariaModal').material_select();
        });
    });
}


function listarProveedor() {
    $('#proveedor')
            .find('option')
            .remove()
            .end()
            .append('<option value="">Selecione un Proveedor</option>')
            .val('null');
    $('#proveedor').material_select();
    var route = "/listarProveedor/";
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $('#proveedor').append('<option  value=' + value.id + ' >' + value.nombre + '</option>');
            $('#proveedor').material_select();
        });
    });
}


function listarGastoCompra() {
    $('#gastoCompra')
            .find('option')
            .remove()
            .end()
            .append('<option value="">Selecione un Gasto</option>')
            .val('null');
    $('#gastoCompra').material_select();
    var route = "/listarGastoCompra/";
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $('#gastoCompra').append('<option  value=' + value.idTipoGasto + ' >' + value.nombre + '</option>');
            $('#gastoCompra').material_select();
        });
    });
}

function listarConcepto() {
    $('#concepto')
            .find('option')
            .remove()
            .end()
            .append('<option value="">Selecione una Forma de Pago</option>')
            .val('null');
    $('#concepto').material_select();
    var route = "/listarConcepto/";
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $('#concepto').append('<option  value=' + value.id + ' >' + value.nombre + '</option>');
            $('#concepto').material_select();
        });
    });
}


function cargarCuenta1() {
    $('#cuentaBancaria')
            .find('option')
            .remove()
            .end()
            .append('<option value="">Selecione una Cuenta</option>')
            .val('null');
    $('#cuentaBancaria').material_select();
    var route = "/listarCuentaBancaria/";
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $('#cuentaBancaria').append('<option  value=' + value.nroCuenta + ' >' + value.banco + "  " + value.nroCuenta + '</option>');
            $('#cuentaBancaria').material_select();
        });
    });
}





/*
 * Metodo para imprimir el comprobante de compra
 */
function imprimir(btn) {
    var iddelempleado = $('#iddelempleado').val();
    if ($("#perfilpuedeImprimir").val() == 1) {
        window.open("/reportecompra/" + btn.value + "/" + iddelempleado);
    } else {
        swal({title: "NO TIENE PERMISO PARA IMPRIMIR",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
}
/*
 * Metodo para eliminar una compra
 */
function Eliminar(btn) {
    if ($("#perfilpuedeEliminar").val() == 1) {
        var idventa = btn.value;
        var route = "/Compras/" + idventa + "";
        var token = $("#token").val();
        swal({title: "ESTA SEGURO QUE DESEA ELIMINAR LA COMPRA?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Si, eliminarla!",
            cancelButtonText: "No, cancelar!",
            closeOnConfirm: false,
            closeOnCancel: false},
                function (isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            url: route,
                            headers: {'X-CSRF-TOKEN': token},
                            type: 'DELETE',
                            dataType: 'json',
                            success: function () {
                                swal({title: "ELIMINACION COMPLETA",
                                    type: "success",
                                    showConfirmButton: false,
                                    closeOnConfirm: false,
                                    timer: 1000});
                                $('#tabla').DataTable().destroy();
                                Cargar();
                            },
                            error: function () {
                                swal({title: "ERROR AL ELIMINAR",
                                    type: "error",
                                    showConfirmButton: false,
                                    closeOnConfirm: false,
                                    timer: 1000});
                            }
                        });
                    } else {
                        swal({title: "CANCELADO",
                            type: "error",
                            showConfirmButton: false,
                            closeOnConfirm: false,
                            timer: 1000});
                    }
                });
    } else {
        swal({title: "NO TIENE PERMISO PARA ELIMINAR",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
}
/*
 * Metodo para generar una nueva compra
 */
$("#nuevacompra").click(function () {
    debugger
    if ($("#perfilpuedeGuardar").val() == 1) {
        var idpunto = iddelpuntoventa;
        var route = "/GenerarCompra/" + idpunto;
        $.get(route, function (res) {
            debugger
            $(res).each(function (key, value) {
                var dato = value.id;
                window.location.href = "/addCompras/" + dato;
            });
        });
    } else {
        swal({title: "NO TIENE PERMISO PARA REALZAR NUEVA COMPRA",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
});
/*
 * Metodo para editar una nueva compra
 */
function Mostrar($btn) {
    if ($("#perfilpuedeModificar").val() == 1) {
        var idventa = $btn;
        window.location.href = "/editarcompra/" + idventa;
    } else {
        swal({title: "NO TIENE PERMISO PARA MODIFICAR",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
}

function Mostrarsillego($btn,$dias) {
    var dias =$dias
     if (dias>0)
         {
        return  swal({title: "FALTAN "+dias+" DIAS,PARA QUE LLEGUE LA ORDEN",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 2000});   
         }
    if ($("#perfilpuedeModificar").val() == 1) {
        var idventa = $btn;
        window.location.href = "/editarcompra/" + idventa;
    } else {
        swal({title: "NO TIENE PERMISO PARA MODIFICAR",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
}
/*
 * Paginador de la tabla
 */
function paginador() {
    // Setup - add a text input to each footer cell
    $('#tabla tfoot th').each(function () {
        var title = $(this).text();
        if (title == "") {

        } else {
            $(this).html('<input type="text" placeholder="' + title + '" style=" border-radius: 3px;"/>');
        }
    });
    var table = $('#tabla').DataTable({
        pagingType: "full_numbers",
        retrieve: true,
        order: [0, 'desc'],
        responsive: true,
        lengthMenu: [[20, 40, 60], [20, 40, 60]]
    });
    table.columns().every(function () {
        var that = this;
        $('input', this.footer()).on('keyup change', function () {
            if (that.search() !== this.value) {
                that.search(this.value).draw();
            }
        });
    });
}

function paginadorGasto() {
    // Setup - add a text input to each footer cell
    $('#tablaGasto tfoot th').each(function () {
        var title = $(this).text();
        if (title == "") {

        } else {
            $(this).html('<input type="text" placeholder="' + title + '" style=" border-radius: 3px;"/>');
        }
    });
    var table = $('#tablaGasto').DataTable({
        pagingType: "full_numbers",
        retrieve: true,
        order: [0, 'desc'],
        responsive: true,
        lengthMenu: [[20, 40, 60], [20, 40, 60]]
    });
    table.columns().every(function () {
        var that = this;
        $('input', this.footer()).on('keyup change', function () {
            if (that.search() !== this.value) {
                that.search(this.value).draw();
            }
        });
    });
}