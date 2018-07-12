var iddelpuntoventa;
var idempleado;
var perfil;
var idventa;
$(document).ready(function () {
    iddelpuntoventa = $('#iddelpuntoventa').val();
    idempleado = $('#iddelempleado').val();
    perfil = $("#iddelperfil").val();
    Cargar();
});

$("#nuevoaluiler").click(function () {
    if ($("#perfilpuedeGuardar").val() == 1) {
        var idpunto = iddelpuntoventa;
        var route = "/GenerarAlquiler/" + idpunto;
        $.get(route, function (res) {
            $(res).each(function (key, value) {
                var dato = value.id;
                window.location.href = "/alquilernuevo/" + dato;
            });
        });
    } else {
        swal({title: "No tiene permiso para alquilar",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
});

$("#devolver").click(function () {
    devolver(idventa);
});

function Cargar() {
    $("#listado").hide();
    $("#cargando").show();
    $('#datos').empty();
    var route = "/listalquiler";
    var token = $("#token").val();
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'GET',
        dataType: 'json',
        data: {},
        success: function ($route) {
            var tabladatos = $('#datos');
            $($route).each(function (key, value) {
                var cliente = value.cliente;
                var razon = value.razon;
                var totales;
                var estados = "";
                if (cliente == null) {
                    cliente = "";
                }
                if (razon == null) {
                    razon = "";
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
                var importedescuento;
                if (value.importedescuento == null) {
                    importedescuento = 0;
                } else {
                    importedescuento = value.importedescuento;
                }
                var totalNeto = totales - value.importedescuento;
                var saldo;
                if (estados == "Aprobado" && value.formaPago == "Credito") {
                    saldo = totalNeto - value.saldo;
                } else {
                    saldo = 0;
                }
                saldo = saldo.toFixed(2);
                var fechaDevolucion = value.fechaDevolucion;
                if (fechaDevolucion == null) {
                    fechaDevolucion = "";
                }
                var boton = "<td></td>";
                if (value.devolucion == "2") {
                    boton = "<td><a class='btn btn-danger btn-floating' href='#' onclick='javascript:openmodal(" + value.id + ")' title='Devolucion'>" +
                            "<i class='mdi-editor-vertical-align-bottom'></i>" +
                            "</a></td>";
                }
                if (estados == "Guardado") {
                    tabladatos.append("<tr>" +
                            "<td>" + value.id + "</td>" +
                            "<td>" + value.fecha + "</td>" +
                            "<td>" + value.hora + "</td>" +
                            "<td>" + value.nombre + "</td>" +
                            "<td>" + cliente + "</td>" +
                            "<td>" + razon + "</td>" +
                            "<td>" + estados + "</td>" +
//                            "<td>" + totales + "</td>" +
//                            "<td>" + importedescuento + "</td>" +
                            "<td>" + totalNeto + "</td>" +
                            "<td>" + value.formaPago + "</td>" +
                            "<td>" + value.garantia + "</td>" +
                            "<td>" + fechaDevolucion + "</td>" +
                            "<td>" +
                            "<button value=" + value.id + " OnClick='Mostrar(" + value.id + ");' class='btn btn-floating'  href='#' title='Editar' title='Editar'>" +
                            "<i class='material-icons'>mode_edit</i>" +
                            "</button>" +
                            "</td><td>" +
                            "<button class='btn btn-danger btn-floating' value=" + value.id + " OnClick='Eliminar(this);' title='Eliminar' title='Eliminar'>" +
                            "<i class='material-icons'>delete</i>" +
                            "</button>" +
                            "</td>" +
                            "<td></td>" +
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
//                            "<td>" + totales + "</td>" +
//                            "<td>" + importedescuento + "</td>" +
                            "<td>" + totalNeto + "</td>" +
                            "<td>" + value.formaPago + "</td>" +
                            "<td>" + value.garantia + "</td>" +
                            "<td>" + fechaDevolucion + "</td>" +
                            "<td></td>" +
                            "<td></td>" +
                            "<td></td>" +
                            "<td></td>" +
                            "</tr>");
                } else if (estados == "Aprobado" && razon == "") {
                    tabladatos.append("<tr>" +
                            "<td>" + value.id + "</td>" +
                            "<td>" + value.fecha + "</td>" +
                            "<td>" + value.hora + "</td>" +
                            "<td>" + value.nombre + "</td>" +
                            "<td>" + cliente + "</td>" +
                            "<td>" + razon + "</td>" +
                            "<td>" + estados + "</td>" +
//                            "<td>" + totales + "</td>" +
//                            "<td>" + importedescuento + "</td>" +
                            "<td>" + totalNeto + "</td>" +
                            "<td>" + value.formaPago + "</td>" +
                            "<td>" + value.garantia + "</td>" +
                            "<td>" + fechaDevolucion + "</td>" +
                            "<td></td>" +
                            "<td>" +
                            "<button class='btn btn-danger btn-floating' value=" + value.id + " OnClick='Anular(" + value.id + ");' title='Anular'>" +
                            "<i class='material-icons'>delete</i>" +
                            "</button>" +
                            "</td>" +
                            "<td><a class='btn btn-danger btn-floating' href='/imprimirnotaalquiler/" + value.id + "' title='Nota de Venta' target='_blank'>" +
                            "<i class='mdi-editor-insert-chart'></i>" +
                            "</a></td>" +
                            boton +
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
//                            "<td>" + totales + "</td>" +
//                            "<td>" + importedescuento + "</td>" +
                            "<td>" + totalNeto + "</td>" +
                            "<td>" + value.formaPago + "</td>" +
                            "<td>" + value.garantia + "</td>" +
                            "<td>" + fechaDevolucion + "</td>" +
                            "<td></td>" +
                            "<td>" +
                            "</td>" +
                            "<td><a class='btn btn-danger btn-floating' href='/imprimirnotaalquiler/" + value.id + "' title='Nota de Venta' target='_blank'>" +
                            "<i class='mdi-editor-insert-chart'></i>" +
                            "</a></td>" +
                            boton +
                            "</tr>");
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
            window.location.reload();
        }
    });
}

function Mostrar($btn) {
    if ($("#perfilpuedeModificar").val() == 1) {
        var idventa = $btn;
        window.location.href = "/editaralquiler/" + idventa;
    } else {
        swal({title: "No tiene permiso para modificar",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
}

function Eliminar(btn) {
    if ($("#perfilpuedeEliminar").val() == 1) {
        var idventa = btn.value;
        var route = "/Venta/" + idventa + "";
        var token = $("#token").val();
        swal({title: "ESTA SEGURO QUE DESEA ELIMINAR EL ALQUILER?",
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
                                swal({title: "ELIMINADO EXITOSO",
                                    type: "success",
                                    showConfirmButton: false,
                                    closeOnConfirm: false,
                                    timer: 1000});
                                ;
                                $('#tablacategoria').DataTable().destroy();
                                Cargar($("#fecha").val());
                                $("#modal1").closeModal();
                                $('.lean-overlay').remove();
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

function openmodal(btn) {
    obtenerProductos(btn);
}

function obtenerProductos(btn) {
    var route = "obteneralquiler/" + btn;
    $('#productos').empty();
    idventa = btn;
    $("#mostrardescuento").text("");
    $("#idvent").text("");
    $("#cliente").text("");
    $("#nit").text("");
    $("#mostrardescuento").text("");
    var tabladatos = $('#productos');
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $("#mostrardescuento").text(value.garantia);
            $("#idvent").text(value.idVenta);
            $("#cliente").text(value.cliente);
            $("#nit").text(value.nit);
            tabladatos.append("<div class='col s2'>" +
                    "<img alt='Logo Producto' src='" + value.imagen + "' width='100' lenght='100' class='circle responsive-img valign profile-image'/>" +
                    "</div>" +
                    "<div class='col s10'>" +
                    "<div><h6><strong>PRODUCTO:</strong>" + value.nombre + "</h6></div>" +
                    "<div><p><strong>CODIGO DE BARRA:</strong>" + value.codigoDeBarra + "</p></div>" +
                    "<div><p><strong>DESCRIPCION:</strong>" + value.descripcion + "</p></div><div class='divider'></div></div>" +
                    "");
            $("#modal1").openModal();
        });
    });

}

function devolver(btn) {
    var route = "devolverproducto/" + btn;
    $.get(route, function (res) {
        swal({title: "TRANSACCION COMPLETADA",
            type: "success",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
        $("#modal1").closeModal();
        $('.lean-overlay').remove();
        Cargar();
    });

}


/*
 * Paginador de la tabla
 */
function paginador() {
    // Setup - add a text input to each footer cell
    $('#tablacategoria tfoot th').each(function () {
        var title = $(this).text();
        $(this).html('<input type="text" placeholder="' + title + '" style=" border-radius: 3px;"/>');
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