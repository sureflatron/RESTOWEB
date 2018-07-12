var iddelpuntoventa;
var idempleado;
$(document).ready(function () {
    iddelpuntoventa = $('#iddelpuntoventa').val();
    idempleado = $('#iddelempleado').val();
    Cargar();
});
/*
 * Cargar la lista de Inventarios
 */
function Cargar() {
    $('#datos').empty();
    var route = "/listarinventario";
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
                var tipo = "";
                var fecha = "";
                var hora = "";
                if (value.tipo != null) {
                    tipo = value.tipo;
                }
                if (value.fecha != null) {
                    fecha = value.fecha;
                }
                if (value.hora != null) {
                    hora = value.hora;
                }
                if (value.estado == 1) {
                    tabladatos.append("<tr>" +
                            "<td>" + value.id + "</td>" +
                            "<td>" + fecha + "</td>" +
                            "<td>" + hora + "</td>" +
                            "<td>" + tipo + "</td>" +
                            "<td>Pagado</td>" +
                            "<td>" + value.almacenOrigen + "</td>" +
                            "<td>" + value.almacenDestino + "</td>" +
                            "<td>" + value.motivo + "</td>" +
                            "<td>" + value.nombre + "</td>" +
                            "<td>" +
                            "</td><td>" +
                            "<button class='btn btn-danger waves-effect btn-floating' value=" + value.id + " OnClick='imprimir(this);' title='imprimir'>" +
                            "<i class='material-icons'>print</i></button>" +
                            "</td><td>" +
                            "<button class='btn btn-danger waves-effect btn-floating' value=" + value.id + " OnClick='Eliminar(this);' title='Eliminar' title='Eliminar'>" +
                            "<i class='material-icons'>delete</i></button>" +
                            "</td>" +
                            "</tr>");
                } else if (value.estado == 0) {
                    tabladatos.append("<tr>" +
                            "<td>" + value.id + "</td>" +
                            "<td>" + fecha + "</td>" +
                            "<td>" + hora + "</td>" +
                            "<td>" + tipo + "</td>" +
                            "<td>Guardado</td>" +
                            "<td>" + value.almacenOrigen + "</td>" +
                            "<td>" + value.almacenDestino + "</td>" +
                            "<td>" + value.motivo + "</td>" +
                            "<td>" + value.nombre + "</td>" +
                            "<td>" +
                            "<button value=" + value.id + " OnClick='Mostrar(" + value.id + ");' class='btn waves-effect btn-floating'  href='#' title='Editar' title='Editar'>" +
                            "<i class='material-icons'>mode_edit</i></button>" +
                            "</td><td></td><td>" +
                            "<button class='btn btn-danger waves-effect btn-floating' value=" + value.id + " OnClick='Eliminar(this);' title='Eliminar' title='Eliminar'>" +
                            "<i class='material-icons'>delete</i></button>" +
                            "</td>" +
                            "</tr>"
                            );
                }
            });
            paginador();
        },
        error: function () {
            swal({title: "ERROR AL CARGAR LOS DATOS",
                type: "error",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        }
    });
}
/*
 * Crear Nuevo Inventario
 */
$("#nuevainventario").click(function () {
    if ($("#perfilpuedeGuardar").val() == 1) {
        var idpunto = iddelpuntoventa;
        var route = "/Generarinventario/" + idpunto;
        $.get(route, function (res) {
            $(res).each(function (key, value) {
                var dato = value.id;
                window.location.href = "/addInventario/" + dato;
            });
        });
    } else {
        swal({title: "NO TIENE PERMISO PARA GUARDAR",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
});
/*
 * Modificar Inventario
 */
function Mostrar($btn) {
    if ($("#perfilpuedeModificar").val() == 1) {
        var idventa = $btn;
        window.location.href = "/editarinventario/" + idventa;
    } else {
        swal({title: "NO TIENE PERMISO PARA MODIFICAR",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }

}
/*
 * Eliminar inventario
 */
function Eliminar(btn) {
    if ($("#perfilpuedeEliminar").val() == 1) {
        var idventa = btn.value;
        var route = "/Inventario/" + idventa + "";
        var token = $("#token").val();
        swal({title: "ESTA SEGURO QUE DESEA ELIMINAR EL INVENTARIO?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Si, eliminarlo!",
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
                                $('#tablacategoria').DataTable().destroy();
                                Cargar();
                                swal({title: "ELIMINACION COMPLETA",
                                    type: "success",
                                    showConfirmButton: false,
                                    closeOnConfirm: false,
                                    timer: 1000});
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
 * Imprimir Inventario
 */
function imprimir(btn) {
    var iddelempleado = $('#iddelempleado').val();
    if ($("#perfilpuedeImprimir").val() == 1) {
        window.open("/reporteinventario/" + btn.value + "/" + iddelempleado + "/0");
    } else {
        swal({title: "NO TIENE PERMISO PARA IMPRIMIR",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
}