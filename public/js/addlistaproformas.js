var iddelpuntoventa;
var idempleado;
var perfil;
$(document).ready(function () {
    var hoy = new Date();
    var dd = hoy.getDate();
    var mm = hoy.getMonth() + 1; //hoy es 0!
    var yyyy = hoy.getFullYear();
    if (dd < 10) {
        dd = '0' + dd
    }
    if (mm < 10) {
        mm = '0' + mm
    }
    hoy = yyyy + '-' + mm + '-' + dd;
    $("#fecha").val(hoy);
    iddelpuntoventa = $('#iddelpuntoventa').val();
    idempleado = $('#iddelempleado').val();
    perfil = $("#iddelperfil").val();
    Cargar(hoy);
    $("#fecha").change(function () {
        $('#tablacategoria').DataTable().destroy();
        Cargar($("#fecha").val());
    });
});
/*
 * Metodo para listar las ventas
 */

function Cargar(fecha) {
    debugger;
    $("#listado").hide();
    $("#cargando").show();
    $('#datos').empty();
    var route = "/listarproformas";
    var token = $("#token").val();
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'GET',
        dataType: 'json',
        data: {
            idempleado: idempleado,
            iddelpuntoventa: iddelpuntoventa,
            idperfil: perfil,
            fecha: fecha
        },
        success: function ($route) {
            debugger;
            var tabladatos = $('#datos');
            $($route).each(function (key, value) {
                debugger;
                var cliente = value.cliente;
                var razon = value.razon;
                var totales;
                var estados = "";
                if (cliente == null) {
                    cliente = " ";
                }
                var estadoV = value.estadoVenta;
                var idVentaProforma = value.idventa;
                var desabilitado;
                if (estadoV == 0) {
                    desabilitado = "disabled";
                } else {
                    desabilitado = "";
                }

                if (razon == null) {
                    razon = " ";
                }
                if (value.estado == 1) {
                    estados = "Aprobado";
                }
                if (value.estado == 2) {
                    estados = "Anulado"
                }
                if (value.estado == 0) {
                    estados = "Guardado";
                }
                if (value.estado == 4) {
                    estados = "Proforma";
                }
                if (value.estado == 5) {
                    estados = "Credito";
                }
                if (value.total == null) {
                    totales = 0;
                } else {
                    totales = value.total;
                }
                var estadoEsVenta = value.esVenta;
                var importedescuento;
                if (value.importedescuento == null) {
                    importedescuento = 0;
                } else {
                    importedescuento = value.importedescuento;
                }
                var totalNeto = totales - value.importedescuento;
                var saldo;
                if (estados == "Aprobado") {
                    saldo = totalNeto - value.cobroAnticipo;
                } else {
                    saldo = 0;
                }
                saldo = saldo.toFixed(2);
                if (saldo < 1) {
                    saldo = 0;
                }
                if (value.nombrepergil === "Administrador") {//Administrador
//                    $("#etiqueta").attr("style", "display :block;");
                    debugger;

                    if (estados == "Guardado" && value.etapa !== "proforma") {

                        tabladatos.append("<tr>" +
                                "<td>" + value.id + "</td>" +
                                "<td>" + value.fecha + "</td>" +
                                "<td>" + value.hora + "</td>" +
                                "<td>" + value.nombre + "</td>" +
                                "<td>" + cliente + "</td>" +
                                "<td>" + razon + "</td>" +
                                "<td>" + estados + "</td>" +
                                "<td>" + totales + "</td>" +
                                "<td>" + importedescuento + "</td>" +
                                "<td>" + totalNeto + "</td>" +
                                "<td>" + value.formaPago + "</td>" +
                                "<td>" + value.cobroAnticipo + "</td>" +
                                "<td>" + saldo + "</td>" +
                                "<td></td>" +
                                "<td></td><td>" +
                                "<button value=" + value.id + " OnClick='Mostrar(" + value.id + ");' class='btn'  href='#' title='Editar' title='Editar'>" +
                                "<i class='material-icons'>mode_edit</i>" +
                                "</button>" +
                                "</td><td>" +
                                "<button class='btn btn-danger' value=" + value.id + " OnClick='Eliminar(this);' title='Eliminar' title='Eliminar'>" +
                                "<i class='material-icons'>delete</i>" +
                                "</button>" +
                                "</td><td>" +
                                "</td>" +
                                "</tr>");
                    } else if (estados == "Aprobado" && razon == " " && estadoEsVenta == 1) {
                        tabladatos.append("<tr>" +
                                "<td>" + value.id + "</td>" +
                                "<td>" + value.fecha + "</td>" +
                                "<td>" + value.hora + "</td>" +
                                "<td>" + value.nombre + "</td>" +
                                "<td>" + cliente + "</td>" +
                                "<td>" + razon + "</td>" +
                                "<td>" + estados + "</td>" +
                                "<td>" + totales + "</td>" +
                                "<td>" + importedescuento + "</td>" +
                                "<td>" + totalNeto + "</td>" +
                                "<td>" + value.formaPago + "</td>" +
                                "<td>" + value.cobroAnticipo + "</td>" +
                                "<td>" + saldo + "</td>" +
                                "<td><button class='btn btn-danger' onclick='ContratoChasisVenta(" + idVentaProforma + ")' title='Generar Venta' target='_blank' " + desabilitado + ">" +
                                "" + idVentaProforma + "</button>" + idVentaProforma + "</td>" +
                                "<td><button class='btn btn-danger' onclick='mensaje()' title='Generar Venta' target='_blank'>" +
                                "<i class='mdi-action-assignment'></i></button> </td>" +
                                "<td></td><td>" +
                                "<button class='btn btn-danger' value=" + value.id + " OnClick='mensaje();' title='Anular' >" +
                                "<i class='material-icons'>delete</i>" +
                                "</button>" +
                                "</td>" +
                                "<td><button class='btn btn-danger' onclick='ContratoChasis(" + value.id + ")' title='Generar Contrato' title='Nota de Venta' target='_blank'>" +
                                "<i class='mdi-editor-insert-chart'></i></button>  </td>" +
                                "</tr>");
                    } else if (estados == "Anulado") {
                        tabladatos.append("<tr>" +
                                "<td>" + value.id + "</td>" +
                                "<td>" + value.fecha + "</td>" +
                                "<td>" + value.hora + "</td>" +
                                "<td>" + value.nombre + "</td>" +
                                "<td>" + cliente + "</td>" +
                                "<td>" + razon + "</td>" +
                                "<td>" + estados + "</td>" +
                                "<td>" + totales + "</td>" +
                                "<td>" + importedescuento + "</td>" +
                                "<td>" + totalNeto + "</td>" +
                                "<td>" + value.formaPago + "</td>" +
                                "<td>" + value.cobroAnticipo + "</td>" +
                                "<td>" + saldo + "</td>" +
                                "<td></td>" +
                                "<td></td><td></td>" +
                                "<td></td>" +
                                "<td></td>" +
                                "</tr>");
                    } else if (estados == "Aprobado" && razon == " " && estadoEsVenta == 0) {
                        tabladatos.append("<tr>" +
                                "<td>" + value.id + "</td>" +
                                "<td>" + value.fecha + "</td>" +
                                "<td>" + value.hora + "</td>" +
                                "<td>" + value.nombre + "</td>" +
                                "<td>" + cliente + "</td>" +
                                "<td>" + razon + "</td>" +
                                "<td>" + estados + "</td>" +
                                "<td>" + totales + "</td>" +
                                "<td>" + importedescuento + "</td>" +
                                "<td>" + totalNeto + "</td>" +
                                "<td>" + value.formaPago + "</td>" +
                                "<td>" + value.cobroAnticipo + "</td>" +
                                "<td>" + saldo + "</td>" +
                                "<td></td>" +
                                "<td><button class='btn btn-danger' onclick='convertirVenta(" + value.id + ")' title='Generar Venta' target='_blank'>" +
                                "<i class='mdi-action-assignment'></i></button> </td>" +
                                "<td></td><td>" +
                                "<button class='btn btn-danger' value=" + value.id + " OnClick='Anular(" + value.id + ");' title='Anular'>" +
                                "<i class='material-icons'>delete</i>" +
                                "</button>" +
                                "</td>" +
                                "<td><button class='btn btn-danger' onclick='ContratoChasis(" + value.id + ")' title='Generar Contrato' title='Nota de Venta' target='_blank'>" +
                                "<i class='mdi-editor-insert-chart'></i></button>  </td>" +
                                "</tr>");
                    } else {
                        tabladatos.append("<tr>" +
                                "<td>" + value.id + "</td>" +
                                "<td>" + value.fecha + "</td>" +
                                "<td>" + value.hora + "</td>" +
                                "<td>" + value.nombre + "</td>" +
                                "<td>" + cliente + "</td>" +
                                "<td>" + razon + "</td>" +
                                "<td>" + estados + "</td>" +
                                "<td>" + totales + "</td>" +
                                "<td>" + importedescuento + "</td>" +
                                "<td>" + totalNeto + "</td>" +
                                "<td>" + value.formaPago + "</td>" +
                                "<td>" + value.cobroAnticipo + "</td>" +
                                "<td>" + saldo + "</td>" +
                                "<td></td>" +
                                "<td><button class='btn btn-danger' onclick='convertirVenta(" + value.id + ")' title='Generar Venta'target='_blank'>" +
                                "<i class='mdi-action-assignment'></i></button> </td><td></td>" +
                                "<td>" +
                                "</td>" +
                                "<td><button class='btn btn-danger' onclick='ContratoChasis(" + value.id + ")' title='Generar Contrato' title='Nota de Venta' target='_blank'>" +
                                "<i class='mdi-editor-insert-chart'></i></button></td>" +
                                "</tr>");
                    }


                } else {
                    //============================================================================LO DE ANTES
//                    $("#etiqueta").attr("style", "display :none;");
//                    var imagen = document.getElementById("#etiqueta");
//                    var padre = imagen.parentNode;
//                    padre.removeChild(imagen);

                    $("#etiqueta1").remove();
                    $("#etiqueta2").remove();
                    if (estados === "Guardado" && value.etapa !== "proforma") {

                        tabladatos.append("<tr>" +
                                "<td>" + value.id + "</td>" +
                                "<td>" + value.fecha + "</td>" +
                                "<td>" + value.hora + "</td>" +
                                "<td>" + cliente + "</td>" +
                                "<td>" + razon + "</td>" +
                                "<td>" + estados + "</td>" +
                                "<td>" + totales + "</td>" +
                                "<td>" + importedescuento + "</td>" +
                                "<td>" + totalNeto + "</td>" +
                                "<td>" + value.formaPago + "</td>" +
                                "<td>" + value.cobroAnticipo + "</td>" +
                                "<td>" + saldo + "</td>" +
                                "<td></td>" +
                                "<td></td><td>" +
                                "<button value=" + value.id + " OnClick='Mostrar(" + value.id + ");' class='btn'  href='#' title='Editar' title='Editar'>" +
                                "<i class='material-icons'>mode_edit</i>" +
                                "</button>" +
                                "</td><td>" +
                                "<button class='btn btn-danger' value=" + value.id + " OnClick='Eliminar(this);' title='Eliminar' title='Eliminar'>" +
                                "<i class='material-icons'>delete</i>" +
                                "</button>" +
                                "</td><td>" +
                                "</td>" +
                                "</tr>");
                    } else if (estados == "Aprobado" && razon == " " && estadoEsVenta == "1") {
                        tabladatos.append("<tr>" +
                                "<td>" + value.id + "</td>" +
                                "<td>" + value.fecha + "</td>" +
                                "<td>" + value.hora + "</td>" +
                                "<td>" + cliente + "</td>" +
                                "<td>" + razon + "</td>" +
                                "<td>" + estados + "</td>" +
                                "<td>" + totales + "</td>" +
                                "<td>" + importedescuento + "</td>" +
                                "<td>" + totalNeto + "</td>" +
                                "<td>" + value.formaPago + "</td>" +
                                "<td>" + value.cobroAnticipo + "</td>" +
                                "<td>" + saldo + "</td>" +
                                "<td><button class='btn btn-danger' onclick='ContratoChasisVenta(" + idVentaProforma + ")' title='Generar Venta' target='_blank' " + desabilitado + ">" +
                                "" + idVentaProforma + "</button></td>" +
                                "<td></td><td></td>" +
                                "<td>" +
                                "<button class='btn btn-danger' value=" + value.id + " OnClick='mensaje();' title='Anular'>" +
                                "<i class='material-icons'>delete</i>" +
                                "</button>" +
                                "</td>" +
                                "<td><button class='btn btn-danger' onclick='ContratoChasis(" + value.id + ")' title='Generar Proforma' target='_blank'>" +
                                "<i class='mdi-editor-insert-chart'></i></button></td>" +
                                "</tr>");
                    } else if (estados == "Anulado") {
                        tabladatos.append("<tr>" +
                                "<td>" + value.id + "</td>" +
                                "<td>" + value.fecha + "</td>" +
                                "<td>" + value.hora + "</td>" +
                                "<td>" + cliente + "</td>" +
                                "<td>" + razon + "</td>" +
                                "<td>" + estados + "</td>" +
                                "<td>" + totales + "</td>" +
                                "<td>" + importedescuento + "</td>" +
                                "<td>" + totalNeto + "</td>" +
                                "<td>" + value.formaPago + "</td>" +
                                "<td>" + value.cobroAnticipo + "</td>" +
                                "<td>" + saldo + "</td>" +
                                "<td></td>" +
                                "<td></td><td></td>" +
                                "<td></td>" +
                                "<td></td>" +
                                "</tr>");
                    } else if (estados == "Aprobado" && razon == " " && estadoEsVenta == "0") {
                        tabladatos.append("<tr>" +
                                "<td>" + value.id + "</td>" +
                                "<td>" + value.fecha + "</td>" +
                                "<td>" + value.hora + "</td>" +
                                "<td>" + cliente + "</td>" +
                                "<td>" + razon + "</td>" +
                                "<td>" + estados + "</td>" +
                                "<td>" + totales + "</td>" +
                                "<td>" + importedescuento + "</td>" +
                                "<td>" + totalNeto + "</td>" +
                                "<td>" + value.formaPago + "</td>" +
                                "<td>" + value.cobroAnticipo + "</td>" +
                                "<td>" + saldo + "</td>" +
                                "<td></td>" +
                                "<td><button class='btn btn-danger' onclick='convertirVenta(" + value.id + ")' title='Generar Venta' target='_blank'>" +
                                "<i class='mdi-action-assignment'></i></button> </td><td></td>" +
                                "<td>" +
                                "<button class='btn btn-danger' value=" + value.id + " OnClick='Anular(" + value.id + ");' title='Anular'>" +
                                "<i class='material-icons'>delete</i>" +
                                "</button>" +
                                "</td>" +
                                "<td><button class='btn btn-danger' onclick='ContratoChasis(" + value.id + ")' title='Generar Proforma' target='_blank'>" +
                                "<i class='mdi-editor-insert-chart'></i></button></td>" +
                                "</tr>");
                    } else {
                        tabladatos.append("<tr>" +
                                "<td>" + value.id + "</td>" +
                                "<td>" + value.fecha + "</td>" +
                                "<td>" + value.hora + "</td>" +
                                "<td>" + cliente + "</td>" +
                                "<td>" + razon + "</td>" +
                                "<td>" + estados + "</td>" +
                                "<td>" + totales + "</td>" +
                                "<td>" + importedescuento + "</td>" +
                                "<td>" + totalNeto + "</td>" +
                                "<td>" + value.formaPago + "</td>" +
                                "<td>" + value.cobroAnticipo + "</td>" +
                                "<td>" + saldo + "</td>" +
                                "<td></td>" +
                                "<td><button class='btn btn-danger' onclick='convertirVenta(" + value.id + ")' title='Generar Venta'  target='_blank'>" +
                                "<i class='mdi-action-assignment'></i></button> </td><td></td>" +
                                "<td>" +
                                "</td>" +
                                "<td><button class='btn btn-danger' onclick='ContratoChasis(" + value.id + ")' title='Generar Proforma' target='_blank'>" +
                                "<i class='mdi-editor-insert-chart'></i></button></td>" +
                                "</tr>");
                    }

                    //============================================================================
                }



            });
            paginador();
            $("#listado").show();
            $("#cargando").hide();
        },
        error: function () {
            swal({title: "ERROR AL CARGAR LAS VENTAS",
                type: "error",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        }
    });
}
/*
 * Metodo para crear una nueva venta
 */


$("#nuevaventas").click(function () {
    if ($("#perfilpuedeGuardar").val() == 1) {
        var idpunto = iddelpuntoventa;
        var route = "/GenerarVenta/" + idpunto;
        $.get(route, function (res) {
            $(res).each(function (key, value) {
                var dato = value.id;
                window.location.href = "/Ventas/" + dato;
            });
        });
    } else {
        swal({title: "No tiene permiso para vender",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
});

/*
 * Metodo para crear una nueva PROFORMA
 */

$("#nuevaproformas").click(function () {
    debugger;
    if (($("#perfilpuedeGuardar").val() == 1)|| (1==1)) {
        var idpunto = iddelpuntoventa;
        var route = "/GenerarProforma/" + idpunto;
        $.get(route, function (res) {
            $(res).each(function (key, value) {
                var dato = value.id;
                window.location.href = "/Proformas/" + dato;
            });
        });
    } else {
        swal({title: "No tiene permiso para vender",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
});

/*
 * Metodo para editar una venta
 */
function Mostrar($btn) {
    if ($("#perfilpuedeModificar").val() == 1) {
        var idventa = $btn;
        window.location.href = "/editarproforma/" + idventa;
    } else {
        swal({title: "No tiene permiso para modificar",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
}

function mensaje() {
    swal({title: "PRIMERO DEBE ANULAR LA VENTA QUE SE GENERO!!",
        type: "warning",
        showConfirmButton: false,
        closeOnConfirm: false,
        timer: 2000});
    return;
}

function ContratoChasis(venta) {
    window.open("/imprimirProforma/" + venta);
//    window.open("/imprimirContrato/" + venta);
//    window.open("/imprimirChasis/" + venta);
    return;
}


function ContratoChasisVenta(venta) {
//    window.open("/imprimirProforma/" + venta);

    var route = "/VerificarVenta/" + venta;

    $.get(route, function (res) {
        $(res).each(function (key, value) {
            debugger;

            var alerta = value.message;
            if (alerta === "Anulado") {
                swal({title: "Venta Anulada anteriormente!!",
                    type: "warning",
                    showConfirmButton: false,
                    closeOnConfirm: false,
                    timer: 2000});

            } else {
                window.open("/imprimirContrato/" + venta);
                window.open("/imprimirChasis/" + venta);
                return;
            }


        });
    });


}

/*
 * Metodo para llevar todo los datos de la proforma a una neuva venta
 */

function convertirVenta(idproforma) {
    debugger;
    var idpunto = iddelpuntoventa;
    var route = "/convertirProformaVenta/" + idpunto + "/" + idproforma;
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            debugger;
            var dato = value.id;
            var alerta = value.message;
            if (typeof alerta === 'undefined') {
                window.location.href = "/editarventa/" + dato;
            } else {
                swal({title: alerta,
                    type: "warning",
                    showConfirmButton: false,
                    closeOnConfirm: false,
                    timer: 2000});
            }


        });
    });

//    window.open("/convertirProformaVenta/" + idpunto + "/" + idproforma);

    return;
}

/*
 * Metodo para eliminar
 */
function Eliminar(btn) {
    if ($("#perfilpuedeEliminar").val() == 1) {
        var idventa = btn.value;
        var route = "/Proforma/" + idventa + "";
        var token = $("#token").val();
        swal({title: "ESTA SEGURO QUE DESEA ELIMINAR LA PROFORMA?",
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
                            data: {},
                            success: function () {
                                swal({title: "ELIMINACION COMPLETA",
                                    type: "success",
                                    showConfirmButton: false,
                                    closeOnConfirm: false,
                                    timer: 1000});
                                ;
                                $('#tablacategoria').DataTable().destroy();
                                Cargar($("#fecha").val());
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
        swal({title: "No tiene permiso para eliminar",
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
    $('#tablacategoria tfoot th').each(function () {
        var title = $(this).text();
        if (title == "") {

        } else {
            $(this).html('<input type="text" placeholder="' + title + '" style=" border-radius: 3px;"/>');
        }
    });
    var table = $('#tablacategoria').DataTable({
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
/*
 * Metodo para imprimir proforma
 */
function print(id) {
    if ($("#perfilpuedeModificar").val() == 1) {
        window.open("/imprimirproforma/" + id);
    } else {
        swal({title: "NO TIENE PERMISO PARA IMPRIMIR",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
}

function Anular(id) {
    debugger;
    if ($("#perfilpuedeEliminar").val() == 1) {
        swal({
            title: "ESTA SEGURO QUE DESEA ELIMINAR LA PROFORMA?",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Si, eliminarla!",
            cancelButtonText: "No, cancelar!",
            closeOnConfirm: false,
            closeOnCancel: false},
                function (isConfirm) {
                    if (isConfirm) {
                        var route = "/AnularProforma/" + id;
                        $.get(route, function (res) {
                            if (res == 1 || res == 4) {
                                $('#tablacategoria').DataTable().destroy();
                                Cargar($("#fecha").val());
                                swal({title: "VENTA ANULADA CON EXITO",
                                    type: "success",
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
        swal({title: "No tiene permiso para eliminar",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
}