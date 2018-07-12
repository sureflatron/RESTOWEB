$(document).ready(function () {
    Cargar();
    $("#rangomin").ionRangeSlider({
        grid: true,
        min: 0,
        max: 15,
        from: 0
    });
    $("#actualizarangomin").ionRangeSlider({
        grid: true,
        min: 0,
        max: 15,
        from: 0
    });
});

function openmodal(btn) {
    $("#modal2").openModal();
    Mostrar(btn);
}

function Cargar() {
    var tabladatos = $('#datos');
    var route = "/listaturno/";
    $('#datos').empty();
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            tabladatos.append("<tr>" +
                    "<td>" + value.nombre + "</td>" +
                    "<td>" + value.minutosTolerancia + "</td>" +
                    "<td>" +
                    "<button value=" + value.id + " OnClick='openmodal(this);' class='waves-effect waves-light btn btn-floating'  href='#' title='Editar'>" +
                    "<i class='material-icons'>mode_edit</i>" +
                    "</button>" +
                    "</td><td>" +
                    "<button class='btn btn-danger btn-floating ' value=" + value.id + " OnClick='Eliminar(this);' title='Eliminar'>" +
                    "<i class='material-icons'>delete</i>" +
                    "</button>" +
                    "</td><td>" +
                    "<button class='btn btn-danger btn-floating' value=" + value.id + " OnClick='Horario(this);' title='Horario'>" +
                    "<i class='material-icons'>today</i>" +
                    "</button>" +
                    "</td>" +
                    "</tr>");
        });
        paginador();
    });
}

function Horario(btn) {
    var route = "/Gestionarhorario/" + btn.value;
    window.location.href = route;
}

function Mostrar(btn) {
    var route = "/Turno/" + btn.value + "/edit";
    $.get(route, function (res) {
        $("#actualizarnombre").val(res.nombre);
        $("#actualizarangomin").val(res.minutosTolerancia);
        $("#idactualizar").val(res.id);
        var slider = $("#actualizarangomin").data("ionRangeSlider");
        slider.update({
            min: 0,
            max: 15,
            from: res.minutosTolerancia
        });
    });
}

$("#actualizar").click(function () {
    if ($("#perfilpuedeModificar").val() == 1) {
        var value = $("#idactualizar").val();
        var nombre = $("#actualizarnombre").val();
        if (!nombre || !nombre.trim().length) {
            Materialize.toast('NOMBRE VACIO', 1000, 'rounded');
            return;
        }
        var minutosTolerancia = $("#actualizarangomin").val();
        var route = "/Turno/" + value + "";
        var token = $("#token").val();
        $.ajax({
            url: route,
            headers: {'X-CSRF-TOKEN': token},
            type: 'PUT',
            dataType: 'json',
            data: {
                nombre: nombre,
                minutosTolerancia: minutosTolerancia
            },
            success: function () {
                $('#tablacategoria').DataTable().destroy();
                Cargar();
                $("#modal2").closeModal();
                $('.lean-overlay').remove();
                swal({title: "ACTUALIZACION COMPLETA",
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
        swal({title: "NO TIENE PERMISO PARA ACTUALIZAR",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
});
function limpiarcampo() {
    $("#nuevonombre").val("");
    $("#rangomin").val("");
}

$("#guardar").click(function () {
    if ($("#perfilpuedeGuardar").val() == 1) {
        var nombre = $("#nuevonombre").val();
        if (!nombre || !nombre.trim().length) {
            Materialize.toast('NOMBRE VACIO', 1000, 'rounded');
            return;
        }
        var minutosTolerancia = $("#rangomin").val();
        var route = "/Turno";
        var token = $("#token").val();
        $.ajax({
            url: route,
            headers: {'X-CSRF-TOKEN': token},
            type: 'POST',
            dataType: 'json',
            data: {
                nombre: nombre,
                minutosTolerancia: minutosTolerancia
            },
            success: function () {
                $('#tablacategoria').DataTable().destroy();
                Cargar();
                $(".lean-overlay").remove();
                $("#modal1").closeModal();
                limpiarcampo();
                swal({title: "TURNO CREADO EXITOSAMENTE",
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
        wal({title: "NO TIENE PERMISO PARA GUARDAR",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
});

function Eliminar(btn) {
    if ($("#perfilpuedeEliminar").val() == 1) {
        var route = "/Turno/" + btn.value + "";
        var token = $("#token").val();
        swal({title: "ESTA SEGURO QUE DESEA ELIMINAR EL TURNO?",
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
        wal({title: "NO TIENE PERMISO PARA ELIMINAR",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
}

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