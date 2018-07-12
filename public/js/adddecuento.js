$(document).ready(function () {
    Cargar();
    $("#descuento").ionRangeSlider({
        grid: true,
        min: 0,
        max: 100,
        from: 0
    });
    $("#descuentos").ionRangeSlider({
        grid: true,
        min: 0,
        max: 100,
        from: 0
    });
});

function openmodal(btn) {
    $("#modal2").openModal();
    Mostrar(btn);
}


/*
 * Metodo para listar los descuentos
 */
function Cargar() {
    var tabladatos = $('#datos');
    var route = "/listardescuento/";
    $('#datos').empty();
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            if (value.id == 1) {
            } else {
                tabladatos.append("<tr>" +
                        "<td>" + value.id + "</td>" +
                        "<td>" + value.nombre + "</td>" +
                        "<td>" + value.descuento + "%</td>" +
                        "<td>" + value.fechaInicio + "</td>" +
                        "<td>" + value.fechaFin + "</td>" +
                        "<td>" +
                        "<button value='" + value.id + "' OnClick='openmodal(this);' class='waves-effect waves-light btn' href='#' title='Editar'>" +
                        "<i class='material-icons'>mode_edit</i>" +
                        "</button>" +
                        "</td><td>" +
                        "<button class='btn btn-danger' value='" + value.id + "' OnClick='Eliminar(this);' title='Eliminar'>" +
                        "<i class='material-icons'>delete</i>" +
                        "</button>" +
                        "</td>" +
                        "</tr>");
            }
        });
        paginador();
    });
}
/*
 * Metodo para registrar nuevo descuento
 */
$("#guardar").click(function () {
    if ($("#perfilpuedeGuardar").val() == 1) {
        var nombre = $("#nombre").val();
        var descripcion = $("#descripcion").val();
        var descuento = $("#descuento").val();
        var fechaFin = $("#fechaFin").val();
        var fechaInicio = $("#fechaInicio").val();
        if (!nombre || !nombre.trim().length) {
            Materialize.toast('NOMBRE VACIO', 2000, 'rounded');
            return;
        }
        if (!fechaFin || !fechaFin.trim().length) {
            Materialize.toast('Fecha Fin Vacia', 2000, 'rounded');
            return;
        }
        if (!fechaInicio || !fechaInicio.trim().length) {
            Materialize.toast('Fecha Inicio Vacia', 2000, 'rounded');
            return;
        }
        var route = "/Descuento";
        var token = $("#token").val();
        $.ajax({
            url: route,
            headers: {'X-CSRF-TOKEN': token},
            type: 'POST',
            dataType: 'json',
            data: {
                nombre: nombre,
                descripcion: descripcion,
                descuento: descuento,
                fechaFin: fechaFin,
                fechaInicio: fechaInicio
            },
            success: function () {
                $('#tablacategoria').DataTable().destroy();
                Cargar();
                $(".lean-overlay").remove();
                $("#nombre").val("");
                $("#descripcion").val("");
                $("#descuento").val("");
                $("#modal1").closeModal();
                swal({title: "GUARDADO EXITOSO",
                    type: "success",
                    showConfirmButton: false,
                    closeOnConfirm: false,
                    timer: 1000});
            }, error: function () {
                swal({title: "ERROR AL GUARDAR",
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
 * Metodo para mostrar los datos de un descxuento en un modal para poder ser editado
 * @param {type} btn -> ID del descuento
 * @returns {undefined}
 */
function Mostrar(btn) {
    var route = "/Descuento/" + btn.value + "/edit";
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            debugger;
            $("#nombres").val(value.nombre);
            $("#descripcions").val(value.descripcion);
            $("#fechaFins").val(value.fechaFin);
            $("#fechaInicios").val(value.fechaInicio);
            var slider = $("#descuentos").data("ionRangeSlider");
            slider.update({
                min: 0,
                max: 100,
                from: value.descuento
            });
            $("#id").val(value.id);
        });
    });
}
/*
 * Metodo para eliminar
 * @param {type} btn -> ID del descuento a eliminar
 * @returns {undefined}
 */
function Eliminar(btn) {
    if ($("#perfilpuedeEliminar").val() == 1) {
        var route = "/Descuento/" + btn.value + "";
        var token = $("#token").val();
        swal({title: "ESTA SEGURO QUE DESEA ELIMINAR EL DESCUENTO?",
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
 * Metodo para actualizar un descuento
 */
$("#actualizar").click(function () {
    if ($("#perfilpuedeModificar").val() == 1) {
        var value = $("#id").val();
        var nombre = $("#nombres").val();
        var fechaFin = $("#fechaFins").val();
        var fechaInicio = $("#fechaInicios").val();
        if (!nombre || !nombre.trim().length) {
            Materialize.toast('NOMBRE VACIO', 2000, 'rounded');
            return;
        }
        if (!fechaFin || !fechaFin.trim().length) {
            Materialize.toast('Fecha Fin Vacia', 2000, 'rounded');
            return;
        }
        if (!fechaInicio || !fechaInicio.trim().length) {
            Materialize.toast('Fecha Fin Vacia', 2000, 'rounded');
            return;
        }
        var descripcion = $("#descripcions").val();
        var descuento = $("#descuentos").val();

        var route = "/Descuento/" + value + "";
        var token = $("#token").val();
        $.ajax({
            url: route,
            headers: {'X-CSRF-TOKEN': token},
            type: 'PUT',
            dataType: 'json',
            data: {
                nombre: nombre,
                descripcion: descripcion,
                descuento: descuento,
                fechaFin: fechaFin,
                fechaInicio: fechaInicio
            },
            success: function () {
                $('#tablacategoria').DataTable().destroy();
                Cargar();
                $("#modal2").closeModal();
                $('.lean-overlay').remove();
                swal({title: "ACTUALIZACION COMPLERA",
                    type: "success",
                    showConfirmButton: false,
                    closeOnConfirm: false,
                    timer: 1000});
            }, error: function () {
                swal({title: "ERROR AL ACTUALIZAR",
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
        order: [0, 'desc'],
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