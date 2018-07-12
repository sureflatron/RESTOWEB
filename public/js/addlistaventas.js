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
    debugger
    $("#listado").hide();
    $("#cargando").show();
    $('#datos').empty();
    var route = "/listarventas";
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
            debugger
            var tabladatos = $('#datos');
            $($route).each(function (key, value) {
                var cancelado = value.cancelado;
                var cliente = value.cliente;
                var razon = value.razon;
                var totales;
                var estados = "";
                if (cliente == null) {
                    cliente = " ";
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
                if (value.total == null) {
                    totales = 0;
                } else {
                    totales = value.total;
                }
                var importedescuento;
                if (value.importedescuento == null) {
                    importedescuento = 0;
                } else {
                    importedescuento = value.importedescuento;
                }
                var totalNeto = totales - value.importedescuento;
                var saldo;
                if (estados == "Aprobado" && value.etapa == "credito") {
                    saldo = totalNeto - value.saldo;
                } else {
                    saldo = 0;
                }
                totalNeto = totalNeto.toFixed(2);
                saldo = saldo.toFixed(2);
                if (saldo < 0.1) {
                    saldo = 0;
                }
                if (cancelado == 1) {
                    saldo = 0;
                }
                if (saldo == 0 && value.etapa == "credito") {
                    estados = "Credito Finalizado";
                }
                if (saldo > 0 && value.etapa == "credito") {
                    estados = "Credito Vigente";
                }
                if (value.estado == 2) {
                    estados = "Anulado";
                }
                if (value.nombrepergil === "Administrador") {//Administrador
                    if (estados == "Guardado" && value.etapa !== "proforma" && estados !== "Anulado") {
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
                                "<td>" + saldo + "</td>" +
                                "<td>" +
                                "<button value=" + value.id + " OnClick='Mostrar(" + value.id + ");' class='btn btn-floating'  href='#' title='Editar' title='Editar'>" +
                                "<i class='material-icons'>mode_edit</i>" +
                                "</button>" +
                                "</td><td>" +
                                "<button class='btn btn-danger btn-floating' value=" + value.id + " OnClick='Eliminar(this);' title='Eliminar' title='Eliminar'>" +
                                "<i class='material-icons'>delete</i>" +
                                "</button>" +
                                "</td><td>" +
                                "</td>" +
                                "<td></td>" +
                                "</tr>");
                    } else if (value.etapa == "proforma" && estados !== "Anulado" && estados == "Guardado") {
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
                                "<td>" + saldo + "</td>" +
                                "<td>" +
                                "<button value=" + value.id + " OnClick='Mostrar(" + value.id + ");' class='btn btn-floating'  href='#' title='Editar' title='Editar'>" +
                                "<i class='material-icons'>mode_edit</i>" +
                                "</button>" +
                                "</td><td>" +
                                "<button class='btn btn-danger btn-floating' value=" + value.id + " OnClick='Eliminar(this);' title='Eliminar' title='Eliminar'>" +
                                "<i class='material-icons'>delete</i>" +
                                "</button>" +
                                "</td><td>" +
                                "<a class='btn btn-floating' href='javascript:print(" + value.id + ")' >" +
                                "<i class='large material-icons'>print</>" +
                                "</a>" +
                                "</td>" +
                                "<td></td>" +
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
                                "<td>" + saldo + "</td>" +
                                "<td></td>" +
                                "<td></td>" +
                                "<td></td>" +
                                "<td></td>" +
                                "</tr>");
                    } else if ((estados == "Aprobado" || estados == "Credito Finalizado" || estados == "Credito Vigente") && razon == " ") {
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
                                "<td>" + saldo + "</td>" +
                                "<td></td>" +
                                "<td>" +
                                "<button class='btn btn-danger btn-floating' value=" + value.id + " OnClick='Anular(" + value.id + ");' title='Anular'>" +
                                "<i class='material-icons'>delete</i>" +
                                "</button>" +
                                "</td>" +
                                "<td></td>" +
                                "<td><a class='btn btn-danger btn-floating' href='/imprimirnotaventa/" + value.id + "' title='Nota de Venta' target='_blank'>" +
                                "<i class='mdi-editor-insert-chart'></i>" +
                                "</a></td>" +
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
                                "<td>" + saldo + "</td>" +
                                "<td></td>" +
                                "<td>" +
                                "</td>" +
                                "<td></td>" +
                                "<td><a class='btn btn-danger btn-floating' href='/imprimirnotaventa/" + value.id + "' title='Nota de Venta' target='_blank'>" +
                                "<i class='mdi-editor-insert-chart'></i>" +
                                "</a></td>" +
                                "</tr>");
                    }


                } else {
                    $("#etiqueta1").remove();
                    $("#etiqueta2").remove();
                    if (estados === "Guardado" && value.etapa !== "proforma" && estados !== "Anulado") {
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
                                "<td>" + saldo + "</td>" +
                                "<td>" +
                                "<button value=" + value.id + " OnClick='Mostrar(" + value.id + ");' class='btn'  href='#' title='Editar' title='Editar'>" +
                                "<i class='material-icons'>mode_edit</i>" +
                                "</button>" +
                                "</td><td>" +
                                "<button class='btn btn-danger btn-floating' value=" + value.id + " OnClick='Eliminar(this);' title='Eliminar' title='Eliminar'>" +
                                "<i class='material-icons'>delete</i>" +
                                "</button>" +
                                "</td><td>" +
                                "</td>" +
                                "<td></td>" +
                                "</tr>");
                    } else if (value.etapa == "proforma" && estados !== "Anulado" && estados === "Guardado") {
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
                                "<td>" + saldo + "</td>" +
                                "<td>" +
                                "<button value=" + value.id + " OnClick='Mostrar(" + value.id + ");' class='btn'  href='#' title='Editar' title='Editar'>" +
                                "<i class='material-icons'>mode_edit</i>" +
                                "</button>" +
                                "</td><td>" +
                                "<button class='btn btn-danger' value=" + value.id + " OnClick='Eliminar(this);' title='Eliminar' title='Eliminar'>" +
                                "<i class='material-icons'>delete</i>" +
                                "</button>" +
                                "</td><td>" +
                                "<a class='btn' href='javascript:print(" + value.id + ")' >" +
                                "<i class='large material-icons'>print</>" +
                                "</a>" +
                                "</td>" +
                                "<td></td>" +
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
                                "<td>" + saldo + "</td>" +
                                "<td></td>" +
                                "<td></td>" +
                                "<td></td>" +
                                "<td></td>" +
                                "</tr>");
                    } else if ((estados == "Aprobado" || estados == "Credito Finalizado" || estados == "Credito Vigente") && razon == " ") {
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
                                "<td>" + saldo + "</td>" +
                                "<td></td>" +
                                "<td>" +
                                "<button class='btn btn-danger' value=" + value.id + " OnClick='Anular(" + value.id + ");' title='Anular'>" +
                                "<i class='material-icons'>delete</i>" +
                                "</button>" +
                                "</td>" +
                                "<td></td>" +
                                "<td><a class='btn btn-danger' href='/imprimirnotaventa/" + value.id + "' title='Nota de Venta' target='_blank'>" +
                                "<i class='mdi-editor-insert-chart'></i>" +
                                "</a></td>" +
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
                                "<td>" + saldo + "</td>" +
                                "<td></td>" +
                                "<td>" +
                                "</td>" +
                                "<td></td>" +
                                "<td><a class='btn btn-danger' href='/imprimirnotaventa/" + value.id + "' title='Nota de Venta' target='_blank'>" +
                                "<i class='mdi-editor-insert-chart'></i>" +
                                "</a></td>" +
                                "</tr>");
                    }
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
 * Metodo para editar una venta
 */
function Mostrar($btn) {
    if ($("#perfilpuedeModificar").val() == 1) {
        var idventa = $btn;
        window.location.href = "/editarventa/" + idventa;
    } else {
        swal({title: "No tiene permiso para modificar",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
}
/*
 * Metodo para eliminar
 */
function Eliminar(btn) {
    if ($("#perfilpuedeEliminar").val() == 1) {
        var idventa = btn.value;
        var route = "/Venta/" + idventa + "";
        var token = $("#token").val();
        swal({title: "ESTA SEGURO QUE DESEA ELIMINAR LA VENTA?",
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
    if ($("#perfilpuedeEliminar").val() == 1) {
        swal({
            title: "ESTA SEGURO QUE DESEA ANULAR LA VENTA?",
            text: "En caso de que anule una venta al credito, las cuotas pagadas se anularan tambien",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Si, anularla!",
            cancelButtonText: "No, cancelar!",
            closeOnConfirm: false,
            closeOnCancel: false},
                function (isConfirm) {
                    if (isConfirm) {
                        var route = "/AnularVent/" + id;
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