var iddelpuntoventa;
var idempleado;
$(document).ready(function () {
    iddelpuntoventa = $('#iddelpuntoventa').val();
    idempleado = $('#iddelempleado').val();
    Cargar();
});


/*
 * Metodo para listar las ventas
 */
function Cargar() {
    $('#datos').empty();
    var route = "/proformas";
    var token = $("#token").val();nombreproducto
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
                var cliente = value.nombrecli;
                var razon = value.razon;
                var estados = "";
                if (cliente == null) {
                    cliente = " ";
                }
                if (razon == null) {
                    razon = " ";
                }
                if (value.estado == 1) {
                    estados = "Cobrado";
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
                var totalNeto = totales - value.importedescuento;
                tabladatos.append("<tr>" +
                        "<td>" + value.id + "</td>" +
                        "<td>" + value.fecha + "</td>" +
                        "<td>" + value.hora + "</td>" +
                        "<td>" + cliente + "</td>" +
                        "<td>" + totales + "</td>" +
                        "<td>" +
                        "<button value=" + value.id + " OnClick='Mostrar(" + value.id + ");' class='btn'  href='#' title='Editar' title='Editar'>" +
                        "<i class='material-icons'>mode_edit</i>" +
                        "</button>" +
                        "<button class='btn btn-danger' value=" + value.id + " OnClick='Eliminar(this);' title='Eliminar' title='Eliminar'>" +
                        "<i class='material-icons'>delete</i>" +
                        "</button>" +
                        "<a class='btn' href='javascript:print(" + value.id + ")' >" +
                        "<i class='large material-icons'>print</>" +
                        "</a>" +
                        "</td>" +
                        "</tr>");
            });
            paginador();
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
        swal({title: "No tiene permiso para eliminar",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
}