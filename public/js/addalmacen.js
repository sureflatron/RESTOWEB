$(document).ready(function () {
    Cargar();
});

function openmodal(btn) {
    $("#modal2").openModal();
    Mostrar(btn);
}

/*
 * Listar los almacenes en la tabla
 */
function Cargar() {
    var tabladatos = $('#datos');
    var route = "/listaralmacen/";
    $('#datos').empty();
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            tabladatos.append("<tr>" +
                    "<td>" + value.nombre + "</td>" +
                    "<td>" + value.sucursal + "</td>" +
                    "<td>" + value.nombreempleado + "</td>" +
                    "<td>" +
                    "<button value=" + value.id + " OnClick='openmodal(this);' class='waves-effect waves-light btn btn-floating' href='#' title='Editar'>" +
                    "<i class='material-icons'>mode_edit</i>" +
                    "</button>" + "</td>" +
                    "<td>" +
                    "<button class='btn btn-danger btn-floating' value=" + value.id + " OnClick='Eliminar(this);' title='Eliminar'>" +
                    "<i class='material-icons'>delete</i>" +
                    "</button>" +
                    "</td>" +
                    "</tr>");
        });
        paginador();
    });
}
/*
 * Guardar un almacen
 */
$("#guardar").click(function () {
    if ($("#perfilpuedeGuardar").val() == 1) {
        var nombre = $("#nombre").val();
        var sucursal = $("#sucursal").val();
        var responsable = $("#responsable").val();
        if (!nombre || !nombre.trim().length) {
            Materialize.toast('NOMBRE VACIO', 2000, 'rounded');
            return;
        }
        if (!sucursal || !sucursal.trim().length || sucursal == 0) {
            Materialize.toast('SELECCIONE SUCURSAL', 2000, 'rounded');
            return;
        }
        if (!responsable || !responsable.trim().length || responsable == 0) {
            Materialize.toast('SELECCIONE RESPONSABLE', 2000, 'rounded');
            return;
        }
        var route = "/Almacen";
        var token = $("#token").val();
        $.ajax({
            url: route,
            headers: {'X-CSRF-TOKEN': token},
            type: 'POST',
            dataType: 'json',
            data: {
                nombre: nombre,
                sucursal: sucursal,
                responsable: responsable
            },
            success: function () {
                $('#tablacategoria').DataTable().destroy();
                Cargar();
                $(".lean-overlay").remove();
                $("#nombre").val("");
                $("#modal1").closeModal();
                swal({title: "GUARDADO EXITOSO",
                    type: "success",
                    showConfirmButton: false,
                    closeOnConfirm: false,
                    timer: 1000});
            },
            error: function () {
                swal({title: "YA EXISTE ESE ALMACEN ASIGANDO A LA SUCURSAL",
                    type: "error",
                    showConfirmButton: false,
                    closeOnConfirm: false,
                    timer: 1000});
            }
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
 * Eliminar Almacen
 */
function Eliminar(btn) {
    if ($("#perfilpuedeEliminar").val() == 1) {
        var route = "/Almacen/" + btn.value + "";
        var token = $("#token").val();
        swal({title: "ESTA SEGURO QUE DESEA ELIMINAR EL ALAMCEN?",
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
                            success: function () {
                                $('#tablacategoria').DataTable().destroy();
                                Cargar();
                                swal({title: "ELIMINACION COMPLETA",
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
        swal({title: "NO TIENE PERMISO PARA ELIMINAR",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
}
/*
 * Mostrar los datos de un almacen en un modal
 */
function Mostrar(btn) {
    var route = "/Almacen/" + btn.value + "/edit";
    $.get(route, function (res) {
        $("#nombres").val(res.nombre);
        var valorss = $("#sucursals").val(res.idsucursal);
        $('#sucursals option:selected').val(res.idsucursal);
        $("#sucursals").material_select();
        $("#id").val(res.id);
    });
}
/*
 * Actualizar los datos de un almacen
 */
$("#actualizar").click(function () {
    if ($("#perfilpuedeModificar").val() == 1) {
        var value = $("#id").val();
        var nombre = $("#nombres").val();
        var sucursal = $("#sucursals").val();
        var responsable1 = $("#responsables").val();
        if (!nombre || !nombre.trim().length) {
            Materialize.toast('NOMBRE VACIO', 2000, 'rounded');
            return;
        }
        if (!sucursal || !sucursal.trim().length || sucursal == 0) {
            Materialize.toast('SELECCIONE SUCURSAL', 2000, 'rounded');
            return;
        }
        if (!responsable1 || !responsable1.trim().length || responsable1 == 0) {
            Materialize.toast('SELECCIONE RESPONSABLE', 2000, 'rounded');
            return;
        }
        var route = "/Almacen/" + value + "";
        var token = $("#token").val();
        $.ajax({
            url: route,
            headers: {'X-CSRF-TOKEN': token},
            type: 'PUT',
            dataType: 'json',
            data: {
                nombre: nombre,
                sucursal: sucursal,
                responsable: responsable1
            },
            success: function () {
                $('#tablacategoria').DataTable().destroy();
                Cargar();
                $("#modal2").closeModal();
                swal({title: "ACTUALIZACION COMPLETA",
                    type: "success",
                    showConfirmButton: false,
                    closeOnConfirm: false,
                    timer: 1000});
            },
            error: function () {
                swal({title: "YA EXISTE ALAMACEN",
                    type: "error",
                    showConfirmButton: false,
                    closeOnConfirm: false,
                    timer: 1000});
            }
        });
    } else {
        swal({title: "NO TIENE PERMISO PARA MODIFICAR",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
});
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
        order: [0, 'asc'],
        responsive: true
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